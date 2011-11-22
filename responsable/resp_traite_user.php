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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../fonctions_javascript.php");
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG = FALSE ;
//$DEBUG = TRUE ;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php 
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";

	/*** initialisation des variables ***/
	/*************************************/
	// recup des parametres reçus :
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
	
	//echo "<br>$user_login";   /* envoyé par le formulaire précédent */

	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// si une annulation de conges a été selectionée :
	if($tab_checkbox_annule!="") 
	{
		annule_conges($user_login, $tab_checkbox_annule, $tab_text_annul, $mysql_link, $DEBUG);
	}
	// si le traitement des demandes a été selectionée :
	elseif($tab_radio_traite_demande!="") 
	{
		traite_demandes($user_login, $tab_radio_traite_demande, $tab_text_refus, $mysql_link, $DEBUG);
	}
	// si un nouveau conges ou absence a été saisi pour un user :
	elseif($new_demande_conges==1) 
	{
		new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type, $mysql_link, $DEBUG);
	}
	else 
	{
		affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date, $mysql_link, $DEBUG);
	}
	
	mysql_close($mysql_link);
	
	
	
/*************************************/
/***   FONCTIONS   ***/
/*************************************/

function affichage($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date, $mysql_link, $DEBUG)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id();

	// on initialise le tableau global des jours fériés s'il ne l'est pas déjà :
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
	echo "<td valign=\"middle\">\n";
	echo "<a href=\"$PHP_SELF?session=$session&user_login=$user_login\"><img src=\"../img/reload_page.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Actualiser la Page\" alt=\"Actualiser la Page\"></a> Actualiser la Page\n";
	echo "</td></tr></table>\n";
	echo "<center>\n";
	
	/********************/
	/* Titre */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_nom, u_prenom, u_quotite FROM conges_users WHERE u_login = '$user_login' ";
	$ReqLog1 = requete_mysql($sql1, $mysql_link, "affichage", $DEBUG);

	// TITRE
	$resultat1 = mysql_fetch_array($ReqLog1) ;
	$slq_nom=$resultat1["u_nom"];
	$sql_prenom=$resultat1["u_prenom"];
	$sql_quotite=$resultat1["u_quotite"];
	
	echo "<H3>Traitement de :</H3><H2> $sql_prenom $slq_nom.</H2>\n\n";

	
	/********************/
	/* Bilan des Conges */
	/********************/
	// AFFICHAGE TABLEAU
	// affichage du tableau récapitulatif des solde de congés d'un user
	affiche_tableau_bilan_conges_user($user_login, $mysql_link);
	echo "<br><br>\n";

	/*************************/
	/* SAISIE NOUVEAU CONGES */
	/*************************/
	// dans le cas ou les users ne peuvent pas saisir de demande, le responsable saisi les congès :
	if(($_SESSION['config']['user_saisie_demande']==FALSE)||($_SESSION['config']['resp_saisie_mission']==TRUE)) 
	{
	
		// si les mois et année ne sont pas renseignés, on prend ceux du jour
		if($year_calendrier_saisie_debut==0)
			$year_calendrier_saisie_debut=date("Y");
		if($mois_calendrier_saisie_debut==0)
			$mois_calendrier_saisie_debut=date("m");
		if($year_calendrier_saisie_fin==0)
			$year_calendrier_saisie_fin=date("Y");
		if($mois_calendrier_saisie_fin==0)
			$mois_calendrier_saisie_fin=date("m");	
		//echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n";
	
		echo "<H3>Nouveau Congès/Absence :</H3>\n\n";
		
		//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
		saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, "", $mysql_link);

		echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	}

	/*********************/
	/* Etat des Demandes */
	/*********************/
	if($_SESSION['config']['user_saisie_demande']==TRUE) 
	{
		echo "<h3>Etat des demandes :</h3>\n";

		//affiche l'état des demande du user (avec le formulaire pour le responsable)
		affiche_etat_demande_user_for_resp($user_login, $mysql_link, $DEBUG);

		echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	}
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	echo "<h3>Etat des congès :</h3>\n";
	
	//affiche l'état des conges du user (avec le formulaire pour le responsable)
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



//affiche l'état des demande du user (avec le formulaire pour le responsable)
function affiche_etat_demande_user_for_resp($user_login, $mysql_link, $DEBUG)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;

	// Récupération des informations
	$sql2 = "SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_num " .
			"FROM conges_periode " .
			"WHERE p_login = '$user_login' ANd p_etat ='demande' ORDER BY p_date_deb";
	$ReqLog2 = requete_mysql($sql2, $mysql_link, "affichage", $DEBUG);
		
	$count2=mysql_num_rows($ReqLog2);
	if($count2==0)
	{
		echo "<b>Aucune demande de congés pour cette personne dans la base de données ...</b><br><br>\n";		
	}
	else
	{
		// recup dans un tableau des types de conges
		$tab_type_conges = recup_tableau_types_conges($mysql_link);

		// AFFICHAGE TABLEAU
		echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
		echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
		echo "<tr align=\"center\"><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td class=\"titre\">Type</td><td class=\"titre\">Accepter</td><td class=\"titre\">Refuser</td><td class=\"titre\">motif refus</td></tr>\n";
		$tab_checkbox=array();
		while ($resultat2 = mysql_fetch_array($ReqLog2)) 
		{
			$sql_date_deb=eng_date_to_fr($resultat2["p_date_deb"]) ;
			$sql_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
			if($sql_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
			$sql_date_fin=eng_date_to_fr($resultat2["p_date_fin"]) ;
			$sql_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
			if($sql_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
			$sql_nb_jours=affiche_decimal($resultat2["p_nb_jours"]) ;
			$sql_commentaire=$resultat2["p_commentaire"] ;
			$sql_type=$resultat2["p_type"] ;
			$sql_num=$resultat2["p_num"] ;
			
			//$casecocher1=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$sql_login--$sql_nb_jours--$sql_type--ACCEPTE\">");
			//$casecocher2=sprintf("<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$sql_login--$sql_nb_jours--$sql_type--REFUSE\">");
			$casecocher1 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--ACCEPTE\">";
			$casecocher2 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$user_login--$sql_nb_jours--$sql_type--REFUSE\">";
			$text_refus  = "<input type=\"text\" name=\"tab_text_refus[$sql_num]\" size=\"20\" max=\"100\">";
			echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td><td class=\"histo\">$sql_date_fin _ $demi_j_fin</td><td class=\"histo\">$sql_nb_jours</td><td class=\"histo\">$sql_commentaire</td><td class=\"histo\">".$tab_type_conges[$sql_type]."</td><td class=\"histo\">$casecocher1</td><td class=\"histo\">$casecocher2</td><td class=\"histo\">$text_refus</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n\n";

		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<br><input type=\"submit\" value=\"Valider\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"Cancel\">\n";
		echo " </form> \n";
	}
}



//affiche l'état des conges du user (avec le formulaire pour le responsable)
function affiche_etat_conges_user_for_resp($user_login, $tri_date, $mysql_link, $DEBUG)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;
	
	// Récupération des informations de speriodes de conges/absences
	$sql3 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_motif_refus, p_num FROM conges_periode WHERE p_login = '$user_login' AND p_etat !='demande' ";
	if($tri_date=="descendant")
		$sql3=$sql3." ORDER BY p_date_deb DESC ";
	else
		$sql3=$sql3." ORDER BY p_date_deb ASC ";
		
	$ReqLog3 = requete_mysql($sql3, $mysql_link, "affiche_etat_conges_user_for_resp", $DEBUG);

	$count3=mysql_num_rows($ReqLog3);
	if($count3==0)
	{
		echo "<b>Aucun congés pour cette personne dans la base de données ...</b><br><br>\n";		
	}
	else
	{
		// recup dans un tableau de tableau les infos des types de conges et absences
		$tab_types_abs = recup_tableau_tout_types_abs($mysql_link) ;
		
		// AFFICHAGE TABLEAU
		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
		echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
		echo "<tr align=\"center\">\n";
		echo " <td class=\"titre\">
				<a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
				 Debut 
				<a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>
			  </td>\n";
		echo " <td class=\"titre\">Fin</td>\n";
		echo " <td class=\"titre\">nb Jours Pris</td>\n";
		echo " <td class=\"titre\">Commentaire<br><i>motif refus ou annulation éventuel</i></td>\n";
		echo " <td class=\"titre\">Type</td>\n";
		echo " <td class=\"titre\">Etat</td>\n";
		echo " <td class=\"titre\">Annuler</td>\n";
		echo " <td class=\"titre\">motif annulation</td>\n";
		echo "</tr>\n";
		$tab_checkbox=array();
		while ($resultat3 = mysql_fetch_array($ReqLog3)) 
		{
				$sql_login=$resultat3["p_login"] ;
				$sql_date_deb=eng_date_to_fr($resultat3["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat3["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
				$sql_date_fin=eng_date_to_fr($resultat3["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat3["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
				$sql_nb_jours=affiche_decimal($resultat3["p_nb_jours"]) ;
				$sql_commentaire=$resultat3["p_commentaire"] ;
				$sql_type=$resultat3["p_type"] ;
				$sql_etat=$resultat3["p_etat"] ;
				$sql_motif_refus=$resultat3["p_motif_refus"] ;
				$sql_num=$resultat3["p_num"] ;
				
				if(($sql_etat=="annul") || ($sql_etat=="refus") || ($sql_etat=="ajout")) 
				{
					$casecocher1="";
					if($sql_etat=="refus")
					{
						if($sql_motif_refus=="")
							$sql_motif_refus="inconnu";
						//$text_annul="<i>motif du refus : $sql_motif_refus</i>";
						$text_annul="<i>motif : $sql_motif_refus</i>";
					}
					elseif($sql_etat=="annul")
					{
						if($sql_motif_refus=="")
							$sql_motif_refus="inconnu";
						//$text_annul="<i>motif de l'annulation : $sql_motif_refus</i>";
						$text_annul="<i>motif : $sql_motif_refus</i>";
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
						echo "refusé";
					elseif($sql_etat=="annul")
						echo "annulé";
					else
						echo "$sql_etat";
					echo "</td>\n";
					echo "<td class=\"histo\">$casecocher1</td>\n";
					echo "<td class=\"histo\">$text_annul</td>\n";
				echo "</tr>\n";
			}
		echo "</table>\n\n";

		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<br><input type=\"submit\" value=\"Valider les Annulations\">\n";
		echo " </form> \n";
	}
}



function annule_conges($user_login, $tab_checkbox_annule, $tab_text_annul, $mysql_link, $DEBUG) 
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
		
		echo "<br><br>conges numero :$numero ---> login : $user_login --- nb de jours : $user_nb_jours_pris_float --- type : $user_type_abs_id ---> ANNULER <br>";

		/* UPDATE table "conges_periode" */
		$sql1 = "UPDATE conges_periode SET p_etat=\"annul\", p_motif_refus='$motif_annul' WHERE p_num=$numero_int" ;
		$ReqLog1 = requete_mysql($sql1, $mysql_link, "annule_conges", $DEBUG);

		/* UPDATE table "conges_solde_user" (jours restants) */
		// on re-crédite les jours seulement pour des conges pris (pas pour les absences)
		// donc seulement si le type de l'absence qu'on annule est un "conges"
		if($tab_tout_type_abs[$user_type_abs_id]['type']=="conges")
		{
			$sql2 = "UPDATE conges_solde_user SET su_solde = su_solde+$user_nb_jours_pris_float WHERE su_login='$user_login' AND su_abs_id=$user_type_abs_id " ;
			//echo($sql2."<br>");
			$ReqLog2 = requete_mysql($sql2, $mysql_link, "annule_conges", $DEBUG);
		}
		
		//envoi d'un mail d'alerte au user (si demandé dans config.php)
		if($_SESSION['config']['mail_annul_conges_alerte_user']==TRUE)
			alerte_mail($_SESSION['userlogin'], $user_login, "annul_conges");
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<input type=\"submit\" value=\"OK\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " Changements pris en compte avec succes !<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";
	}

}



function traite_demandes($user_login, $tab_radio_traite_demande, $tab_text_refus, $mysql_link, $DEBUG) 
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
		echo "<br><br>conges numero :$numero --- User_login : $user_login --- nb de jours : $user_nb_jours_pris --->$value_traite<br>" ;

		if($value_traite == "ACCEPTE") 
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"ok\" WHERE p_num=$numero_int" ;
			$ReqLog1 = requete_mysql($sql1, $mysql_link, "traite_demandes", $DEBUG);

			/* UPDATE table "conges_solde_user" (jours restants) */
			// on retranche les jours seulement pour des conges pris (pas pour les absences)
			// donc seulement si le type de l'absence qu'on annule est un "conges"
			if($tab_tout_type_abs[$value_type_abs_id]['type']=="conges")
				$sql2 = "UPDATE conges_solde_user SET su_solde = su_solde-$user_nb_jours_pris_float WHERE su_login='$user_login' AND su_abs_id=$value_type_abs_id " ;
			//echo($sql2."<br>");
			$ReqLog2 = requete_mysql($sql2, $mysql_link, "traite_demandes", $DEBUG);
			
			//envoi d'un mail d'alerte au user (si demandé dans config.php)
			if($_SESSION['config']['mail_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, "valid_conges");
		}
		else 
		{
			if($value_traite == "REFUSE") 
			{
				// recup di motif de refus
				$motif_refus=$tab_text_refus[$numero_int];
				//$sql3 = "UPDATE conges_periode SET p_etat=\"refus\" WHERE p_num=$numero_int" ;
				$sql3 = "UPDATE conges_periode SET p_etat=\"refus\", p_motif_refus='$motif_refus' WHERE p_num=$numero_int" ;
				$ReqLog3 = requete_mysql($sql3, $mysql_link, "traite_demandes", $DEBUG);
			
				//envoi d'un mail d'alerte au user (si demandé dans config.php)
				if($_SESSION['config']['mail_refus_conges_alerte_user']==TRUE)
					alerte_mail($_SESSION['userlogin'], $user_login, "refus_conges");
			}
		}
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<input type=\"submit\" value=\"OK\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo " Changements pris en compte avec succes !<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";
	}

}

function new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type_id, $mysql_link, $DEBUG)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	// verif validité des valeurs saisies
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
			echo " Changements pris en compte avec succes !<br><br> \n";
		else
			echo " ERREUR ! Changements NON pris en compte !<br><br> \n";
	}
	else
	{
			echo " ERREUR ! Les valeurs saisies sont invalides ou manquantes  !!!<br><br> \n";
	}

	/* APPEL D'UNE AUTRE PAGE */
	echo "<form action=\"$PHP_SELF?session=$session&user_login=$user_login\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"Retour\">\n";
	echo "</form> \n";
	
}


?>

</CENTER>
</body>
</html>
