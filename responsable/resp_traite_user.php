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
include("../fonctions_javascript.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG = FALSE ;
//$DEBUG = TRUE ;

// verif des droits du user � afficher la page
verif_droits_user($session, "is_resp", $DEBUG);


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";

	/*** initialisation des variables ***/
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$user_login   = getpost_variable("user_login") ;
	$year_calendrier_saisie_debut = getpost_variable("year_calendrier_saisie_debut", 0) ;
	$mois_calendrier_saisie_debut = getpost_variable("mois_calendrier_saisie_debut", 0) ;
	$year_calendrier_saisie_fin = getpost_variable("year_calendrier_saisie_fin", 0) ;
	$mois_calendrier_saisie_fin = getpost_variable("mois_calendrier_saisie_fin", 0) ;
	$tri_date = getpost_variable("tri_date", "ascendant") ;
	$tab_checkbox_annule = getpost_variable("tab_checkbox_annule") ;
	$tab_radio_traite_demande = getpost_variable("tab_radio_traite_demande") ;
	$tab_text_refus = getpost_variable("tab_text_refus") ;
	$tab_text_annul = getpost_variable("tab_text_annul") ;
	$new_demande_conges = getpost_variable("new_demande_conges", 0) ;
	$new_debut = getpost_variable("new_debut") ;
	$new_demi_jour_deb = getpost_variable("new_demi_jour_deb") ;
	$new_fin = getpost_variable("new_fin") ;
	$new_demi_jour_fin = getpost_variable("new_demi_jour_fin") ;
	$new_nb_jours = getpost_variable("new_nb_jours") ;
	$new_comment = getpost_variable("new_comment") ;
	$new_type = getpost_variable("new_type") ;
	/*************************************/
	
	//echo "<br>$user_login";   /* envoy� par le formulaire pr�c�dent */

	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// si une annulation de conges a �t� selection�e :
	if($tab_checkbox_annule!="") 
	{
		annule_conges($user_login, $tab_checkbox_annule, $tab_text_annul, $mysql_link, $DEBUG);
	}
	// si le traitement des demandes a �t� selection�e :
	elseif($tab_radio_traite_demande!="") 
	{
		traite_demandes($user_login, $tab_radio_traite_demande, $tab_text_refus, $mysql_link, $DEBUG);
	}
	// si un nouveau conges ou absence a �t� saisi pour un user :
	elseif($new_demande_conges==1) 
	{
		new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type, $mysql_link, $DEBUG);
	}
	else 
	{
		affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date, $mysql_link, $DEBUG);
	}
	
	mysql_close($mysql_link);
	
	
echo "</CENTER>\n";
echo "</body>\n";
echo "</html>\n";
	
	
	
/*************************************/
/***   FONCTIONS   ***/
/*************************************/

function affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date, $mysql_link, $DEBUG)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id();

	// on initialise le tableau global des jours f�ri�s s'il ne l'est pas d�j� :
	if(!isset($_SESSION["tab_j_feries"]))
	{
		init_tab_jours_feries($mysql_link);
		//print_r($GLOBALS["tab_j_feries"]);   // verif DEBUG
	}
	
	/********************/
	/* affichage "deconnexion" et "actualiser page": */
	/********************/
	echo "</center>\n";
	echo "<table><tr>\n";
	if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
	{
		echo "<td valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
		echo "<td valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	/* // on affiche pas ce bouton car bug (pas de passage du user en param)
	echo "<td valign=\"middle\">\n";
	bouton_actualiser("", $DEBUG);
	echo "</td>\n";
	*/
	echo "</tr></table>\n";
	echo "<center>\n";
	
	/********************/
	/* R�cup�ration des informations sur le user : */
	/********************/
	$list_group_dbl_valid_du_resp = get_list_groupes_double_valid_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	$tab_user=array();
	$tab_user = recup_infos_du_user($user_login, $list_group_dbl_valid_du_resp, $mysql_link, $DEBUG);
	if($DEBUG==TRUE) { echo"tab_user =<br>\n"; print_r($tab_user); echo "<br>\n"; }
	
	$list_all_users_du_resp=get_list_all_users_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	if($DEBUG==TRUE) { echo"list_all_users_du_resp = $list_all_users_du_resp<br>\n"; }

	// recup des grd resp du user
	$tab_grd_resp=array();
	if($_SESSION['config']['double_validation_conges']==TRUE) 
	{
		get_tab_grd_resp_du_user($user_login, $tab_grd_resp, $mysql_link, $DEBUG);
		if($DEBUG==TRUE) { echo"tab_grd_resp =<br>\n"; print_r($tab_grd_resp); echo "<br>\n"; }
	}	
	
	/********************/
	/* Titre */
	/********************/
	echo "<H3>".$_SESSION['lang']['resp_traite_user_titre']."</H3><H2>".$tab_user['nom']." ".$tab_user['prenom'].".</H2>\n\n";

	
	/********************/
	/* Bilan des Conges */
	/********************/
	// AFFICHAGE TABLEAU
	// affichage du tableau r�capitulatif des solde de cong�s d'un user
	affiche_tableau_bilan_conges_user($user_login, $mysql_link);
	echo "<br><br>\n";

	/*************************/
	/* SAISIE NOUVEAU CONGES */
	/*************************/
	// dans le cas ou les users ne peuvent pas saisir de demande, le responsable saisi les cong�s :
	if(($_SESSION['config']['user_saisie_demande']==FALSE)||($_SESSION['config']['resp_saisie_mission']==TRUE)) 
	{
	
		// si les mois et ann�e ne sont pas renseign�s, on prend ceux du jour
		if($year_calendrier_saisie_debut==0)
			$year_calendrier_saisie_debut=date("Y");
		if($mois_calendrier_saisie_debut==0)
			$mois_calendrier_saisie_debut=date("m");
		if($year_calendrier_saisie_fin==0)
			$year_calendrier_saisie_fin=date("Y");
		if($mois_calendrier_saisie_fin==0)
			$mois_calendrier_saisie_fin=date("m");	
		if($DEBUG==TRUE) { echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n"; }
	
		echo "<H3>".$_SESSION['lang']['resp_traite_user_new_conges']."</H3>\n\n";
		
		//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
		saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, "", $mysql_link);

		echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	}

	/*********************/
	/* Etat des Demandes */
	/*********************/
	if($_SESSION['config']['user_saisie_demande']==TRUE) 
	{
		//verif si le user est bien un user du resp (et pas seulement du grad resp)
		if(strstr($list_all_users_du_resp, "'$user_login'")!=FALSE)
		{
			echo "<h3>".$_SESSION['lang']['resp_traite_user_etat_demandes']."</h3>\n";
	
			//affiche l'�tat des demande du user (avec le formulaire pour le responsable)
			affiche_etat_demande_user_for_resp($user_login, $tab_user, $tab_grd_resp, $mysql_link, $DEBUG);
	
			echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
		}
	}
	
	/*********************/
	/* Etat des Demandes en attente de 2ieme validation */
	/*********************/
	if($_SESSION['config']['double_validation_conges']==TRUE) 
	{
		/*******************************/
		/* verif si le resp est grand_responsable pour ce user*/
	
		if(in_array($_SESSION['userlogin'], $tab_grd_resp)==TRUE) // si resp_login est dans le tableau
		{
			echo "<h3>".$_SESSION['lang']['resp_traite_user_etat_demandes_2_valid']."</h3>\n";
	
			//affiche l'�tat des demande en attente de 2ieme valid du user (avec le formulaire pour le responsable)
			affiche_etat_demande_2_valid_user_for_resp($user_login, $mysql_link, $DEBUG);
	
			echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
		}
	}
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	echo "<h3>".$_SESSION['lang']['resp_traite_user_etat_conges']."</h3>\n";
	
	//affiche l'�tat des conges du user (avec le formulaire pour le responsable)
	affiche_etat_conges_user_for_resp($user_login, $tri_date, $mysql_link, $DEBUG);
	
	echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	
	
	/********************/
	/*  affichage "deconnexion" et "actualiser page":   */
	/********************/
	echo "</center>\n";
	echo "<table><tr>\n";
	if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
	{
		echo "<td valign=\"middle\">\n";
		bouton_deconnexion($DEBUG);
		echo "</td>\n";
		echo "<td valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	echo "<td valign=\"middle\">\n";
	bouton_actualiser("", $DEBUG);
	echo "</td></tr></table>\n";
	echo "<center>\n";

}



//affiche l'�tat des demande du user (avec le formulaire pour le responsable)
function affiche_etat_demande_user_for_resp($user_login, $tab_user, $tab_grd_resp, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;

	// R�cup�ration des informations
	$sql2 = "SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_date_demande, p_date_traitement, p_num " .
			"FROM conges_periode " .
			"WHERE p_login = '$user_login' AND p_etat ='demande' ".
			"ORDER BY p_date_deb";
	$ReqLog2 = requete_mysql($sql2, $mysql_link, "affichage", $DEBUG);
		
	$count2=mysql_num_rows($ReqLog2);
	if($count2==0)
	{
		echo "<b>".$_SESSION['lang']['resp_traite_user_aucune_demande']."</b><br><br>\n";		
	}
	else
	{
		// recup dans un tableau des types de conges
		$tab_type_conges = recup_tableau_types_conges($mysql_link);

		// AFFICHAGE TABLEAU
		echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
		//echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
		echo "<table cellpadding=\"2\" class=\"tablo\">\n";
		echo "<tr align=\"center\">\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_debut_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_nb_jours_pris_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_accepter_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_refuser_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_user_motif_refus']."</td>\n";
		if($_SESSION['config']['affiche_date_traitement']==TRUE)
		{
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
		}
		echo "</tr>\n";
		
		$tab_checkbox=array();
		while ($resultat2 = mysql_fetch_array($ReqLog2)) 
		{
			$sql_date_deb=eng_date_to_fr($resultat2["p_date_deb"]) ;
			$sql_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
			if($sql_demi_jour_deb=="am") 
				$demi_j_deb = $_SESSION['lang']['divers_am_short'];
			else
				$demi_j_deb = $_SESSION['lang']['divers_pm_short'];
			$sql_date_fin=eng_date_to_fr($resultat2["p_date_fin"]) ;
			$sql_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
			if($sql_demi_jour_fin=="am")
				$demi_j_fin = $_SESSION['lang']['divers_am_short'];
			else
				$demi_j_fin = $_SESSION['lang']['divers_pm_short'];
			$sql_nb_jours=affiche_decimal($resultat2["p_nb_jours"]) ;
			$sql_commentaire=$resultat2["p_commentaire"] ;
			$sql_type=$resultat2["p_type"] ;
			$sql_date_demande = $resultat2["p_date_demande"];
			$sql_date_traitement = $resultat2["p_date_traitement"];
			$sql_num=$resultat2["p_num"] ;
			
			// si le user fait l'objet d'une double validation on a pas le meme resultat sur le bouton !
			if($tab_user['double_valid'] == "Y")
			{
				/*******************************/
				/* verif si le resp est grand_responsable pour ce user*/
				if(in_array($_SESSION['userlogin'], $tab_grd_resp)==TRUE) // si resp_login est dans le tableau
					$boutonradio1="<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--VALID\">";
				else
					$boutonradio1="<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--ACCEPTE\">";				
			}
			else
				$boutonradio1="<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--ACCEPTE\">";
			//$boutonradio1 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--ACCEPTE\">";

			$boutonradio2 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--REFUSE\">";
			$text_refus  = "<input type=\"text\" name=\"tab_text_refus[$sql_num]\" size=\"20\" max=\"100\">";
			echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td>\n";
			echo "<td class=\"histo\">$sql_date_fin _ $demi_j_fin</td>\n";
			echo "<td class=\"histo\">$sql_nb_jours</td>\n";
			echo "<td class=\"histo\">$sql_commentaire</td>\n";
			echo "<td class=\"histo\">".$tab_type_conges[$sql_type]."</td>\n";
			echo "<td class=\"histo\">$boutonradio1</td>\n";
			echo "<td class=\"histo\">$boutonradio2</td>\n";
			echo "<td class=\"histo\">$text_refus</td>\n";
			if($_SESSION['config']['affiche_date_traitement']==TRUE)
			{
				echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_date_traitement</td>\n" ;
			}
			echo "</tr>\n";
		}
		echo "</table>\n\n";

		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"".$_SESSION['lang']['form_cancel']."\">\n";
		echo " </form> \n";
	}
}



//affiche l'�tat des demande en attente de 2ieme validation du user (avec le formulaire pour le responsable)
function affiche_etat_demande_2_valid_user_for_resp($user_login, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;

		// R�cup�ration des informations
		$sql2 = "SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_date_demande, p_date_traitement, p_num " .
				"FROM conges_periode " .
				"WHERE p_login = '$user_login' AND p_etat ='valid' ORDER BY p_date_deb";
		$ReqLog2 = requete_mysql($sql2, $mysql_link, "affichage", $DEBUG);
			
		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			echo "<b>".$_SESSION['lang']['resp_traite_user_aucune_demande']."</b><br><br>\n";		
		}
		else
		{
			// recup dans un tableau des types de conges
			$tab_type_conges = recup_tableau_types_conges($mysql_link);
	
			// AFFICHAGE TABLEAU
			echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
			//echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
			echo "<table cellpadding=\"2\" class=\"tablo\">\n";
			echo "<tr align=\"center\">\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_debut_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_nb_jours_pris_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_accepter_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_refuser_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_user_motif_refus']."</td>\n";
			if($_SESSION['config']['affiche_date_traitement']==TRUE)
			{
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
			}
			echo "</tr>\n";
			
			$tab_checkbox=array();
			while ($resultat2 = mysql_fetch_array($ReqLog2)) 
			{
				$sql_date_deb=eng_date_to_fr($resultat2["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am") 
					$demi_j_deb = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_deb = $_SESSION['lang']['divers_pm_short'];
				$sql_date_fin=eng_date_to_fr($resultat2["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am")
					$demi_j_fin = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_fin = $_SESSION['lang']['divers_pm_short'];
				$sql_nb_jours=affiche_decimal($resultat2["p_nb_jours"]) ;
				$sql_commentaire=$resultat2["p_commentaire"] ;
				$sql_type=$resultat2["p_type"] ;
				$sql_date_demande = $resultat2["p_date_demande"];
				$sql_date_traitement = $resultat2["p_date_traitement"];
				$sql_num=$resultat2["p_num"] ;
				
				$casecocher1 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--ACCEPTE\">";
				$casecocher2 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--REFUSE\">";
				$text_refus  = "<input type=\"text\" name=\"tab_text_refus[$sql_num]\" size=\"20\" max=\"100\">";
				echo "<tr align=\"center\">\n";
				echo "<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td>\n";
				echo "<td class=\"histo\">$sql_date_fin _ $demi_j_fin</td>\n";
				echo "<td class=\"histo\">$sql_nb_jours</td>\n";
				echo "<td class=\"histo\">$sql_commentaire</td>\n";
				echo "<td class=\"histo\">".$tab_type_conges[$sql_type]."</td>\n";
				echo "<td class=\"histo\">$casecocher1</td>\n";
				echo "<td class=\"histo\">$casecocher2</td>\n";
				echo "<td class=\"histo\">$text_refus</td>\n";
				if($_SESSION['config']['affiche_date_traitement']==TRUE)
				{
					echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_date_traitement</td>\n" ;
				}
				echo "</tr>\n";
			}
			echo "</table>\n\n";
	
			echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
			echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"".$_SESSION['lang']['form_cancel']."\">\n";
			echo " </form> \n";
		}

}



//affiche l'�tat des conges du user (avec le formulaire pour le responsable)
function affiche_etat_conges_user_for_resp($user_login, $tri_date, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;
	
	// R�cup�ration des informations de speriodes de conges/absences
	$sql3 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_motif_refus, p_date_demande, p_date_traitement, p_num FROM conges_periode WHERE p_login = '$user_login' AND p_etat !='demande' AND p_etat !='valid' ";
	if($tri_date=="descendant")
		$sql3=$sql3." ORDER BY p_date_deb DESC ";
	else
		$sql3=$sql3." ORDER BY p_date_deb ASC ";
		
	$ReqLog3 = requete_mysql($sql3, $mysql_link, "affiche_etat_conges_user_for_resp", $DEBUG);

	$count3=mysql_num_rows($ReqLog3);
	if($count3==0)
	{
		echo "<b>".$_SESSION['lang']['resp_traite_user_aucun_conges']."</b><br><br>\n";		
	}
	else
	{
		// recup dans un tableau de tableau les infos des types de conges et absences
		$tab_types_abs = recup_tableau_tout_types_abs($mysql_link, $DEBUG) ;
		
		// AFFICHAGE TABLEAU
		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
		//echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
		echo "<table cellpadding=\"2\" class=\"tablo\">\n";
		echo "<tr align=\"center\">\n";
		echo " <td class=\"titre\">\n";
		echo " <a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>\n";
		echo " ".$_SESSION['lang']['divers_debut_maj_1']." \n";
		echo " <a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>\n";
		echo " </td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_nb_jours_pris_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."<br><i>".$_SESSION['lang']['resp_traite_user_motif_possible']."</i></td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_etat_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['resp_traite_user_annul']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['resp_traite_user_motif_annul']."</td>\n";
		if($_SESSION['config']['affiche_date_traitement']==TRUE)
		{
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
		}
		echo "</tr>\n";
		$tab_checkbox=array();
		while ($resultat3 = mysql_fetch_array($ReqLog3)) 
		{
				$sql_login=$resultat3["p_login"] ;
				$sql_date_deb=eng_date_to_fr($resultat3["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat3["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am")
					$demi_j_deb = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_deb = $_SESSION['lang']['divers_pm_short'];
				$sql_date_fin=eng_date_to_fr($resultat3["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat3["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am")
					$demi_j_fin = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_fin = $_SESSION['lang']['divers_pm_short'];
				$sql_nb_jours=affiche_decimal($resultat3["p_nb_jours"]) ;
				$sql_commentaire=$resultat3["p_commentaire"] ;
				$sql_type=$resultat3["p_type"] ;
				$sql_etat=$resultat3["p_etat"] ;
				$sql_motif_refus=$resultat3["p_motif_refus"] ;
				$sql_p_date_demande = $resultat3["p_date_demande"];
				$sql_p_date_traitement = $resultat3["p_date_traitement"];
				$sql_num=$resultat3["p_num"] ;
				
				if(($sql_etat=="annul") || ($sql_etat=="refus") || ($sql_etat=="ajout")) 
				{
					$casecocher1="";
					if($sql_etat=="refus")
					{
						if($sql_motif_refus=="")
							$sql_motif_refus = $_SESSION['lang']['divers_inconnu'] ;
						//$text_annul="<i>motif du refus : $sql_motif_refus</i>";
						$text_annul="<i>".$_SESSION['lang']['resp_traite_user_motif']." : $sql_motif_refus</i>";
					}
					elseif($sql_etat=="annul")
					{
						if($sql_motif_refus=="")
							$sql_motif_refus = $_SESSION['lang']['divers_inconnu'] ;
						//$text_annul="<i>motif de l'annulation : $sql_motif_refus</i>";
						$text_annul="<i>".$_SESSION['lang']['resp_traite_user_motif']." : $sql_motif_refus</i>";
					}
				}
				else
				{
					$casecocher1=sprintf("<input type=\"checkbox\" name=\"tab_checkbox_annule[$sql_num]\" value=\"$sql_login--$sql_nb_jours--$sql_type--ANNULE\">");
					$text_annul="<input type=\"text\" name=\"tab_text_annul[$sql_num]\" size=\"20\" max=\"100\">";
				}

				echo "<tr align=\"center\">\n";
					echo "<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td>\n";
					echo "<td class=\"histo\">$sql_date_fin _ $demi_j_fin</td>\n";
					echo "<td class=\"histo\">$sql_nb_jours</td>\n";
					echo "<td class=\"histo\">$sql_commentaire</td>\n";
					echo "<td class=\"histo\">".$tab_types_abs[$sql_type]['libelle']."</td>\n";
					echo "<td class=\"histo\">";
					if($sql_etat=="refus")
						echo $_SESSION['lang']['divers_refuse'];
					elseif($sql_etat=="annul")
						echo $_SESSION['lang']['divers_annule'];
					else
						echo "$sql_etat";
					echo "</td>\n";
					echo "<td class=\"histo\">$casecocher1</td>\n";
					echo "<td class=\"histo\">$text_annul</td>\n";
					if($_SESSION['config']['affiche_date_traitement']==TRUE)
					{
						echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_p_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_p_date_traitement</td>\n" ;
					}
					echo "</tr>\n";
			}
		echo "</table>\n\n";

		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n";
		echo " </form> \n";
	}
}



function annule_conges($user_login, $tab_checkbox_annule, $tab_text_annul, $mysql_link, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;
	
	// recup dans un tableau de tableau les infos des types de conges et absences
	$tab_tout_type_abs = recup_tableau_tout_types_abs($mysql_link, $DEBUG);
	
	while($elem_tableau = each($tab_checkbox_annule))
	{
		$champs = explode("--", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$user_nb_jours_pris_float=(float) $user_nb_jours_pris ;
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		$user_type_abs_id=$champs[2];
		
		$motif_annul=$tab_text_annul[$numero_int];
		
		if($DEBUG==TRUE) { echo "<br><br>conges numero :$numero ---> login : $user_login --- nb de jours : $user_nb_jours_pris_float --- type : $user_type_abs_id ---> ANNULER <br>"; }

		/* UPDATE table "conges_periode" */
		$sql1 = "UPDATE conges_periode SET p_etat=\"annul\", p_motif_refus='$motif_annul', p_date_traitement=NOW() WHERE p_num=$numero_int" ;
		$ReqLog1 = requete_mysql($sql1, $mysql_link, "annule_conges", $DEBUG);

		/* UPDATE table "conges_solde_user" (jours restants) */
		// on re-cr�dite les jours seulement pour des conges pris (pas pour les absences)
		// donc seulement si le type de l'absence qu'on annule est un "conges"
		if($tab_tout_type_abs[$user_type_abs_id]['type']=="conges")
		{
			$sql2 = "UPDATE conges_solde_user SET su_solde = su_solde+$user_nb_jours_pris_float WHERE su_login='$user_login' AND su_abs_id=$user_type_abs_id " ;
			//echo($sql2."<br>");
			$ReqLog2 = requete_mysql($sql2, $mysql_link, "annule_conges", $DEBUG);
		}
		
		//envoi d'un mail d'alerte au user (si demand� dans config de php_conges)
		if($_SESSION['config']['mail_annul_conges_alerte_user']==TRUE)
			alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "annul_conges", $mysql_link, $DEBUG);
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";
	}

}



function traite_demandes($user_login, $tab_radio_traite_demande, $tab_text_refus, $mysql_link, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id();

	// recup dans un tableau de tableau les infos des types de conges et absences
	$tab_tout_type_abs = recup_tableau_tout_types_abs($mysql_link, $DEBUG);
	
	while($elem_tableau = each($tab_radio_traite_demande))
	{
		$champs = explode("--", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$user_nb_jours_pris_float=(float) $user_nb_jours_pris ;
		$value_type_abs_id=$champs[2];
		$value_traite=$champs[3];
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		if($DEBUG==TRUE) { echo "<br><br>conges numero :$numero --- User_login : $user_login --- nb de jours : $user_nb_jours_pris --->$value_traite<br>" ; }

		if($value_traite == "ACCEPTE") 
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"ok\", p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "traite_demandes", $DEBUG);

			/* UPDATE table "conges_solde_user" (jours restants) */
			// on retranche les jours seulement pour des conges pris (pas pour les absences)
			// donc seulement si le type de l'absence qu'on annule est un "conges"
			if($tab_tout_type_abs[$value_type_abs_id]['type']=="conges")
				$sql2 = "UPDATE conges_solde_user SET su_solde = su_solde-$user_nb_jours_pris_float WHERE su_login='$user_login' AND su_abs_id=$value_type_abs_id " ;
			//echo($sql2."<br>");
			$ReqLog2 = requete_mysql($sql2, $mysql_link, "traite_demandes", $DEBUG);
			
			//envoi d'un mail d'alerte au user (si demand� dans config de php_conges)
			if($_SESSION['config']['mail_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "accept_conges", $mysql_link, $DEBUG);
		}
		elseif($value_traite == "VALID") 
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"valid\", p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "traite_demandes", $DEBUG);

			//envoi d'un mail d'alerte au user (si demand� dans config de php_conges)
			if($_SESSION['config']['mail_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "valid_conges", $mysql_link, $DEBUG);
		}
		elseif($value_traite == "REFUSE") 
		{
			// recup di motif de refus
			$motif_refus=$tab_text_refus[$numero_int];
			//$sql3 = "UPDATE conges_periode SET p_etat=\"refus\" WHERE p_num=$numero_int" ;
			$sql3 = "UPDATE conges_periode SET p_etat=\"refus\", p_motif_refus='$motif_refus', p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			$ReqLog3 = requete_mysql($sql3, $mysql_link, "traite_demandes", $DEBUG);
		
			//envoi d'un mail d'alerte au user (si demand� dans config de php_conges)
			if($_SESSION['config']['mail_refus_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $user_nb_jours_pris, "refus_conges", $mysql_link, $DEBUG);
		}
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";
	}

}

function new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type_id, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	// verif validit� des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment);
	
	if($valid==TRUE)
	{
		echo "$user_login---$new_debut _ $new_demi_jour_deb---$new_fin _ $new_demi_jour_fin---$new_nb_jours---$new_comment---$new_type<br>\n";

		// recup dans un tableau de tableau les infos des types de conges et absences
		$tab_tout_type_abs = recup_tableau_tout_types_abs($mysql_link, $DEBUG);
	
		/**********************************/
		/* insert dans conges_periode     */
		/**********************************/
		$new_etat="ok";
		$result=insert_dans_periode($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type_id, $new_etat, $mysql_link);
		
		/************************************************/
		/* UPDATE table "conges_solde_user" (jours restants) */
		// on retranche les jours seulement pour des conges pris (pas pour les absences)
		// donc seulement si le type de l'absence qu'on annule est un "conges"
		if($tab_tout_type_abs[$new_type_id]['type']=="conges")
		{
			$user_nb_jours_pris_float=(float) $new_nb_jours ;
			$sql2 = "UPDATE conges_solde_user SET su_solde = su_solde-$user_nb_jours_pris_float WHERE su_login='$user_login' AND su_abs_id=$new_type_id " ;
			//echo($sql2."<br>");
			$ReqLog2 = requete_mysql($sql2, $mysql_link, "new_conges", $DEBUG);
		}
		

		if($result==TRUE)
			echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		else
			echo $_SESSION['lang']['form_modif_not_ok']."<br><br> \n";
	}
	else
	{
			echo $_SESSION['lang']['resp_traite_user_valeurs_not_ok']."<br><br> \n";
	}

	/* APPEL D'UNE AUTRE PAGE */
	echo "<form action=\"$PHP_SELF?session=$session&user_login=$user_login\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_modif_retour']."\">\n";
	echo "</form> \n";
	
}


?>

