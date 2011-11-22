<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Congés
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique Générale GNU publiée par la Free Software Foundation.
Ce programme est distribué car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but spécifique. Reportez-vous à la Licence Publique Générale GNU pour plus de détails.
Vous devez avoir reçu une copie de la Licence Publique Générale GNU en même temps 
que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, États-Unis.
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
if($config_verif_droits==1){ include("../INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background="../img/watback.jpg">
<CENTER>
<?php
/** MAIN **/
	//echo "<br>$user_login";   /* envoyé par le formulaire précédent */

	//connexion mysql
	$link = connexion_mysql() ;
	
	// si une annulation de conges a été selectionée :
	if(isset($tab_checkbox_annule)) {
		annule_conges($user_login);
	}
	// si le traitement des demandes a été selectionée :
	elseif(isset($tab_radio_traite_demande)) {
		traite_demandes($user_login);
	}
	// si un nouveau conges a été saisi pour un user :
	elseif($new_demande_conges==1) {
		new_conges($user_login);
	}
	else {
		affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);
	}
	
	mysql_close($link);
	
	
function affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin) {
	global $PHP_SELF ;
	global $link;
	global $session, $config_user_saisie_demande ;

	/********************/
	/* Bilan des Conges */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_quotite FROM conges_users WHERE u_login = '$user_login' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	// TITRE
	$resultat1 = mysql_fetch_array($ReqLog1) ;
	printf("<H3>Traitement de :</H3><H2> %s %s.</H2>\n\n", $resultat1["u_prenom"], $resultat1["u_nom"]);
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");

	// AFFICHAGE TABLEAU
	printf("<h3>Bilan :</h3>\n");
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"300\">\n");
	printf("<tr align=\"center\"><td>Quotité</td><td>NB_JOURS_AN</td><td>SOLDE</td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
			printf("<tr align=\"center\">\n");
			printf("<td>%d%%</td><td>%s</td><td>%s</tr>\n", $resultat1["u_quotite"], affiche_decimal($resultat1["u_nb_jours_an"]), affiche_decimal($resultat1["u_nb_jours_reste"]));
			printf("</tr>\n");
		}
	printf("</table>\n");
	printf("<br><br>\n");

	/*************************/
	/* SAISIE NOUVEAU CONGES */
	/*************************/
	// dans le cas ou les users ne peuvent pas saisir de demande, le responsable saisi les congès :
	if($config_user_saisie_demande==0) {
	
		if(!isset($year_calendrier_saisie_debut))
			$year_calendrier_saisie_debut=date("Y");
		if(!isset($mois_calendrier_saisie_debut))
			$mois_calendrier_saisie_debut=date("m");
		if(!isset($year_calendrier_saisie_fin))
			$year_calendrier_saisie_fin=date("Y");
		if(!isset($mois_calendrier_saisie_fin))
			$mois_calendrier_saisie_fin=date("m");	
		//echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n";
	
		printf("<H3>Nouveau Congès :</H3>\n\n");
		//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
		saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);

		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	}

	/*******************/
	/* Etat des Demandes */
	/*******************/
	if($config_user_saisie_demande==1) {
		// Récupération des informations
		$sql2 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num FROM conges_periode WHERE p_login = '$user_login' ANd p_etat ='demande' ORDER BY p_date_deb";

		// AFFICHAGE TABLEAU
		printf("<h3>Etat des demandes :</h3>\n");
		printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");

		printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
		printf("<tr align=\"center\"><td>Debut</td><td>Fin</td><td>NB_Jours_Pris</td><td>Commentaire</td><td>Etat</td><td>Accepter</td><td>Refuser</td></tr>\n");
		$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		$tab_checkbox=array();
		while ($resultat2 = mysql_fetch_array($ReqLog2)) {
				$casecocher1=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[%s]\" value=\"%s-%s-ACCEPTE\">", $resultat2["p_num"], $resultat2["p_login"], affiche_decimal($resultat2["p_nb_jours"]) );
				$casecocher2=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[%s]\" value=\"%s-%s-REFUSE\">", $resultat2["p_num"], $resultat2["p_login"], affiche_decimal($resultat2["p_nb_jours"]) );
				printf("<tr align=\"center\">\n");
				printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
						$resultat2["p_date_deb"], $resultat2["p_date_fin"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_commentaire"], $resultat2["p_etat"], $casecocher1, $casecocher2);
				printf("</tr>\n");
			}
		printf("</table>\n\n");

		printf("<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n");
		printf("<br><input type=\"submit\" value=\"Valider\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"Cancel\">\n");
		printf(" </form> \n");
		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	}
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	// Récupération des informations
	$sql2 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num FROM conges_periode WHERE p_login = '$user_login' ANd p_etat !='demande'  ORDER BY p_date_deb";

	// AFFICHAGE TABLEAU
	printf("<h3>Etat des congès :</h3>\n");
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");

	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td>Debut</td><td>Fin</td><td>NB_Jours_Pris</td><td>Commentaire</td><td>Etat</td><td>Annuler</td></tr>\n");
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	$tab_checkbox=array();
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
			if(strcmp($resultat2[5], "pris")==0) {
				$casecocher1=sprintf("<input type=\"checkbox\" name=\"tab_checkbox_annule[%s]\" value=\"%s-%s-ANNULE\">", $resultat2["p_num"], $resultat2["p_login"], affiche_decimal($resultat2["p_nb_jours"]) );
			}
			else {
				$casecocher1="";
			}
			printf("<tr align=\"center\">\n");
			printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
					$resultat2["p_date_deb"], $resultat2["p_date_fin"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_commentaire"], $resultat2["p_etat"], $casecocher1);
			printf("</tr>\n");
		}
	printf("</table>\n\n");

	printf("<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n");
	printf("<br><input type=\"submit\" value=\"Valider les Annulations\">\n");
	printf(" </form> \n");
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");

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
		echo("<br><br>conges numero :".$numero." --- User_login : ".$user_login." --- nb de jours : ".$user_nb_jours_pris_float." ---> ANNULER <br>");

		/* UPDATE table "conges_periode" */
		$sql1 = "UPDATE conges_periode SET p_etat=\"annulé\" WHERE p_num=$numero_int" ;
		//echo($sql1."<br>");
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		/* UPDATE table "conges_users" (jours restants) */
		$sql2 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste+$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
		//echo($sql2."<br>");
		$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
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
		$value_traite=$champs[2];
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		echo("<br><br>conges numero :".$numero." --- User_login : ".$user_login." --- nb de jours : ".$user_nb_jours_pris." --->".$value_traite."<br>");

		if(strcmp($value_traite, "ACCEPTE")==0) {
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"pris\" WHERE p_num=$numero_int" ;
			//echo($sql1."<br>");
			$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

			/* UPDATE table "conges_users" (jours restants) */
			$sql2 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		}
		else {
			if(strcmp($value_traite, "REFUSE")==0) {
				/* UPDATE table "conges_periode" */
				$sql1 = "UPDATE conges_periode SET p_etat=\"refusé\" WHERE p_num=$numero_int" ;
				//echo($sql1."<br>");
				$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
			}
		}
	}

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2 secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";

}

function new_conges($user_login) {
	global $PHP_SELF;
	global $session, $session_username;
	global $link ;
	global $new_debut, $new_fin, $new_nb_jours, $new_comment ;
	
	// verif validité des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_fin, &$new_nb_jours, $new_comment);
	
	if($valid==TRUE)
	{
//		$link = connexion_mysql() ;

		/**********************************/
		/* calcul num conges (num demande)*/
		/**********************************/
		// Récupération du + grand p_num (+ grand numero identifiant de conges)
		$sql1 = "SELECT max(p_num) FROM conges_periode" ;
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
		$num_new_demande = mysql_result($ReqLog1, p_num)+1;

		echo($user_login."---".$new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."---".$num_new_demande."<br>");

		/**********************************/
		/* insert dans conges_periode     */
		/**********************************/
		$etat="pris" ;
		$sql1 = "INSERT into conges_periode (p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num) 
					VALUES ('$user_login','$new_debut','$new_fin','$new_nb_jours','$new_comment','$etat','$num_new_demande')" ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		/************************************************/
		/* UPDATE table "conges_users" (jours restants) */
		$user_nb_jours_pris_float=(float) $new_nb_jours ;
		$sql2 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
		//echo($sql2."<br>");
		$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

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
