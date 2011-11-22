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

//session_start();
include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php 
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	echo "<body text=#000000 bgcolor=$config_bgcolor link=#000080 vlink=#800080 alink=#FF0000 background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";
	echo "<CENTER>\n";

	/*** initialisation des variables ***/
	$new_demande_conges=0;
	$year_calendrier_saisie_debut=0;
	$mois_calendrier_saisie_debut=0;
	$year_calendrier_saisie_fin=0;
	$mois_calendrier_saisie_fin=0;
	$tri_date="ascendant";
	/************************************/
	
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['user_login'])) { $user_login=$_GET['user_login']; }
	if(isset($_GET['year_calendrier_saisie_debut'])) { $year_calendrier_saisie_debut=$_GET['year_calendrier_saisie_debut']; }
	if(isset($_GET['mois_calendrier_saisie_debut'])) { $mois_calendrier_saisie_debut=$_GET['mois_calendrier_saisie_debut']; }
	if(isset($_GET['year_calendrier_saisie_fin'])) { $year_calendrier_saisie_fin=$_GET['year_calendrier_saisie_fin']; }
	if(isset($_GET['mois_calendrier_saisie_fin'])) { $mois_calendrier_saisie_fin=$_GET['mois_calendrier_saisie_fin']; }
	if(isset($_GET['tri_date'])) { $tri_date=$_GET['tri_date']; }
	// POST
	if(!isset($user_login))
		if(isset($_POST['user_login'])) { $user_login=$_POST['user_login']; }
	if(isset($_POST['tab_checkbox_annule'])) { $tab_checkbox_annule=$_POST['tab_checkbox_annule']; }
	if(isset($_POST['tab_radio_traite_demande'])) { $tab_radio_traite_demande=$_POST['tab_radio_traite_demande']; }
	if(isset($_POST['new_demande_conges'])) { $new_demande_conges=$_POST['new_demande_conges']; }
	if(isset($_POST['new_debut'])) { $new_debut=$_POST['new_debut']; }
	if(isset($_POST['new_demi_jour_deb'])) { $new_demi_jour_deb=$_POST['new_demi_jour_deb']; }
	if(isset($_POST['new_fin'])) { $new_fin=$_POST['new_fin']; }
	if(isset($_POST['new_demi_jour_fin'])) { $new_demi_jour_fin=$_POST['new_demi_jour_fin']; }
	if(isset($_POST['new_nb_jours'])) { $new_nb_jours=$_POST['new_nb_jours']; }
	if(isset($_POST['new_comment'])) { $new_comment=$_POST['new_comment']; }
	if(isset($_POST['new_etat'])) { $new_etat=$_POST['new_etat']; }
	/*************************************/
	
	//echo "<br>$user_login";   /* envoy� par le formulaire pr�c�dent */

	//connexion mysql
	$link = connexion_mysql() ;
	
	// si une annulation de conges a �t� selection�e :
	if(isset($tab_checkbox_annule)) {
		annule_conges($user_login);
	}
	// si le traitement des demandes a �t� selection�e :
	elseif(isset($tab_radio_traite_demande)) {
		traite_demandes($user_login);
	}
	// si un nouveau conges ou absence a �t� saisi pour un user :
	elseif($new_demande_conges==1) {
		new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_etat);
	}
	else {
		affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date);
	}
	
	mysql_close($link);
	
	
function affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date) {
	global $PHP_SELF ;
	global $link;
	global $session, $config_user_saisie_demande, $config_resp_saisie_mission;
	global $config_rtt_comme_conges, $config_auth, $config_verif_droits ;

	// affichage "deconnexion" et "actualiser page":
	echo "</center>\n";
	echo "<table><tr>\n";
	if(($config_auth==TRUE)&&($config_verif_droits!=TRUE))
	{
		echo "<td valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
		echo "<td valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	echo "<td valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&user_login=$user_login\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Actualiser la Page\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td></tr></table>\n";
	echo "<center>\n";
	
	/********************/
	/* Bilan des Conges */
	/********************/
	// R�cup�ration des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users WHERE u_login = '$user_login' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	// TITRE
	$resultat1 = mysql_fetch_array($ReqLog1) ;
	printf("<H3>Traitement de :</H3><H2> %s %s.</H2>\n\n", $resultat1["u_prenom"], $resultat1["u_nom"]);
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");

	// AFFICHAGE TABLEAU
	printf("<h3>Bilan :</h3>\n");
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"300\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">Quotit�</td><td class=\"titre\">nb jours/an</td><td class=\"titre\">SOLDE cong�s</td>");
	if($config_rtt_comme_conges==TRUE)
		printf("<td class=\"titre\">nb rtt/an</td><td class=\"titre\">SOLDE rtt</td>");
	printf("</tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		printf("<tr align=\"center\">\n");
		if($config_rtt_comme_conges==TRUE)
			printf("<td class=\"histo\">%d%%</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td></tr>\n", $resultat1["u_quotite"], affiche_decimal($resultat1["u_nb_jours_an"]), affiche_decimal($resultat1["u_solde_jours"]), affiche_decimal($resultat1["u_nb_rtt_an"]), affiche_decimal($resultat1["u_solde_rtt"]));
		else
			printf("<td class=\"histo\">%d%%</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td></tr>\n", $resultat1["u_quotite"], affiche_decimal($resultat1["u_nb_jours_an"]), affiche_decimal($resultat1["u_solde_jours"]));
		printf("</tr>\n");
	}
	printf("</table>\n");
	printf("<br><br>\n");

	/*************************/
	/* SAISIE NOUVEAU CONGES */
	/*************************/
	// dans le cas ou les users ne peuvent pas saisir de demande, le responsable saisi les cong�s :
	if(($config_user_saisie_demande==FALSE)||($config_resp_saisie_mission==TRUE)) {
	
		// si les mois et ann�e ne sont pas renseign�s, on prend ceux du jour
		if( (!isset($year_calendrier_saisie_debut)) || ($year_calendrier_saisie_debut==0) )
			$year_calendrier_saisie_debut=date("Y");
		if( (!isset($mois_calendrier_saisie_debut)) || ($mois_calendrier_saisie_debut==0) )
			$mois_calendrier_saisie_debut=date("m");
		if( (!isset($year_calendrier_saisie_fin)) || ($year_calendrier_saisie_fin==0) )
			$year_calendrier_saisie_fin=date("Y");
		if( (!isset($mois_calendrier_saisie_fin)) || ($mois_calendrier_saisie_fin==0) )
			$mois_calendrier_saisie_fin=date("m");	
		//echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n";
	
		printf("<H3>Nouveau Cong�s/Absence :</H3>\n\n");
		//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
		saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);

		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	}

	/*******************/
	/* Etat des Demandes */
	/*******************/
	if($config_user_saisie_demande==TRUE) {
		// R�cup�ration des informations
		$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_etat, p_num FROM conges_periode WHERE p_login = '$user_login' ANd ( p_etat ='demande' OR p_etat ='demande_rtt' ) ORDER BY p_date_deb";
		$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		
		printf("<h3>Etat des demandes :</h3>\n");

		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			echo "<b>Aucune demande de cong�s pour cette personne dans la base de donn�es ...</b><br><br>\n";		
		}
		else
		{
			// AFFICHAGE TABLEAU
			printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
			printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
			printf("<tr align=\"center\"><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td class=\"titre\">Etat</td><td class=\"titre\">Accepter</td><td class=\"titre\">Refuser</td></tr>\n");
			$tab_checkbox=array();
			while ($resultat2 = mysql_fetch_array($ReqLog2)) {
				$sql_login=$resultat2["p_login"] ;
				$sql_date_deb=eng_date_to_fr($resultat2["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
				$sql_date_fin=eng_date_to_fr($resultat2["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
				$sql_nb_jours=affiche_decimal($resultat2["p_nb_jours"]) ;
				$sql_commentaire=$resultat2["p_commentaire"] ;
				$sql_etat=$resultat2["p_etat"] ;
				$sql_num=$resultat2["p_num"] ;
				
//				$casecocher1=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[%s]\" value=\"%s-%s-%s-ACCEPTE\">", $resultat2["p_num"], $resultat2["p_login"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_etat"] );
				$casecocher1=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$sql_login-$sql_nb_jours-$sql_etat-ACCEPTE\">");
//				$casecocher2=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[%s]\" value=\"%s-%s-%s-REFUSE\">", $resultat2["p_num"], $resultat2["p_login"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_etat"] );
				$casecocher2=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$sql_login-$sql_nb_jours-$sql_etat-REFUSE\">");
				printf("<tr align=\"center\">\n");
//				printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", 
//						$resultat2["p_date_deb"], $resultat2["p_date_fin"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_commentaire"], $resultat2["p_etat"], $casecocher1, $casecocher2);
				printf("<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_date_fin _ $demi_j_fin</td><td class=\"histo\">$sql_nb_jours</td><td class=\"histo\">$sql_commentaire</td><td class=\"histo\">$sql_etat</td><td class=\"histo\">$casecocher1</td><td class=\"histo\">$casecocher2</td>\n");
				printf("</tr>\n");
			}
			printf("</table>\n\n");

			printf("<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n");
			printf("<br><input type=\"submit\" value=\"Valider\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"Cancel\">\n");
			printf(" </form> \n");
		}
		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	}
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	// R�cup�ration des informations
	$sql3 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_etat, p_num FROM conges_periode WHERE p_login = '$user_login' AND ( p_etat !='demande' OR p_etat !='demande_rtt' ) ";
	if($tri_date=="descendant")
		$sql3=$sql3." ORDER BY p_date_deb DESC ";
	else
		$sql3=$sql3." ORDER BY p_date_deb ASC ";
	$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());

	printf("<h3>Etat des cong�s :</h3>\n");
	
	$count3=mysql_num_rows($ReqLog3);
	if($count3==0)
	{
		echo "<b>Aucun cong�s pour cette personne dans la base de donn�es ...</b><br><br>\n";		
	}
	else
	{
		// AFFICHAGE TABLEAU
		printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
		printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
		echo "<tr align=\"center\">\n";
		echo " <td class=\"titre\">
				<a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
				 Debut 
				<a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
			  </td>\n";
		echo " <td class=\"titre\">Fin</td>\n";
		echo " <td class=\"titre\">nb Jours Pris</td>\n";
		echo " <td class=\"titre\">Commentaire</td>\n";
		echo " <td class=\"titre\">Etat</td>\n";
		echo " <td class=\"titre\">Annuler</td>\n";
		echo "</tr>\n";
		$tab_checkbox=array();
		while ($resultat3 = mysql_fetch_array($ReqLog3)) {
				$sql_login=$resultat3["p_login"] ;
				$sql_date_deb=eng_date_to_fr($resultat3["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat3["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
				$sql_date_fin=eng_date_to_fr($resultat3["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat3["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
				$sql_nb_jours=affiche_decimal($resultat3["p_nb_jours"]) ;
				$sql_commentaire=$resultat3["p_commentaire"] ;
				$sql_etat=$resultat3["p_etat"] ;
				$sql_num=$resultat3["p_num"] ;
				
				if( (strstr($sql_etat, "annul�")==FALSE) && (strstr($sql_etat, "refus�")==FALSE) ) {
//					$casecocher1=sprintf("<input type=\"checkbox\" name=\"tab_checkbox_annule[%s]\" value=\"%s-%s-%s-ANNULE\">", $resultat3["p_num"], $resultat3["p_login"], affiche_decimal($resultat3["p_nb_jours"]), $resultat3["p_etat"] );
					$casecocher1=sprintf("<input type=\"checkbox\" name=\"tab_checkbox_annule[$sql_num]\" value=\"$sql_login-$sql_nb_jours-$sql_etat-ANNULE\">");
				}
				else {
					$casecocher1="";
				}
				printf("<tr align=\"center\">\n");
//				printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", 
//						$resultat3["p_date_deb"], $resultat3["p_date_fin"], affiche_decimal($resultat3["p_nb_jours"]), $resultat3["p_commentaire"], $resultat3["p_etat"], $casecocher1);
				printf("<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_date_fin _ $demi_j_fin</td><td class=\"histo\">$sql_nb_jours</td><td class=\"histo\">$sql_commentaire</td><td class=\"histo\">$sql_etat</td><td class=\"histo\">$casecocher1</td>\n");
				printf("</tr>\n");
			}
		printf("</table>\n\n");

		printf("<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n");
		printf("<br><input type=\"submit\" value=\"Valider les Annulations\">\n");
		printf(" </form> \n");
	}
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	
	// affichage "deconnexion" et "actualiser page":
	echo "</center>\n";
	echo "<table><tr>\n";
	if(($config_auth==TRUE)&&($config_verif_droits!=TRUE))
	{
		echo "<td valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
		echo "<td valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	echo "<td valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&user_login=$user_login\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Actualiser la Page\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td></tr></table>\n";
	echo "<center>\n";

}

function annule_conges($user_login) {
	global $PHP_SELF ;
	global $link;
	global $session, $tab_checkbox_annule ;
	
	while($elem_tableau = each($tab_checkbox_annule))
	{
		$champs = explode("-", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$user_nb_jours_pris_float=(float) $user_nb_jours_pris ;
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		$user_etat=$champs[2];
		echo("<br><br>conges numero :".$numero." ---> login : ".$user_login." --- nb de jours : ".$user_nb_jours_pris_float." --- etat : ".$user_etat." ---> ANNULER <br>");

		/* UPDATE table "conges_periode" */
		if($user_etat=="pris")
			$sql1 = "UPDATE conges_periode SET p_etat=\"annul�\" WHERE p_num=$numero_int" ;
		elseif($user_etat=="rtt_prise")
			$sql1 = "UPDATE conges_periode SET p_etat=\"rtt_annul�e\" WHERE p_num=$numero_int" ;
		else
			$sql1 = "UPDATE conges_periode SET p_etat=\"absence-annul�e\" WHERE p_num=$numero_int" ;
		
		//echo($sql1."<br>");
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		/* UPDATE table "conges_users" (jours restants) */
		if($user_etat=="pris") // on re-cr�dite les jours seulement pour des conges pris (pas pour les absences autres)
		{
			$sql2 = "UPDATE conges_users SET u_solde_jours=u_solde_jours+$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
		}
		elseif($user_etat=="rtt_prise")
		{
			$sql2 = "UPDATE conges_users SET u_solde_rtt=u_solde_rtt+$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
		}
	}

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2 secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";

}

function traite_demandes($user_login) {
	global $PHP_SELF ;
	global $tab_radio_traite_demande ;
	global $session, $link;

	while($elem_tableau = each($tab_radio_traite_demande))
	{
		$champs = explode("-", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$user_nb_jours_pris_float=(float) $user_nb_jours_pris ;
		$value_etat=$champs[2];
		$value_traite=$champs[3];
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		echo("<br><br>conges numero :".$numero." --- User_login : ".$user_login." --- nb de jours : ".$user_nb_jours_pris." --->".$value_traite."<br>");

		if(strcmp($value_traite, "ACCEPTE")==0) {
			/* UPDATE table "conges_periode" */
			if($value_etat=="demande")
				$sql1 = "UPDATE conges_periode SET p_etat=\"pris\" WHERE p_num=$numero_int" ;
			else  // alors $value_etat=="demande_rtt"
				$sql1 = "UPDATE conges_periode SET p_etat=\"rtt_prise\" WHERE p_num=$numero_int" ;
			//echo($sql1."<br>");
			$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

			/* UPDATE table "conges_users" (jours restants) */
			if($value_etat=="demande")
				$sql2 = "UPDATE conges_users SET u_solde_jours=u_solde_jours-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			else  // alors $value_etat=="demande_rtt"
				$sql2 = "UPDATE conges_users SET u_solde_rtt=u_solde_rtt-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		}
		else {
			if(strcmp($value_traite, "REFUSE")==0) {
				/* UPDATE table "conges_periode" */
				if($value_etat=="demande")
					$sql1 = "UPDATE conges_periode SET p_etat=\"refus�\" WHERE p_num=$numero_int" ;
			else  // alors $value_etat=="demande_rtt"
					$sql1 = "UPDATE conges_periode SET p_etat=\"rtt_refus�e\" WHERE p_num=$numero_int" ;
				//echo($sql1."<br>");
				$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
			}
		}
	}

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2 secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";

}

function new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_etat) {
	global $PHP_SELF;
	global $session, $session_username;
	global $link ;
//	global $new_debut, $new_fin, $new_nb_jours, $new_comment, $new_etat ;
	
	// verif validit� des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment);
	
	if($valid==TRUE)
	{
//		$link = connexion_mysql() ;

		/**********************************/
		/* calcul num conges (num demande)*/
		/**********************************/
		// R�cup�ration du + grand p_num (+ grand numero identifiant de conges)
		$sql1 = "SELECT max(p_num) FROM conges_periode" ;
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
		$num_new_demande = mysql_result($ReqLog1, p_num)+1;

		echo "$user_login---$new_debut _ $new_demi_jour_deb---$new_fin _ $new_demi_jour_fin---$new_nb_jours---$new_comment---$new_etat---$num_new_demande<br>\n";

		/**********************************/
		/* insert dans conges_periode     */
		/**********************************/
		if($new_etat=="conges")
			$new_etat="pris";
		elseif($new_etat=="rtt")
			$new_etat="rtt_prise";
		$sql1 = "INSERT into conges_periode (p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_etat, p_num) 
					VALUES ('$user_login','$new_debut','$new_demi_jour_deb','$new_fin','$new_demi_jour_fin','$new_nb_jours','$new_comment','$new_etat','$num_new_demande')" ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		/************************************************/
		/* UPDATE table "conges_users" (jours restants) */
		if($new_etat=="pris")
		{
			$user_nb_jours_pris_float=(float) $new_nb_jours ;
			$sql2 = "UPDATE conges_users SET u_solde_jours=u_solde_jours-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		}
		elseif($new_etat=="rtt_prise")
		{
			$user_nb_jours_pris_float=(float) $new_nb_jours ;
			$sql2 = "UPDATE conges_users SET u_solde_rtt=u_solde_rtt-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		}
		
	//	mysql_close($link);

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	}
	else
	{
			printf(" ERREUR ! Les valeurs saisies sont invalides ou manquantes  !!!<br><br> \n");
	}

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"$PHP_SELF?session=$session&user_login=$user_login\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");
	
}


?>

</CENTER>
</body>
</html>
