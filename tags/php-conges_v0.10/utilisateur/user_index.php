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

include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
if($config_where_to_find_user_email=="ldap"){ include("../config_ldap.php");}


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

	/*************************************/
	// recup des parametres re�us :
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
	if(isset($_POST['new_type'])) { $new_type=$_POST['new_type']; }
	if(isset($_POST['moment_absence_ordinaire'])) { $moment_absence_ordinaire=$_POST['moment_absence_ordinaire']; }
	if(isset($_POST['moment_absence_souhaitee'])) { $moment_absence_souhaitee=$_POST['moment_absence_souhaitee']; }
	if(isset($_POST['change_passwd'])) { $change_passwd=$_POST['change_passwd']; }
	if(isset($_POST['new_passwd1'])) { $new_passwd1=$_POST['new_passwd1']; }
	if(isset($_POST['new_passwd2'])) { $new_passwd2=$_POST['new_passwd2']; }
	if( (!isset($onglet)) || ($onglet=="") )
		if(isset($_POST['onglet'])) { $onglet=$_POST['onglet']; }
	/*************************************/
	
	//connexion mysql
	$link = connexion_mysql();
	
	// on initialise le tableau global des jours f�ri�s s'il ne l'est pas d�j� :
	if(!isset($GLOBALS["tab_j_feries"]))
	{
		init_tab_jours_feries($link);
		//print_r($GLOBALS["tab_j_feries"]);   // verif DEBUG
	}
	
	/*************************************/
	/***  debut de la page             ***/
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<TITLE> $config_titre_user_index $session_username</TITLE>\n";
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	include("../fonctions_javascript.php") ;
	echo "</head>\n";

	echo "<body text=\"#000000\" bgcolor=$config_bgcolor link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";

	
	/*************************************/
	/*** affichage "deconnexion" et "actualiser page" et "mode administrateur" et "affichage calendrier" ***/
	/*************************************/
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>\n";
	/*** bouton deconnexion  ***/
	if(($config_auth==TRUE)&&($config_verif_droits!=TRUE))
	{
		echo "<td width=\"120\" valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
	}
	
	/*** bouton actualiser  ***/
	echo "<td width=\"140\" valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&onglet=$onglet\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Actualiser la Page\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td>\n";
	
	/*** bouton �ditions papier  ***/
	if($config_editions_papier==TRUE)
	{
		echo "<td width=\"155\" valign=\"middle\">\n";
		echo "<a href=\"../edition/edit_user.php?session=$session&user_login=$session_username\" target=\"_blank\"><img src=\"../img/edition-22x22.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Editions Papier\" alt=\"Editions Papier\"></a> Editions Papier\n";
		echo "</td>\n";
	}
	/*** cellule centrale vide  ***/
	echo "<td valign=\"middle\">\n";
	echo "&nbsp;\n";
	echo "</td>\n";
	
	/*** bouton mode administrateur  ***/
	if(is_admin($session_username, $link))
	{
		echo "<td width=\"155\" align=\"right\" valign=\"middle\">\n";
		echo "<a href=\"../admin/admin_index.php?session=$session\" method=\"POST\" target=\"_blank\"><img src=\"../img/admin-tools-22x22.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Mode Administrateur\" alt=\"Mode Administrateur\"></a> Mode Administrateur\n";
		echo "</td>\n";
	}
	
	/*** bouton calendrier  ***/
	if($config_user_affiche_calendrier==TRUE)
	{
		echo "<td width=\"155\" align=\"right\" valign=\"middle\">\n";
		echo "<a href=\"../calendrier.php?session=$session\"><img src=\"../img/rebuild.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Afficher le Calendrier\" alt=\"Afficher le Calendrier\"></a> Afficher le Calendrier\n";
		echo "</td>\n";
	}
	echo "</tr></table>\n";

	echo "<CENTER>\n";

	
	/*************************************/
	/***  suite de la page             ***/
	/*************************************/
	// si le user peut saisir ses demandes et qu'il vient d'en saisir une ...
	if(($new_demande_conges==1) && ($config_user_saisie_demande==TRUE)) {
		new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type);
	}
	elseif(($new_echange_rtt==1)&&($config_user_echange_rtt==TRUE)) {
		echange_absence_rtt($onglet, $new_debut, $new_fin, $new_comment, $moment_absence_ordinaire, $moment_absence_souhaitee);
	}
	elseif($change_passwd==1) {
		change_passwd($link);
	}
	else {
		if($onglet=="")
			$onglet="historique_conges";
		affichage($onglet, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date);
	}

	mysql_close($link);

	/*************************************/
	/***  fin de la page             ***/
	echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";
	echo "<br>\n";
	echo "</CENTER>\n";

//	include("../fonctions_javascript.php") ;
	echo "</body>\n";
	echo "</html>\n";
	
	
	
	
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/

function affichage($onglet, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date)
{
	global $PHP_SELF;
	global $session, $session_username ;
	global $config_auth, $config_user_saisie_demande, $config_user_saisie_mission;
	global $config_rtt_comme_conges, $config_user_echange_rtt, $config_user_ch_passwd , $config_verif_droits;
	global $link;

	// si les mois et ann�e ne sont pas renseign�s, on prend ceux du jour
	if( (!isset($year_calendrier_saisie_debut)) || ($year_calendrier_saisie_debut==0))
		$year_calendrier_saisie_debut=date("Y");
	if( (!isset($mois_calendrier_saisie_debut)) || ($mois_calendrier_saisie_debut==0) )
		$mois_calendrier_saisie_debut=date("m");
	if( (!isset($year_calendrier_saisie_fin)) || ($year_calendrier_saisie_fin==0) )
		$year_calendrier_saisie_fin=date("Y");
	if( (!isset($mois_calendrier_saisie_fin)) || ($mois_calendrier_saisie_fin==0) )
		$mois_calendrier_saisie_fin=date("m");
	//echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n";


	$sql1 = "SELECT u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users where u_login = '$session_username' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : user_index.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$NOM=$resultat1["u_nom"];
		$PRENOM=$resultat1["u_prenom"];
		$sql_nb_jours_an=affiche_decimal($resultat1["u_nb_jours_an"]);
		$sql_solde_jours=affiche_decimal($resultat1["u_solde_jours"]);
		$sql_nb_rtt_an=affiche_decimal($resultat1["u_nb_rtt_an"]);
		$sql_solde_rtt=affiche_decimal($resultat1["u_solde_rtt"]);
		$sql_quotite=$resultat1["u_quotite"];
	}

	// TITRE
	echo "<H1>$session_username : $PRENOM $NOM</H1>\n\n";

	/********************/
	/* Bilan des Conges */
	/********************/
	if($config_rtt_comme_conges==TRUE)
		$taille_tableau_bilan=500;
	else
		$taille_tableau_bilan=300;
	
	printf("<table cellpadding=\"2\" width=\"$taille_tableau_bilan\" class=\"tablo\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">quotit�</td><td class=\"titre\">NB CONGES / AN</td><td class=\"titre\">SOLDE CONGES</td>");
	if($config_rtt_comme_conges==TRUE)
		printf("<td class=\"titre\">NB RTT / AN</td><td class=\"titre\">SOLDE RTT</td>");
	printf("</tr>\n");
	printf("<tr align=\"center\">\n");
	echo "<td>$sql_quotite%</td><td><b>$sql_nb_jours_an</b></td><td bgcolor=\"#FF9191\"><b>$sql_solde_jours</b></td>\n";
	if($config_rtt_comme_conges==TRUE)
		echo "<td><b>$sql_nb_rtt_an</b></td><td bgcolor=\"#FF9191\"><b>$sql_solde_rtt</b></td></tr>\n";
	printf("</tr>\n");
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
			{$taille_onglet=200; $text_onglet_histo_conges=" Historique des cong�s/RTT ";}
		else
			{$taille_onglet=170; $text_onglet_histo_conges=" Historique des cong�s ";}
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

	echo "<CENTER>\n" ;
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\">\n" ;
	/**************************************/
	/*** AFFICHAGE DE LA PAGE DEMAND�E  ***/
	echo "<tr align=\"center\">\n";

	/**************************/
	/* Nouvelle Demande */
	/**************************/
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
	/* Echange absence rtt/jour travaill� */
	/**************************************/
	if($onglet=="echange_jour_absence")
	{
		echo "<td colspan=$nb_colonnes>\n";
		printf("<H3>Echange jour rtt,temps partiel / jour travaill� :</H3>\n\n");

		//affiche le formulaire de saisie d'une nouvelle demande de conges
		saisie_echange_rtt($session_username, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);

		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
		echo "</td>\n";
	}


	/**************************/
	/* Etat demandes en cours */
	/**************************/
	if($onglet=="demandes_en_cours")
	{
		echo "<td colspan=$nb_colonnes>\n";
		// R�cup�ration des informations
		$sql3 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_num 
				FROM conges_periode
				WHERE p_login = '$session_username' 
				AND (p_etat = 'demande') ";
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
//			if($config_rtt_comme_conges==TRUE)
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
					$sql_p_type = $resultat3["p_type"];
					$sql_p_etat = $resultat3["p_etat"];
					$sql_p_num = $resultat3["p_num"];

					$user_modif_demande="<a href=\"user_modif_demande.php?session=$session&p_num=$sql_p_num&onglet=$onglet\">Modifier</a>" ;
					$user_suppr_demande="<a href=\"user_suppr_demande.php?session=$session&p_num=$sql_p_num&onglet=$onglet\">Supprimer</a>" ;
					echo "<tr align=\"center\">\n" ;
					echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td>" ;
//			if($config_rtt_comme_conges==TRUE)
						echo "<td class=\"histo\">$sql_p_type</td>" ;
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
		// R�cup�ration des informations
		$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_motif_refus
				FROM conges_periode 
				WHERE p_login = '$session_username' 
				AND (p_type='conges' OR  p_type='rtt') 
				AND (p_etat='ok' OR  p_etat='refus' OR  p_etat='annul') ";
		if($tri_date=="descendant")
			$sql2=$sql2." ORDER BY p_date_deb DESC ";
		else
			$sql2=$sql2." ORDER BY p_date_deb ASC ";
		$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

		printf("<h3>Historique des cong�s :</h3>\n");

		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			echo "<b>Aucun cong�s dans la base de donn�es ...</b><br>\n";
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
			echo " <td class=\"titre\">Type</td>\n";
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
					$sql_p_type = $resultat2["p_type"];
					$sql_p_etat = $resultat2["p_etat"];
					$sql_p_motif_refus=$resultat2["p_motif_refus"] ;

					echo "<tr align=\"center\">\n";
						echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td>\n";
						echo "<td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td>\n";
						echo "<td class=\"histo\">$sql_p_nb_jours</td>\n";
						echo "<td class=\"histo\">$sql_p_commentaire";
						if($sql_p_etat=="refus")
						{
							if($sql_p_motif_refus=="")
								$sql_p_motif_refus="inconnu";
							echo "<br><i>motif du refus : $sql_p_motif_refus</i>";
						}
						elseif($sql_p_etat=="annul")
						{
							if($sql_p_motif_refus=="")
								$sql_p_motif_refus="inconnu";
							echo "<br><i>motif de l'annulation : $sql_p_motif_refus</i>";
						}
						echo "</td>\n";

						echo "<td class=\"histo\">$sql_p_type</td>\n";
						echo "<td class=\"histo\">";
						if($sql_p_etat=="refus")
							echo "refus�";
						elseif($sql_p_etat=="annul")
							echo "annul�";
						else
							echo "$sql_p_etat";
						echo "</td>\n" ;
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
		// R�cup�ration des informations
		$sql4 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_motif_refus, p_num 
				FROM conges_periode WHERE p_login = '$session_username' 
				AND (p_type = 'mission' OR p_type='formation' OR p_type='autre') "  ;
		if($tri_date=="descendant")
			$sql4=$sql4." ORDER BY p_date_deb DESC ";
		else
			$sql4=$sql4." ORDER BY p_date_deb ASC ";
		$ReqLog4 = mysql_query($sql4, $link) or die("ERREUR : mysql_query : ".$sql4." --> ".mysql_error());

		printf("<h3>Historique des absences pour mission, formation, etc ... :</h3>\n");

		$count4=mysql_num_rows($ReqLog4);
		if($count4==0)
		{
			echo "<b>Aucune absences dans la base de donn�es ...</b><br>\n";
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
			echo "<td class=\"titre\">Etat</td>\n";
			echo "<td></td><td></td>\n";
			echo "</tr>\n";
			while ($resultat4 = mysql_fetch_array($ReqLog4))
			{
				$sql_login= $resultat4["p_login"];
				$sql_date_deb= eng_date_to_fr($resultat4["p_date_deb"]);
				$sql_date_fin= eng_date_to_fr($resultat4["p_date_fin"]);
				$sql_nb_jours= affiche_decimal($resultat4["p_nb_jours"]);
				$sql_commentaire= $resultat4["p_commentaire"];
				$sql_type=$resultat4["p_type"];
				$sql_etat=$resultat4["p_etat"];
				$sql_motif_refus=$resultat4["p_motif_refus"] ;
				$sql_num= $resultat4["p_num"];
				
				// si le user a le droit de saisir lui meme ses absences et qu'elle n'est pas deja annulee, on propose de modifier ou de supprimer
				if(($sql_etat != "annul")&&($config_user_saisie_mission==TRUE))
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
				echo "<td class=\"histo\">$sql_date_deb</td>\n";
				echo "<td class=\"histo\">$sql_date_fin</td>\n";
				echo "<td class=\"histo\">$sql_nb_jours</td>\n";
				echo "<td class=\"histo\">$sql_commentaire";
				if($sql_etat=="refus")
				{
					if($sql_motif_refus=="")
						$sql_motif_refus="inconnu";
					echo "<br><i>motif du refus : $sql_motif_refus</i>";
				}
				elseif($sql_etat=="annul")
				{
					if($sql_motif_refus=="")
						$sql_motif_refus="inconnu";
					echo "<br><i>motif de l'annulation : $sql_motif_refus</i>";
				}
				echo "</td>\n";
				echo "<td class=\"histo\">$sql_type</td>\n";
				echo "<td class=\"histo\">";
				if($sql_etat=="refus")
					echo "refus�";
				elseif($sql_etat=="annul")
					echo "annul�";
				else
					echo "$sql_etat";
				echo "</td>\n";
				echo "<td class=\"histo\">$user_modif_mission</td>\n";
				echo "<td class=\"histo\">$user_suppr_mission</td>\n" ;
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

	echo "</tr>\n";
	/*** FIN AFFICHAGE DE LA PAGE DEMAND�E  ***/
	/******************************************/
	echo "</table>\n";
	echo "</CENTER>\n";

	// affichage "deconnexion" et "actualiser page":
	echo "<table>\n";
	echo "<tr>\n";
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
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<CENTER>\n";

}

function new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type) {
	global $PHP_SELF;
	global $config_mail_new_demande_alerte_resp;
	//global $MYSQL_HOST, $MYSQL_USER ,$MYSQL_PASSWD, $CONGES_DATABASE;
	global $session, $session_username;
	//global $new_debut, $new_fin, $new_nb_jours, $new_comment ;
	global $link;

	//echo " $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type<br><br>\n";
	
	// verif validit� des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type);

	if($valid==TRUE)
	{
		if( ($new_type=="conges") || ($new_type=="rtt"))
			$new_etat="demande" ;
		if( ($new_type=="formation") || ($new_type=="mission") || ($new_type=="autre") )
			$new_etat="ok" ;
		echo "$session_username---$new_debut---$new_demi_jour_deb---$new_fin---$new_demi_jour_fin---$new_nb_jours---$new_comment---$new_type---$new_etat<br>\n";

		$result=insert_dans_periode($session_username, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type, $new_etat, $link);

		if($result==TRUE)
		{
			printf(" Changements pris en compte avec succes !<br><br> \n");
			//envoi d'un mail d'alerte au responsable (si demand� dans config.php)
			if($config_mail_new_demande_alerte_resp==TRUE)
				alerte_mail($session_username, ":responsable:", "new_demande");
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
//	$link = connexion_mysql() ;
	$nb_insert=2;
	$valid=TRUE;


	// verif si les dates sont renseign�es  (si ce n'est pas le cas, on ne verifie meme pas le suite !)
	if( ($new_debut=="")||($new_fin=="") )
		$valid=FALSE;
	else
	{
		// verif si le premier jour est bien un jour d'absence
		// recup des infos ARTT ou Temps Partiel :
		$date_1=explode("-", $new_debut);
		$j_timestamp_1=mktime (0,0,0,$date_1[1], $date_1[2], $date_1[0]);
		recup_infos_artt_du_jour($session_username, $j_timestamp_1, $val_matin, $val_aprem, $link);
		if ( (($val_matin=="N")&&($val_aprem=="N"))                       // si pas un jour dcomplet de presence
			|| (($val_matin=="N")&&($moment_absence_ordinaire=="M"))     // ou si echange du matin demand� mais pas matin d'absence
			|| (($val_aprem=="N")&&($moment_absence_ordinaire=="A")) )   // ou si echange de l'aprem demand� mais pas aprem d'absence
			$valid=FALSE;

		// attention : si journ�e compl�te d'absence, mais on demande l'�change d'1/2 journ�e seulement :
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


		// verif si le 2ieme jour est bien un jour travaill�
		// recup des infos ARTT ou Temps Partiel :
		$date_2=explode("-", $new_fin);
		$j_timestamp_2=mktime (0,0,0,$date_2[1], $date_2[2], $date_2[0]);
		recup_infos_artt_du_jour($session_username, $j_timestamp_2, $val_matin, $val_aprem, $link);
		if ( (($val_matin=="Y")&&($val_aprem=="Y"))                        // si jour d'absence complete
			|| (($val_matin=="Y")&&($moment_absence_souhaitee=="M"))      // matin d'absence mais echange du matin demand�
			|| (($val_aprem=="Y")&&($moment_absence_souhaitee=="A")) )    // aprem d'absence mais echange de l'aprem demand�
			$valid=FALSE;

		// attention : si journ�e compl�te de presence, mais on demande l'�change d'1/2 journ�e seulement :
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


		// verif de la concordance des dur�e (journ�e avec journ�e ou 1/2 journ�e avec1/2 journ�e)
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

		// insert du jour d'absence souhait� (qui en devient un)
		// e_absence = N (non) , J (jour entier) , M (matin) ou A (apres-midi)
		// verif si le couple user/date2 existe dans conges_echange_rtt ...
		$sql_verif_echange2="SELECT e_absence, e_presence from conges_echange_rtt WHERE e_login='$session_username' AND e_date_jour='$new_fin' ";
		$result_verif_echange2 = mysql_query($sql_verif_echange2, $link) or die("ERREUR : echange_absence_rtt() :<br>\n".$sql_verif_echange�."<br>\n".mysql_error());
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

//		mysql_close($link);

}

function change_passwd($link) {
	global $PHP_SELF;
	global $session, $session_username;
	global $new_passwd1, $new_passwd2 ;

	if((strlen($new_passwd1)==0) || (strlen($new_passwd2)==0) || ($new_passwd1!=$new_passwd2)) {         // si les 2 passwd sont vides ou diff�?�entes
		echo "ERREUR ! les 2 saisies sont diff�rentes ou vides !!<br>\n" ;
	}
	else {
		//connexion mysql
//		$link = connexion_mysql() ;

		$passwd_md5=md5($new_passwd1);
		$sql1 = "UPDATE conges_users SET  u_passwd='$passwd_md5' WHERE u_login='$session_username' " ;
		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
//		mysql_close($link);

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