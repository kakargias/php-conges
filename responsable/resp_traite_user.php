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
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];
	// GET
	$user_login=$HTTP_GET_VARS['user_login'];
	$year_calendrier_saisie_debut=$HTTP_GET_VARS['year_calendrier_saisie_debut'];
	$mois_calendrier_saisie_debut=$HTTP_GET_VARS['mois_calendrier_saisie_debut'];
	$year_calendrier_saisie_fin=$HTTP_GET_VARS['year_calendrier_saisie_fin'];
	$mois_calendrier_saisie_fin=$HTTP_GET_VARS['mois_calendrier_saisie_fin'];
	// POST
	if(!isset($user_login))
		$user_login=$HTTP_POST_VARS['user_login'];
	$tab_checkbox_annule=$HTTP_POST_VARS['tab_checkbox_annule'];
	$tab_radio_traite_demande=$HTTP_POST_VARS['tab_radio_traite_demande'];
	$new_demande_conges=$HTTP_POST_VARS['new_demande_conges'];
	$new_debut=$HTTP_POST_VARS['new_debut'];
	$new_fin=$HTTP_POST_VARS['new_fin'];
	$new_nb_jours=$HTTP_POST_VARS['new_nb_jours'];
	$new_comment=$HTTP_POST_VARS['new_comment'];
	$new_etat=$HTTP_POST_VARS['new_etat'];
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
		new_conges($user_login);
	}
	else {
		affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);
	}
	
	mysql_close($link);
	
	
function affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin) {
	global $PHP_SELF ;
	global $link;
	global $session, $config_user_saisie_demande, $config_resp_saisie_mission, $config_auth, $config_verif_droits ;

	// affichage "deconnexion" et "actualiser page":
	echo "</center>\n";
	echo "<table><tr>\n";
	if(($config_auth==1)&&($config_verif_droits!=1))
	{
		echo "<td valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
		echo "<td valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	echo "<td valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&user_login=$user_login\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td></tr></table>\n";
	echo "<center>\n";
	
	/********************/
	/* Bilan des Conges */
	/********************/
	// R�cup�ration des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_quotite FROM conges_users WHERE u_login = '$user_login' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	// TITRE
	$resultat1 = mysql_fetch_array($ReqLog1) ;
	printf("<H3>Traitement de :</H3><H2> %s %s.</H2>\n\n", $resultat1["u_prenom"], $resultat1["u_nom"]);
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");

	// AFFICHAGE TABLEAU
	printf("<h3>Bilan :</h3>\n");
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"300\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">Quotit�</td><td class=\"titre\">nb jours/an</td><td class=\"titre\">SOLDE</td></tr>\n");
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
	// dans le cas ou les users ne peuvent pas saisir de demande, le responsable saisi les cong�s :
	if(($config_user_saisie_demande==0)||($config_resp_saisie_mission==1)) {
	
		if(!isset($year_calendrier_saisie_debut))
			$year_calendrier_saisie_debut=date("Y");
		if(!isset($mois_calendrier_saisie_debut))
			$mois_calendrier_saisie_debut=date("m");
		if(!isset($year_calendrier_saisie_fin))
			$year_calendrier_saisie_fin=date("Y");
		if(!isset($mois_calendrier_saisie_fin))
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
	if($config_user_saisie_demande==1) {
		// R�cup�ration des informations
		$sql2 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num FROM conges_periode WHERE p_login = '$user_login' ANd p_etat ='demande' ORDER BY p_date_deb";
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
			printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
			printf("<tr align=\"center\"><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td class=\"titre\">Etat</td><td class=\"titre\">Accepter</td><td class=\"titre\">Refuser</td></tr>\n");
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
		}
		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	}
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	// R�cup�ration des informations
	$sql3 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num FROM conges_periode WHERE p_login = '$user_login' ANd p_etat !='demande'  ORDER BY p_date_deb";
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
		printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
		printf("<tr align=\"center\"><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td class=\"titre\">Etat</td><td class=\"titre\">Annuler</td></tr>\n");
		$tab_checkbox=array();
		while ($resultat3 = mysql_fetch_array($ReqLog3)) {
				$etat=$resultat3[5];
				if(($etat!="annul�")&&($etat!="absence-annul�e")) {
					$casecocher1=sprintf("<input type=\"checkbox\" name=\"tab_checkbox_annule[%s]\" value=\"%s-%s-%s-ANNULE\">", $resultat3["p_num"], $resultat3["p_login"], affiche_decimal($resultat3["p_nb_jours"]), $resultat3["p_etat"] );
				}
				else {
					$casecocher1="";
				}
				printf("<tr align=\"center\">\n");
				printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
						$resultat3["p_date_deb"], $resultat3["p_date_fin"], affiche_decimal($resultat3["p_nb_jours"]), $resultat3["p_commentaire"], $resultat3["p_etat"], $casecocher1);
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
	if(($config_auth==1)&&($config_verif_droits!=1))
	{
		echo "<td valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
		echo "<td valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	echo "<td valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&user_login=$user_login\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
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
		else
			$sql1 = "UPDATE conges_periode SET p_etat=\"absence-annul�e\" WHERE p_num=$numero_int" ;
		
		//echo($sql1."<br>");
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		/* UPDATE table "conges_users" (jours restants) */
		if($user_etat=="pris") // on recr�dite les jours seulement pour des conges pris (pas pour les absences autres)
		{
			$sql2 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste+$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
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
				$sql1 = "UPDATE conges_periode SET p_etat=\"refus�\" WHERE p_num=$numero_int" ;
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
	global $new_debut, $new_fin, $new_nb_jours, $new_comment, $new_etat ;
	
	// verif validit� des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_fin, &$new_nb_jours, $new_comment);
	
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

		echo($user_login."---".$new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."---".$num_new_demande."<br>");

		/**********************************/
		/* insert dans conges_periode     */
		/**********************************/
		if($new_etat=="conges") $new_etat="pris";
		$sql1 = "INSERT into conges_periode (p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num) 
					VALUES ('$user_login','$new_debut','$new_fin','$new_nb_jours','$new_comment','$new_etat','$num_new_demande')" ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		/************************************************/
		/* UPDATE table "conges_users" (jours restants) */
		if($new_etat=="pris")
		{
			$user_nb_jours_pris_float=(float) $new_nb_jours ;
			$sql2 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste-$user_nb_jours_pris_float WHERE u_login='$user_login' " ;
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
