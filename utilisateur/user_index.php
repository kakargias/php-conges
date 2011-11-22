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
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
if($config_where_to_find_user_email=="ldap"){ include("../config_ldap.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php

	/*** initialisation des variables ***/
	$year_calendrier_saisie_debut=0;
	$mois_calendrier_saisie_debut=0;
	$year_calendrier_saisie_fin=0;
	$mois_calendrier_saisie_fin=0;
	$onglet="";
	$new_demande_conges=0;
	$new_echange_rtt=0;
	$change_passwd=0;
	$tri_date="ascendant";
	/************************************/

	echo "<TITLE> CONGES : Utilisateur $session_username</TITLE>\n";
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET

	if(isset($_GET['year_calendrier_saisie_debut'])) { $year_calendrier_saisie_debut=$_GET['year_calendrier_saisie_debut']; }
	if(isset($_GET['mois_calendrier_saisie_debut'])) { $mois_calendrier_saisie_debut=$_GET['mois_calendrier_saisie_debut']; }
	if(isset($_GET['year_calendrier_saisie_fin'])) { $year_calendrier_saisie_fin=$_GET['year_calendrier_saisie_fin']; }
	if(isset($_GET['mois_calendrier_saisie_fin'])) { $mois_calendrier_saisie_fin=$_GET['mois_calendrier_saisie_fin']; }
	if(isset($_GET['onglet'])) { $onglet=$_GET['onglet']; }
	if(isset($_GET['tri_date'])) { $tri_date=$_GET['tri_date']; }
	// POST
	if(isset($_POST['new_demande_conges'])) { $new_demande_conges=$_POST['new_demande_conges']; }
	if(isset($_POST['new_echange_rtt'])) { $new_echange_rtt=$_POST['new_echange_rtt']; }
	if(isset($_POST['new_debut'])) { $new_debut=$_POST['new_debut']; }
	if(isset($_POST['new_demi_jour_deb'])) { $new_demi_jour_deb=$_POST['new_demi_jour_deb']; }
	if(isset($_POST['new_fin'])) { $new_fin=$_POST['new_fin']; }
	if(isset($_POST['new_demi_jour_fin'])) { $new_demi_jour_fin=$_POST['new_demi_jour_fin']; }
	if(isset($_POST['new_nb_jours'])) { $new_nb_jours=$_POST['new_nb_jours']; }
	if(isset($_POST['new_comment'])) { $new_comment=$_POST['new_comment']; }
	if(isset($_POST['new_etat'])) { $new_etat=$_POST['new_etat']; }
	if(isset($_POST['moment_absence_ordinaire'])) { $moment_absence_ordinaire=$_POST['moment_absence_ordinaire']; }
	if(isset($_POST['moment_absence_souhaitee'])) { $moment_absence_souhaitee=$_POST['moment_absence_souhaitee']; }
	if(isset($_POST['change_passwd'])) { $change_passwd=$_POST['change_passwd']; }
	if(isset($_POST['new_passwd1'])) { $new_passwd1=$_POST['new_passwd1']; }
	if(isset($_POST['new_passwd2'])) { $new_passwd2=$_POST['new_passwd2']; }
	if( (!isset($onglet)) || ($onglet=="") )
		if(isset($_POST['onglet'])) { $onglet=$_POST['onglet']; }
	/*************************************/

	echo "<body text=\"#000000\" bgcolor=$config_bgcolor link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";

	// affichage "deconnexion" et "actualiser page" et "affichage calendrier" :
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>\n";
	if(($config_auth==TRUE)&&($config_verif_droits!=TRUE))
	{
		echo "<td width=\"100\" valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
		echo "<td width=\"25\" valign=\"middle\">\n";
		echo "<img src=\"../img/shim.gif\" width=\"20\" height=\"22\" border=\"0\">\n";
		echo "</td>\n";
	}
	echo "<td width=\"150\" valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&onglet=$onglet\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Actualiser la Page\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td>\n";
	echo "<td align=\"right\" valign=\"middle\">\n";
	if($config_user_affiche_calendrier==TRUE)
		echo "<a href=\"../calendrier.php?session=$session\"><img src=\"../img/rebuild.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Afficher le Calendrier\" alt=\"Afficher le Calendrier\"></a> Afficher le Calendrier\n";
	else
		echo "&nbsp;\n";
	echo "</td>\n";
	echo "</tr></table>\n";

	echo "<CENTER>\n";

	// si le user peut saisir ses demandes et qu'il vient d'en saisir une ...
	if(($new_demande_conges==1) && ($config_user_saisie_demande==TRUE)) {
		new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_etat);
	}
	elseif(($new_echange_rtt==1)&&($config_user_echange_rtt==TRUE)) {
		echange_absence_rtt($onglet, $new_debut, $new_fin, $new_comment, $moment_absence_ordinaire, $moment_absence_souhaitee);
	}
	elseif($change_passwd==1) {
		change_passwd();
	}
	else {
		if($onglet=="")
			$onglet="historique_conges";
		affichage($onglet, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date);
	}

function affichage($onglet, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date)
{
	global $PHP_SELF;
	global $session, $session_username ;
	global $config_auth, $config_user_saisie_demande, $config_user_saisie_mission;
	global $config_rtt_comme_conges, $config_user_echange_rtt, $config_user_ch_passwd , $config_verif_droits;
	global $link;

	// si les mois et année ne sont pas renseignés, on prend ceux du jour
	if( (!isset($year_calendrier_saisie_debut)) || ($year_calendrier_saisie_debut==0))
		$year_calendrier_saisie_debut=date("Y");
	if( (!isset($mois_calendrier_saisie_debut)) || ($mois_calendrier_saisie_debut==0) )
		$mois_calendrier_saisie_debut=date("m");
	if( (!isset($year_calendrier_saisie_fin)) || ($year_calendrier_saisie_fin==0) )
		$year_calendrier_saisie_fin=date("Y");
	if( (!isset($mois_calendrier_saisie_fin)) || ($mois_calendrier_saisie_fin==0) )
		$mois_calendrier_saisie_fin=date("m");
	//echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n";

	//connexion mysql
	$link = connexion_mysql();

	$sql1 = "SELECT u_nom, u_prenom FROM conges_users where u_login = '$session_username' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : user_index.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$NOM=$resultat1["u_nom"];
		$PRENOM=$resultat1["u_prenom"];
	}

	// TITRE
	printf("<H1>%s : %s %s</H1>\n\n", $session_username, $PRENOM, $NOM);

	/********************/
	/* Bilan des Conges */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users where u_login = '".$session_username."' ";
	// AFFICHAGE TABLEAU
	//printf("<h3>Bilan :</h3>\n");
	if($config_rtt_comme_conges==TRUE)
		$taille_tableau_bilan=500;
	else
		$taille_tableau_bilan=300;
//	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"$taille_tableau_bilan\">\n");
	printf("<table cellpadding=\"2\" width=\"$taille_tableau_bilan\" class=\"tablo\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">quotité</td><td class=\"titre\">NB CONGES / AN</td><td class=\"titre\">SOLDE CONGES</td>");
	if($config_rtt_comme_conges==TRUE)
		printf("<td class=\"titre\">NB RTT / AN</td><td class=\"titre\">SOLDE RTT</td>");
	printf("</tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
			printf("<tr align=\"center\">\n");
			if($config_rtt_comme_conges==TRUE)
			{
				printf("<td>%d%%</td><td><b>%s</b></td><td bgcolor=\"#FF9191\"><b>%s</b></td><td><b>%s</b></td><td bgcolor=\"#FF9191\"><b>%s</b></td></tr>\n",
						$resultat1["u_quotite"],  affiche_decimal($resultat1["u_nb_jours_an"]), affiche_decimal($resultat1["u_solde_jours"]),  affiche_decimal($resultat1["u_nb_rtt_an"]), affiche_decimal($resultat1["u_solde_rtt"]) );
			}
			else
			{
				printf("<td>%d%%</td><td><b>%s</b></td><td bgcolor=\"#FF9191\"><b>%s</b></td></tr>\n",
						$resultat1["u_quotite"],  affiche_decimal($resultat1["u_nb_jours_an"]), affiche_decimal($resultat1["u_solde_jours"]) );
			}
			printf("</tr>\n");
		}
	printf("</table>\n");
	printf("<br><br><br>\n");



	/*********************************/
	/*** AFFICHAGE DES ONGLETS...  ***/
	$nb_colonnes=2 ; // on affiche toujours au moins 2 onglets (histo conges et histo absences)
//	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\">\n" ;
	echo "</center>\n" ;
	echo "<table cellpadding=\"1\" cellspacing=\"2\" border=\"1\">\n" ;
	echo "<tr align=\"center\">\n";
		if(($config_user_saisie_demande==TRUE)||($config_user_saisie_mission==TRUE))
		{
			if($onglet!="nouvelle_absence")
				echo "<td class=\"onglet\" width=\"150\"><a href=\"$PHP_SELF?session=$session&onglet=nouvelle_absence\" class=\"bouton-onglet\"> Nouvelle Absence </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"150\"><a href=\"$PHP_SELF?session=$session&onglet=nouvelle_absence\" class=\"bouton-current-onglet\"> Nouvelle Absence </a></td>\n";
			$nb_colonnes=$nb_colonnes+1;
		}
		if($config_user_echange_rtt==TRUE)
		{
			if($onglet!="echange_jour_absence")
				echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=echange_jour_absence\" class=\"bouton-onglet\"> Echange jour absence </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=echange_jour_absence\" class=\"bouton-current-onglet\"> Echange jour absence </a></td>\n";
			$nb_colonnes=$nb_colonnes+1;
		}
		if($config_user_saisie_demande==TRUE)
		{
			if($onglet!="demandes_en_cours")
				echo "<td class=\"onglet\" width=\"150\"><a href=\"$PHP_SELF?session=$session&onglet=demandes_en_cours\" class=\"bouton-onglet\"> demandes en cours </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"150\"><a href=\"$PHP_SELF?session=$session&onglet=demandes_en_cours\" class=\"bouton-current-onglet\"> demandes en cours </a></td>\n";
			$nb_colonnes=$nb_colonnes+1;
		}
		if($config_rtt_comme_conges==TRUE)
			{$taille_onglet=200; $text_onglet_histo_conges=" Historique des congés/RTT ";}
		else
			{$taille_onglet=170; $text_onglet_histo_conges=" Historique des congés ";}
		if($onglet!="historique_conges")
			echo "<td class=\"onglet\" width=\"$taille_onglet\"><a href=\"$PHP_SELF?session=$session&onglet=historique_conges\" class=\"bouton-onglet\">$text_onglet_histo_conges</a></td>\n";
		else
			echo "<td class=\"current-onglet\" width=\"$taille_onglet\"><a href=\"$PHP_SELF?session=$session&onglet=historique_conges\" class=\"bouton-current-onglet\">$text_onglet_histo_conges</a></td>\n";

		if($onglet!="historique_autres_absences")
			echo "<td class=\"onglet\" width=\"200\"><a href=\"$PHP_SELF?session=$session&onglet=historique_autres_absences\" class=\"bouton-onglet\"> Historique autres absences </a></td>\n";
		else
			echo "<td class=\"current-onglet\" width=\"200\"><a href=\"$PHP_SELF?session=$session&onglet=historique_autres_absences\" class=\"bouton-current-onglet\"> Historique autres absences </a></td>\n";
		if(($config_auth==TRUE) && ($config_user_ch_passwd==TRUE))
		{
			if($onglet!="changer_mot_de_passe")
				echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=changer_mot_de_passe\" class=\"bouton-onglet\"> Changer mot de passe </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=changer_mot_de_passe\" class=\"bouton-current-onglet\"> Changer mot de passe </a></td>\n";
			$nb_colonnes=$nb_colonnes+1;
		}
	echo "</tr>\n";
	echo "</table>\n" ;

	echo "<center>\n" ;
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\">\n" ;
	/**************************************/
	/*** AFFICHAGE DE LA PAGE DEMANDéE  ***/
	echo "<tr align=\"center\">\n";

	/**************************/
	/* Nouvelle Demande */
	/**************************/
	//if(($config_user_saisie_demande==TRUE)||($config_user_saisie_mission==TRUE)) {
	if($onglet=="nouvelle_absence")
	{
		echo "<td colspan=$nb_colonnes>\n";
		printf("<H3>Nouvelle Absence :</H3>\n\n");

		//affiche le formulaire de saisie d'une nouvelle demande de conges
		saisie_nouveau_conges($session_username, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);

		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
		echo "</td>\n";
	}


	/**************************************/
	/* Echange absence rtt/jour travaillé */
	/**************************************/
	//if($config_user_echange_rtt==TRUE) {
	if($onglet=="echange_jour_absence")
	{
		echo "<td colspan=$nb_colonnes>\n";
		printf("<H3>Echange jour rtt,temps partiel / jour travaillé :</H3>\n\n");

		//affiche le formulaire de saisie d'une nouvelle demande de conges
		saisie_echange_rtt($session_username, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);

		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
		echo "</td>\n";
	}


	/**************************/
	/* Etat demandes en cours */
	/**************************/
	//if($config_user_saisie_demande==TRUE) {
	if($onglet=="demandes_en_cours")
	{
		echo "<td colspan=$nb_colonnes>\n";
		// Récupération des informations
		$sql3 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_num, p_etat FROM conges_periode
				WHERE p_login = '$session_username' AND (p_etat = \"demande\" OR p_etat = \"demande_rtt\") ";
		if($tri_date=="descendant")
			$sql3=$sql3." ORDER BY p_date_deb DESC ";
		else
			$sql3=$sql3." ORDER BY p_date_deb ASC ";
		$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());

		echo "<h3>Etat des demandes en cours :</h3>\n" ;

		$count3=mysql_num_rows($ReqLog3);
		if($count3==0)
		{
			echo "<b>Aucune demande en cours ...</b><br>\n";
		}
		else
		{
			// AFFICHAGE TABLEAU
			echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n" ;
			echo "<tr align=\"center\">\n";
			echo "<td class=\"titre\">
					<a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
					 Debut
					<a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
				</td>\n";
			echo "<td class=\"titre\">Fin</td>" ;
			if($config_rtt_comme_conges==TRUE)
				echo "<td class=\"titre\">type</td>" ;
			echo "<td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td></td><td></td></tr>\n" ;

			while ($resultat3 = mysql_fetch_array($ReqLog3)) {
					$sql_p_date_deb = eng_date_to_fr($resultat3["p_date_deb"]);
					$sql_p_demi_jour_deb = $resultat3["p_demi_jour_deb"];
					if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
					$sql_p_date_fin = eng_date_to_fr($resultat3["p_date_fin"]);
					$sql_p_demi_jour_fin = $resultat3["p_demi_jour_fin"];
					if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
					$sql_p_nb_jours = $resultat3["p_nb_jours"];
					$sql_p_commentaire = $resultat3["p_commentaire"];
					$sql_p_num = $resultat3["p_num"];
					$sql_p_etat = $resultat3["p_etat"];

					$user_modif_demande="<a href=\"user_modif_demande.php?session=$session&p_num=$sql_p_num&onglet=$onglet\">Modifier</a>" ;
					$user_suppr_demande="<a href=\"user_suppr_demande.php?session=$session&p_num=$sql_p_num&onglet=$onglet\">Supprimer</a>" ;
					echo "<tr align=\"center\">\n" ;
					echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td>" ;
					if($config_rtt_comme_conges==TRUE)
						echo "<td class=\"histo\">$sql_p_etat</td>" ;
					echo "<td class=\"histo\">".affiche_decimal($sql_p_nb_jours)."</td><td class=\"histo\">$sql_p_commentaire</td><td class=\"histo\">$user_modif_demande</td><td class=\"histo\">$user_suppr_demande</td>\n" ;
					echo "</tr>\n" ;
				}
			echo "</table>\n" ;
		}
		echo "<br><br>\n\n" ;
		echo "</td>\n";
	}


	/*************************/
	/* Historique des Conges */
	/*************************/
	if($onglet=="historique_conges")
	{
		echo "<td colspan=$nb_colonnes>\n";
		// Récupération des informations
		$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_etat FROM conges_periode WHERE p_login = '".$session_username."' and ( (p_etat='pris' OR  p_etat='annulé' OR  p_etat='refusé') OR (p_etat='rtt_prise' OR  p_etat='rtt_annulée' OR  p_etat='rtt_refusée') ) ";
		if($tri_date=="descendant")
			$sql2=$sql2." ORDER BY p_date_deb DESC ";
		else
			$sql2=$sql2." ORDER BY p_date_deb ASC ";
		$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

		printf("<h3>Historique des congés :</h3>\n");

		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			echo "<b>Aucun congés dans la base de données ...</b><br>\n";
		}
		else
		{
			// AFFICHAGE TABLEAU
			printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
			echo "<tr align=\"center\">\n";
			echo " <td class=\"titre\">
					<a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
					 Debut
					<a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
				  </td>\n";
			echo " <td class=\"titre\">Fin</td>\n";
			echo " <td class=\"titre\">nb Jours</td>\n";
			echo " <td class=\"titre\">Commentaire</td>\n";
			echo " <td class=\"titre\">Etat</td>\n";
			echo "</tr>\n";
			while ($resultat2 = mysql_fetch_array($ReqLog2)) {
					$sql_p_date_deb = eng_date_to_fr($resultat2["p_date_deb"]);
					$sql_p_demi_jour_deb = $resultat2["p_demi_jour_deb"];
					if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
					$sql_p_date_fin = eng_date_to_fr($resultat2["p_date_fin"]);
					$sql_p_demi_jour_fin = $resultat2["p_demi_jour_fin"];
					if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
					$sql_p_nb_jours = $resultat2["p_nb_jours"];
					$sql_p_commentaire = $resultat2["p_commentaire"];
					$sql_p_etat = $resultat2["p_etat"];

					echo "<tr align=\"center\">\n";
//					printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n",
//							$resultat2["p_date_deb"], $resultat2["p_date_fin"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_commentaire"], $resultat2["p_etat"]);
					echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td><td class=\"histo\">$sql_p_nb_jours</td><td class=\"histo\">$sql_p_commentaire</td><td class=\"histo\">$sql_p_etat</td>\n" ;
					echo "</tr>\n";
			}
			printf("</table>\n\n");
		}
		printf("<br><br>\n");
		echo "</td>\n";
	}


	/**********************************/
	/* Historique des absences autres */
	/**********************************/
	if($onglet=="historique_autres_absences")
	{
		echo "<td colspan=$nb_colonnes>\n";
		// Récupération des informations
		$sql4 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_num, p_etat FROM conges_periode WHERE p_login = '".$session_username."' and (p_etat = \"mission\" or p_etat = \"formation\" or p_etat = \"autre\" or p_etat = \"absence-annulée\") "  ;
		if($tri_date=="descendant")
			$sql4=$sql4." ORDER BY p_date_deb DESC ";
		else
			$sql4=$sql4." ORDER BY p_date_deb ASC ";
		$ReqLog4 = mysql_query($sql4, $link) or die("ERREUR : mysql_query : ".$sql4." --> ".mysql_error());

		printf("<h3>Historique des absences pour mission, formation, etc ... :</h3>\n");

		$count4=mysql_num_rows($ReqLog4);
		if($count4==0)
		{
			echo "<b>Aucune absences dans la base de données ...</b><br>\n";
		}
		else
		{
			// AFFICHAGE TABLEAU
			printf("<table cellpadding=\"2\"  class=\"tablo\" width=\"80%%\">\n");
			echo "<tr align=\"center\">\n";
			echo "<td class=\"titre\">
					<a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
					 Debut
					<a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
				</td>\n";
			echo "<td class=\"titre\">Fin</td>\n";
			echo "<td class=\"titre\">nb Jours</td>\n";
			echo "<td class=\"titre\">Commentaire</td>\n";
			echo "<td class=\"titre\">Absence</td>\n";
			echo "<td></td><td></td>\n";
			echo "</tr>\n";
			while ($resultat4 = mysql_fetch_array($ReqLog4))
			{
				$sql_login= $resultat4["p_login"];
				$sql_date_deb= eng_date_to_fr($resultat4["p_date_deb"]);
				$sql_date_fin= eng_date_to_fr($resultat4["p_date_fin"]);
				$sql_nb_jours= affiche_decimal($resultat4["p_nb_jours"]);
				$sql_commentaire= $resultat4["p_commentaire"];
				$sql_num= $resultat4["p_num"];
				$sql_etat=$resultat4["p_etat"];
				
				// si le user a le droit de saisir lui meme ses absences et qu'elle n'est pas deja annulee, on propose de modifier ou de supprimer
				if(($sql_etat != "absence-annulée")&&($config_user_saisie_mission==TRUE))
				{
					$user_modif_mission="<a href=\"user_modif_demande.php?session=$session&p_num=$sql_num&onglet=$onglet\">Modifier</a>" ;
					$user_suppr_mission="<a href=\"user_suppr_demande.php?session=$session&p_num=$sql_num&onglet=$onglet\">Supprimer</a>" ;
				}
				else
				{
					$user_modif_mission=" - " ;
					$user_suppr_mission=" - " ;
				}
				echo "<tr align=\"center\">\n";
				echo "<td class=\"histo\">$sql_date_deb</td><td class=\"histo\">$sql_date_fin</td>
					<td class=\"histo\">$sql_nb_jours</td><td class=\"histo\">$sql_commentaire</td><td class=\"histo\">$sql_etat</td>
					<td class=\"histo\">$user_modif_mission</td><td class=\"histo\">$user_suppr_mission</td>\n" ;
				echo "</tr>\n";
			}
			printf("</table>\n\n");
		}
		printf("<br><br>\n");
		echo "</td>\n";
	}

	/**************************/
	/* Changer Password */
	/**************************/
	// si  autentification demandée dans config.php et user peut changer son password
	//if(($config_auth==TRUE) && ($config_user_ch_passwd==TRUE)) {
	if($onglet=="changer_mot_de_passe")
	{
		echo "<td colspan=$nb_colonnes>\n";
		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
		printf("<H3>Changer votre mot de passe :</H3>\n\n");

		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<table cellpadding=\"2\" class=\"tablo\" width=\"500\">\n");
		printf("<tr align=\"center\"><td class=\"titre\">1iere saisie</td><td class=\"titre\">2eme saisie</td></tr>\n");

		$text_passwd1="<input type=\"password\" name=\"new_passwd1\" size=\"10\" maxlength=\"20\" value=\"\">" ;
		$text_passwd2="<input type=\"password\" name=\"new_passwd2\" size=\"10\" maxlength=\"20\" value=\"\">" ;
		printf("<tr align=\"center\">\n");
		printf("<td>%s</td><td>%s</td>\n", $text_passwd1, $text_passwd2);
		printf("</tr>\n");

		printf("</table><br>\n");
		printf("<input type=\"hidden\" name=\"change_passwd\" value=1>\n");
		printf("<input type=\"submit\" value=\"Valider\">   <input value=\"cancel\" type=\"reset\">\n");
		printf("</form>\n" ) ;
		echo "</td>\n";
	}

	echo "</CENTER>\n";

	// affichage "deconnexion" et "actualiser page":
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
	echo "<a href=\"$PHP_SELF?session=$session&onglet=$onglet\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Actualiser la Page\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td></tr></table>\n";

	echo "<CENTER>\n";

	mysql_close($link);
}

function new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_etat) {
	global $PHP_SELF;
	global $config_mail_alerte_resp;
	//global $MYSQL_HOST, $MYSQL_USER ,$MYSQL_PASSWD, $CONGES_DATABASE;
	global $session, $session_username;
	//global $new_debut, $new_fin, $new_nb_jours, $new_comment ;

	
	// verif validité des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment);

	if($valid==TRUE)
	{
		//connexion mysql
		$link = connexion_mysql() ;

		/**********************/
		/* calcul num demande */
		/**********************/
		// Récupération du + grand p_num (+ grand numero identifiant de conges)
		$sql1 = "SELECT max(p_num) FROM conges_periode" ;
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
		//$num_new_demande = mysql_result($ReqLog1, p_num)+1;
		$num_new_demande = mysql_result($ReqLog1, 0)+1;

		echo "$session_username---$new_debut---$new_demi_jour_deb---$new_fin---$new_demi_jour_fin---$new_nb_jours---$new_comment---$new_etat---$num_new_demande<br>\n";
		//echo($new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."<br>");
		if($new_etat=="conges") $new_etat="demande" ;
		if($new_etat=="rtt") $new_etat="demande_rtt" ;

		$sql1 = "INSERT into conges_periode (p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_etat, p_num)
				VALUES ('$session_username','$new_debut','$new_demi_jour_deb','$new_fin','$new_demi_jour_fin','$new_nb_jours','$new_comment','$new_etat','$num_new_demande')" ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		mysql_close($link);

		if($result==TRUE)
		{
			printf(" Changements pris en compte avec succes !<br><br> \n");
			//envoi d'un mail d'alerte au responsable (si demandé dans config.php)
			if($config_mail_alerte_resp==TRUE)
				alerte_resp_mail($session_username);
		}
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	}
	else
	{
			printf(" ERREUR ! Les valeurs saisies sont invalides ou manquantes  !!!<br><br> \n");
	}

		/* RETOUR PAGE PRINCIPALE */
		echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
		echo "<input type=\"submit\" value=\"Retour\">\n";
		echo " </form> \n";

}

function echange_absence_rtt($onglet, $new_debut, $new_fin, $new_comment, $moment_absence_ordinaire, $moment_absence_souhaitee) {
	global $PHP_SELF;
	global $session, $session_username;
	global $link;

	//connexion mysql
	$link = connexion_mysql() ;
	$nb_insert=2;
	$valid=TRUE;


	// verif si les dates sont renseignées  (si ce n'est pas le cas, on ne verifie meme pas le suite !)
	if( ($new_debut=="")||($new_fin=="") )
		$valid=FALSE;
	else
	{
		// verif si le premier jour est bien un jour d'absence
		// recup des infos ARTT ou Temps Partiel :
		$date_1=explode("-", $new_debut);
		$j_timestamp_1=mktime (0,0,0,$date_1[1], $date_1[2], $date_1[0]);
		recup_infos_artt_du_jour($session_username, $j_timestamp_1, $val_matin, $val_aprem);
		if ( (($val_matin=="N")&&($val_aprem=="N"))                       // si pas un jour dcomplet de presence
			|| (($val_matin=="N")&&($moment_absence_ordinaire=="M"))     // ou si echange du matin demandé mais pas matin d'absence
			|| (($val_aprem=="N")&&($moment_absence_ordinaire=="A")) )   // ou si echange de l'aprem demandé mais pas aprem d'absence
			$valid=FALSE;

		// attention : si journée complète d'absence, mais on demande l'échange d'1/2 journée seulement :
		// il faut inserer l'absence et la presence dans l'enregisterment de la table
		if ( (($val_matin=="Y")&&($val_aprem=="Y")) && (($moment_absence_ordinaire=="M")||($moment_absence_ordinaire=="A")) )
		{
			if($moment_absence_ordinaire=="M")
			{
				$nouvelle_presence_date_1="M";
				$nouvelle_absence_date_1="A";
			}
			else
			{
				$nouvelle_presence_date_1="A";
				$nouvelle_absence_date_1="M";
			}
		}
		else
		{
			$nouvelle_presence_date_1="J";
			$nouvelle_absence_date_1="N";
		}


		// verif si le 2ieme jour est bien un jour travaillé
		// recup des infos ARTT ou Temps Partiel :
		$date_2=explode("-", $new_fin);
		$j_timestamp_2=mktime (0,0,0,$date_2[1], $date_2[2], $date_2[0]);
		recup_infos_artt_du_jour($session_username, $j_timestamp_2, $val_matin, $val_aprem);
		if ( (($val_matin=="Y")&&($val_aprem=="Y"))                        // si jour d'absence complete
			|| (($val_matin=="Y")&&($moment_absence_souhaitee=="M"))      // matin d'absence mais echange du matin demandé
			|| (($val_aprem=="Y")&&($moment_absence_souhaitee=="A")) )    // aprem d'absence mais echange de l'aprem demandé
			$valid=FALSE;

		// attention : si journée complète de presence, mais on demande l'échange d'1/2 journée seulement :
		// il faut inserer l'absence et la presence dans l'enregisterment de la table
		if ( (($val_matin=="N")&&($val_aprem=="N")) && (($moment_absence_souhaitee=="M")||($moment_absence_souhaitee=="A")) )
		{
			if($moment_absence_souhaitee=="M")
			{
				$nouvelle_presence_date_2="A";
				$nouvelle_absence_date_2="M";
			}
			else
			{
				$nouvelle_presence_date_2="M";
				$nouvelle_absence_date_2="A";
			}
		}
		else
		{
			$nouvelle_presence_date_2="N";
			$nouvelle_absence_date_2="J";
		}


		// verif de la concordance des durée (journée avec journée ou 1/2 journée avec1/2 journée)
		if( (($moment_absence_ordinaire=='J')&&($moment_absence_souhaitee!='J')) || (($moment_absence_ordinaire!='J')&&($moment_absence_souhaitee=='J')) )
			$valid=FALSE;
	}

	if($valid==TRUE)
	{
		echo "$session_username---$new_debut---$new_fin---$new_comment<br>\n" ;

		// insert du jour d'absence ordinaire (qui n'en sera plus un ou qu'a moitie ...)
		// e_presence = N (non) , J (jour entier) , M (matin) ou A (apres-midi)
		// verif si le couple user/date1 existe dans conges_echange_rtt ...
		$sql_verif_echange1="SELECT e_absence, e_presence from conges_echange_rtt WHERE e_login='$session_username' AND e_date_jour='$new_debut' ";
		$result_verif_echange1 = mysql_query($sql_verif_echange1, $link) or die("ERREUR : echange_absence_rtt() :<br>\n".$sql_verif_echange1."<br>\n".mysql_error());
		$count_verif_echange1=mysql_num_rows($result_verif_echange1);
		
		// si le couple user/date1 existe dans conges_echange_rtt : on update
		if($count_verif_echange1!=0)
		{
			//$resultat1=mysql_fetch_array($result_verif_echange1);
			//if($resultatverif_echange1['e_absence'] == 'N' )
			$sql1 = "UPDATE conges_echange_rtt 
					SET e_absence='$nouvelle_absence_date_1', e_presence='$nouvelle_presence_date_1', e_comment='$new_comment' 
					WHERE e_login='$session_username' AND e_date_jour='$new_debut' ";
		}
		else // sinon : on insert
		{
			$sql1 = "INSERT into conges_echange_rtt (e_login, e_date_jour, e_absence, e_presence, e_comment)
					VALUES ('$session_username','$new_debut','$nouvelle_absence_date_1', '$nouvelle_presence_date_1', '$new_comment')" ;
		}
		$result1 = mysql_query($sql1, $link) or die("ERREUR : echange_absence_rtt() :<br>\n".$sql1."<br>\n".mysql_error());

		// insert du jour d'absence souhaité (qui en devient un)
		// e_absence = N (non) , J (jour entier) , M (matin) ou A (apres-midi)
		// verif si le couple user/date2 existe dans conges_echange_rtt ...
		$sql_verif_echange2="SELECT e_absence, e_presence from conges_echange_rtt WHERE e_login='$session_username' AND e_date_jour='$new_fin' ";
		$result_verif_echange2 = mysql_query($sql_verif_echange2, $link) or die("ERREUR : echange_absence_rtt() :<br>\n".$sql_verif_echangeé."<br>\n".mysql_error());
		$count_verif_echange2=mysql_num_rows($result_verif_echange2);
		
		// si le couple user/date2 existe dans conges_echange_rtt : on update
		if($count_verif_echange2!=0)
		{
			$sql2 = "UPDATE conges_echange_rtt 
					SET e_absence='$nouvelle_absence_date_2', e_presence='$nouvelle_presence_date_2', e_comment='$new_comment' 
					WHERE e_login='$session_username' AND e_date_jour='$new_fin' ";
		}
		else // sinon: on insert
		{
			$sql2 = "INSERT into conges_echange_rtt (e_login, e_date_jour, e_absence, e_presence, e_comment)
					VALUES ('$session_username','$new_fin','$nouvelle_absence_date_2', '$nouvelle_presence_date_2', '$new_comment')" ;
		}
		$result2 = mysql_query($sql2, $link) or die("ERREUR : echange_absence_rtt() :<br>\n".$sql2."<br>\n".mysql_error());

		if(($result1==TRUE)&&($result2==TRUE))
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Une erreur s'est produite : contactez votre responsable !<br><br> \n");
	}
	else
	{
			printf(" ERREUR ! Les valeurs saisies sont invalides ou manquantes  !!!<br><br> \n");
	}

		/* RETOUR PAGE PRINCIPALE */
		echo " <form action=\"$PHP_SELF?session=$session&onglet=$onglet\" method=\"POST\"> \n";
		echo "<input type=\"submit\" value=\"Retour\">\n";
		echo " </form> \n";

		mysql_close($link);

}

function change_passwd() {
	global $PHP_SELF;
	global $session, $session_username;
	global $new_passwd1, $new_passwd2 ;

	if((strlen($new_passwd1)==0) || (strlen($new_passwd2)==0) || ($new_passwd1!=$new_passwd2)) {         // si les 2 passwd sont vides ou diffï?½entes
		echo "ERREUR ! les 2 saisies sont différentes ou vides !!<br>\n" ;
	}
	else {
		//connexion mysql
		$link = connexion_mysql() ;

		$sql1 = "UPDATE conges_users SET  u_passwd=password('".$new_passwd1."') WHERE u_login='".$session_username."' " ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		mysql_close($link);

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	}

	/* APPEL D'UNE AUTRE PAGE */
	echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
	echo " <input type=\"submit\" value=\"Retour\">\n";
	echo " </form> \n";

}


?>
<hr align="center" size="2" width="90%">
<br>
</CENTER>
</body>
</html>
