<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Cong�s
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique G�n�rale GNU publi�e par la Free Software Foundation.
Ce programme est distribu� car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but sp�cifique. Reportez-vous � la Licence Publique G�n�rale GNU pour plus de d�tails.
Vous devez avoir re�u une copie de la Licence Publique G�n�rale GNU en m�me temps 
que ce programme ; si ce n'est pas le cas, �crivez � la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, �tats-Unis.
*************************************************************************************************
This program is free software; you can redistribute it and/or modify it under the terms
of the GNU General Public License as published by the Free Software Foundation; either 
version 2 of the License, or any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*************************************************************************************************/

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG = FALSE ;
//$DEBUG = TRUE ;

// verif des droits du user � afficher la page
verif_droits_user($session, "is_resp", $DEBUG);

if($DEBUG==TRUE) { echo "SESSION :<br>\n"; print_r($_SESSION); echo "<br><hr><br>\n";}


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";
	
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$tab_bt_radio   = getpost_variable("tab_bt_radio") ;
	$tab_text_refus = getpost_variable("tab_text_refus") ;
	/*************************************/
	
	// titre
	echo "<H2>".$_SESSION['lang']['resp_traite_demandes_titre']."</H2>\n\n";
	//connexion mysql
	$mysql_link = connexion_mysql() ;

	if($tab_bt_radio=="") 
	{
		saisie($mysql_link, $DEBUG);
	}
	else 
	{
		traite_demande($mysql_link, $tab_bt_radio, $tab_text_refus, $DEBUG);
	}
	
	mysql_close($mysql_link);
	
	echo "</CENTER>\n";
	echo "</body>\n";
	echo "</html>\n";

	
	
/********************************************/
/*   FONCTIONS   */

function saisie($mysql_link, $DEBUG=FALSE) 
{
//$DEBUG=TRUE ;
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;
	$count1=0;
	$count2=0;
	
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges = recup_tableau_types_conges($mysql_link);
	
	/*********************************/
	// R�cup�ration des informations
	/*********************************/
	
	// R�cup dans un tableau de tableau des informations de tous les users dont $_SESSION['userlogin'] est responsable
	$tab_all_users_du_resp=recup_infos_all_users_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	if($DEBUG==TRUE) { echo "tab_all_users_du_resp :<br>\n"; print_r($tab_all_users_du_resp); echo "<br><br>\n";}
	
	// si tableau des users du resp n'est pas vide
	if( count($tab_all_users_du_resp)!=0 )
	{
		// constitution de la liste (s�par� par des virgules) des logins ...
		$list_users_du_resp="";
		foreach($tab_all_users_du_resp as $current_login => $tab_current_user)
		{
			if($list_users_du_resp=="")
				$list_users_du_resp= "'$current_login'" ;
			else
				$list_users_du_resp=$list_users_du_resp.", '$current_login'" ;
		}
	}


	// R�cup dans un tableau de tableau des informations de tous les users dont $_SESSION['userlogin'] est GRAND responsable
	if($_SESSION['config']['double_validation_conges']==TRUE)
	{
		$tab_all_users_du_grand_resp=recup_infos_all_users_du_grand_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);

		// si tableau des users du grand resp n'est pas vide
		if( count($tab_all_users_du_grand_resp)!=0 )
		{
			// constitution de la liste (s�par� par des virgules) des logins ...
			$list_users_du_grand_resp="";
			foreach($tab_all_users_du_grand_resp as $current_login => $tab_current_user)
			{
				if($list_users_du_grand_resp=="")
					$list_users_du_grand_resp= "'$current_login'" ;
				else
					$list_users_du_grand_resp=$list_users_du_grand_resp.", '$current_login'" ;
			}
		}
	}
	
	/*********************************/
	
	
	
	
	echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n" ;

	/*********************************/
	/* TABLEAU DES DEMANDES DES USERS DONT ON EST LE RESP */
	/*********************************/
	
	// si tableau des users du resp n'est pas vide
	if( count($tab_all_users_du_resp)!=0 )
	{
		
		// R�cup des demandes en cours pour les users dont $_SESSION['userlogin'] est responsable :
		$sql1 = "SELECT p_num, p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_date_demande, p_date_traitement FROM conges_periode ";
		$sql1=$sql1." WHERE p_etat =\"demande\" ";
		if($_SESSION['config']['responsable_virtuel']==TRUE)
			$sql1=$sql1." AND p_login != 'conges' ";
		else
			$sql1=$sql1." AND p_login IN ($list_users_du_resp) ";
		$sql1=$sql1." ORDER BY p_num";
		
		$ReqLog1 = requete_mysql($sql1, $mysql_link, "saisie", $DEBUG) ;
	
		$count1=mysql_num_rows($ReqLog1);
		if($count1!=0)
		{
			// AFFICHAGE TABLEAU DES DEMANDES EN COURS
			
			echo "<h3>".$_SESSION['lang']['resp_traite_demandes_titre_tableau_1']."</h3>\n" ;
			
			echo "<table cellpadding=\"2\" class=\"tablo\">\n" ;
			echo "<tr>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_nom_maj_1']."<br>".$_SESSION['lang']['divers_prenom_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_quotite_maj_1']."</td>" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_debut_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_demandes_nb_jours']."</td>";
			foreach($tab_type_conges as $id_conges => $libelle)
			{
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_solde_maj_1']."<br>$libelle</td>" ;
			}
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_accepter_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_refuser_maj_1']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_demandes_attente']."</td>\n" ;
			echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_demandes_motif_refus']."</td>\n" ;
			if($_SESSION['config']['affiche_date_traitement']==TRUE)
			{
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
			}
			echo "</tr>\n";
			
			$tab_bt_radio=array();
			while ($resultat1 = mysql_fetch_array($ReqLog1)) 
			{
				/** sur la ligne ,   **/
				/** le 1er bouton radio est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]--[valeur p_nb_jours]--$type--OK"> */
				/**  et le 2ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]--[valeur p_nb_jours]--$type--not_OK"> */
				/**  et le 3ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]--[valeur p_nb_jours]--$type--RIEN"> */
	
				$sql_p_date_deb = eng_date_to_fr($resultat1["p_date_deb"]);
				$sql_p_demi_jour_deb=$resultat1["p_demi_jour_deb"] ;
				if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
				$sql_p_date_fin = eng_date_to_fr($resultat1["p_date_fin"]);
				$sql_p_demi_jour_fin=$resultat1["p_demi_jour_fin"] ;
				if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
				$sql_p_commentaire = $resultat1["p_commentaire"];
				$sql_p_num = $resultat1["p_num"];
				$sql_p_login = $resultat1["p_login"];
				$sql_p_nb_jours = affiche_decimal($resultat1["p_nb_jours"]);
				$sql_p_type = $resultat1["p_type"];
				$sql_p_date_demande = $resultat1["p_date_demande"];
				$sql_p_date_traitement = $resultat1["p_date_traitement"];
				
				// si le user fait l'objet d'une double validation on a pas le meme resultat sur le bouton !
				if($tab_all_users_du_resp[$sql_p_login]['double_valid'] == "Y")
					{
						// si on est a la fois resp et grand resp
						if( (count($tab_all_users_du_grand_resp)!=0 ) && (array_key_exists($sql_p_login, $tab_all_users_du_grand_resp)) )
							$boutonradio1="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--OK\">";
						else  //on est QUe resp
							$boutonradio1="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--VALID\">";
					}
				else
					$boutonradio1="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--OK\">";
				
				$boutonradio2="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--not_OK\">";
				$boutonradio3="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--RIEN\" checked>";
	
				$text_refus="<input type=\"text\" name=\"tab_text_refus[$sql_p_num]\" size=\"20\" max=\"100\">";
				
				echo "<tr>\n" ;
				echo "<td class=\"histo\"><b>".$tab_all_users_du_resp[$sql_p_login]['nom']."</b><br>".$tab_all_users_du_resp[$sql_p_login]['prenom']."</td><td class=\"histo\">".$tab_all_users_du_resp[$sql_p_login]['quotite']."%</td>";
				echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td><td class=\"histo\">$sql_p_commentaire</td><td class=\"histo\"><b>$sql_p_nb_jours</b></td>";
				$tab_conges=$tab_all_users_du_resp[$sql_p_login]['conges']; 
				foreach($tab_type_conges as $id_conges => $libelle)
				{
					//echo "<td class=\"histo\">".$tab_conges[$id_conges]['solde']."</td>";
					echo "<td class=\"histo\">".$tab_conges[$libelle]['solde']."</td>";
				}
				echo "<td class=\"histo\">".$tab_type_conges[$sql_p_type]."</td>";
				echo "<td class=\"histo\">$boutonradio1</td><td class=\"histo\">$boutonradio2</td><td class=\"histo\">$boutonradio3</td><td class=\"histo\">$text_refus</td>\n";
				if($_SESSION['config']['affiche_date_traitement']==TRUE)
				{
					echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_p_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_p_date_traitement</td>\n" ;
				}
				
				echo "</tr>\n" ;
			} // while
			echo "</table>\n\n" ;
		} //if($count1!=0)
	} //if( count($tab_all_users_du_resp)!=0 )

	
	/*********************************/
	/* TABLEAU DES DEMANDES DES USERS DONT ON EST LE GRAND RESP */
	/*********************************/

	if($_SESSION['config']['double_validation_conges']==TRUE)
	{
		
		// si tableau des users du grand resp n'est pas vide
		if( count($tab_all_users_du_grand_resp)!=0 )
		{
			
			// R�cup des demandes en cours pour les users dont $_SESSION['userlogin'] est GRAND responsable :
			$sql2 = "SELECT p_num, p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_date_demande, p_date_traitement FROM conges_periode ";
			$sql2=$sql2." WHERE p_etat =\"valid\" ";
			$sql2=$sql2." AND p_login IN ($list_users_du_grand_resp) ";
			$sql2=$sql2." ORDER BY p_num";
			
			$ReqLog2 = requete_mysql($sql2, $mysql_link, "saisie", $DEBUG) ;
	
			$count2=mysql_num_rows($ReqLog2);
			if($count2!=0)
			{
				// AFFICHAGE TABLEAU DES DEMANDES EN COURS POUR DEUXIEME VALIDATION
				
				echo "<h3>".$_SESSION['lang']['resp_traite_demandes_titre_tableau_2']."</h3>\n" ;
			
				echo "<table cellpadding=\"2\" class=\"tablo\">\n" ;
				echo "<tr>\n" ;
				echo "<td class=\"titre\"><b>".$_SESSION['lang']['divers_nom_maj_1']."</b><br>".$_SESSION['lang']['divers_prenom_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_quotite_maj_1']."</td>" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_debut_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_demandes_nb_jours']."</td>";
				foreach($tab_type_conges as $id_conges => $libelle)
				{
					echo "<td class=\"titre\">".$_SESSION['lang']['divers_solde_maj_1']."<br>$libelle</td>" ;
				}
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_accepter_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_refuser_maj_1']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_demandes_attente']."</td>\n" ;
				echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_demandes_motif_refus']."</td>\n" ;
				if($_SESSION['config']['affiche_date_traitement']==TRUE)
				{
					echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
				}
				echo "</tr>\n";
				
				$tab_bt_radio=array();
				while ($resultat2 = mysql_fetch_array($ReqLog2)) 
				{
					/** sur la ligne ,   **/
					/** le 1er bouton radio est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]--[valeur p_nb_jours]--$type--OK"> */
					/**  et le 2ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]--[valeur p_nb_jours]--$type--not_OK"> */
					/**  et le 3ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]--[valeur p_nb_jours]--$type--RIEN"> */
		
					$sql_p_date_deb = eng_date_to_fr($resultat2["p_date_deb"]);
					$sql_p_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
					if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
					$sql_p_date_fin = eng_date_to_fr($resultat2["p_date_fin"]);
					$sql_p_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
					if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
					$sql_p_commentaire = $resultat2["p_commentaire"];
					$sql_p_num = $resultat2["p_num"];
					$sql_p_login = $resultat2["p_login"];
					$sql_p_nb_jours = affiche_decimal($resultat2["p_nb_jours"]);
					$sql_p_type = $resultat2["p_type"];
					$sql_p_date_demande = $resultat2["p_date_demande"];
					$sql_p_date_traitement = $resultat2["p_date_traitement"];
					
					$boutonradio1="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--OK\">";
					$boutonradio2="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--not_OK\">";
					$boutonradio3="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login--$sql_p_nb_jours--$sql_p_type--RIEN\" checked>";
		
					$text_refus="<input type=\"text\" name=\"tab_text_refus[$sql_p_num]\" size=\"20\" max=\"100\">";
					
					echo "<tr>\n" ;
					echo "<td class=\"histo\"><b>".$tab_all_users_du_grand_resp[$sql_p_login]['nom']."</b><br>".$tab_all_users_du_grand_resp[$sql_p_login]['prenom']."</td><td class=\"histo\">".$tab_all_users_du_grand_resp[$sql_p_login]['quotite']."%</td>";
					echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td><td class=\"histo\">$sql_p_commentaire</td><td class=\"histo\"><b>$sql_p_nb_jours</b></td>";
					$tab_conges=$tab_all_users_du_grand_resp[$sql_p_login]['conges']; 
					foreach($tab_type_conges as $id_conges => $libelle)
					{
						echo "<td class=\"histo\">".$tab_conges[$id_conges]['solde']."</td>";
					}
					echo "<td class=\"histo\">".$tab_type_conges[$sql_p_type]."</td>";
					echo "<td class=\"histo\">$boutonradio1</td><td class=\"histo\">$boutonradio2</td><td class=\"histo\">$boutonradio3</td><td class=\"histo\">$text_refus</td>\n";
					if($_SESSION['config']['affiche_date_traitement']==TRUE)
					{
						echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_p_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_p_date_traitement</td>\n" ;
					}
					
					echo "</tr>\n" ;
				} //while
				echo "</table>\n\n" ;
			} //if($count2!=0)
		} //if( count($tab_all_users_du_grand_resp)!=0 )
	} //if($_SESSION['config']['double_validation_conges']==TRUE)

	echo "<br>\n";

	if(($count1==0) && ($count2==0))
		echo "<b>".$_SESSION['lang']['resp_traite_demandes_aucune_demande']."</b><br><br><br>\n";		
	else
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n" ;
		
	echo " </form> \n" ;
	
	
	/*********************************/
	
	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"resp_main.php?session=$session\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_retour']."\">\n";
	echo " </form> \n";
}


function traite_demande($mysql_link, $tab_bt_radio, $tab_text_refus, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	while($elem_tableau = each($tab_bt_radio))
	{
		$champs = explode("--", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$type=$champs[2];   // id du type de conges demand�
		$reponse=$champs[3];
		
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		echo "$numero---$user_login---$user_nb_jours_pris---$reponse<br>\n";

		/* Modification de la table conges_periode */
		if(strcmp($reponse, "VALID")==0)
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"valid\", p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			/* On valide l'UPDATE dans la table "conges_periode" ! */
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "traite_demande", $DEBUG) ;

			// Log de l'action
			log_action($numero_int, "valid", $user_login, "traite demande $numero ($user_login) ($user_nb_jours_pris jours) : $reponse", $mysql_link, $DEBUG);
			
			//envoi d'un mail d'alerte au user et au responsable du resp (pour double validation) (si demand� dans config de php_conges)
			if($_SESSION['config']['mail_prem_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "valid_conges", $mysql_link, $DEBUG);
		}
		if(strcmp($reponse, "OK")==0)
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"ok\", p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			/* On valide l'UPDATE dans la table "conges_periode" ! */
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "traite_demande", $DEBUG) ;

			// Log de l'action
			log_action($numero_int,"ok", $user_login, "traite demande $numero ($user_login) ($user_nb_jours_pris jours) : $reponse", $mysql_link, $DEBUG);
			
			/* UPDATE table "conges_solde_user" (jours restants) */
			$sql2 = "UPDATE conges_solde_user SET su_solde=su_solde-$user_nb_jours_pris WHERE su_login='$user_login' AND su_abs_id=$type " ;
			$ReqLog2 = requete_mysql($sql2,$mysql_link, "traite_demande", $DEBUG) ;
			
			//envoi d'un mail d'alerte au user (si demand� dans config de php_conges)
			if($_SESSION['config']['mail_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "accept_conges", $mysql_link, $DEBUG);
		}
		elseif(strcmp($reponse, "not_OK")==0)
		{
			// recup du motif de refus
			$motif_refus=$tab_text_refus[$numero_int];
			$sql1 = "UPDATE conges_periode SET p_etat=\"refus\", p_motif_refus='$motif_refus', p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			//echo "$sql1<br>\n");
			
			// Log de l'action
			log_action($numero_int,"refus", $user_login, "traite demande $numero ($user_login) ($user_nb_jours_pris jours) : refus", $mysql_link, $DEBUG);
			
			/* On valide l'UPDATE dans la table ! */
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "traite_demande", $DEBUG) ;
			
			//envoi d'un mail d'alerte au user (si demand� dans config de php_conges)
			if($_SESSION['config']['mail_refus_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "refus_conges", $mysql_link, $DEBUG);
		}
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
	}
}


?>
