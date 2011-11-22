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



//#################################################################################################

//function  affiche_calendrier_saisie_date_debut($year, $mois) {

//function  affiche_calendrier_saisie_date_fin($year, $mois) {

// saisie des jours d'abscence ARTT ou temps partiel:
//function saisie_jours_absence_temps_partiel($login, $mysql_link)

// retourne le nom du jour de la semaine en francais sur 2 caracteres
//function get_j_name_fr_2c($timestamp)

//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
//function saisie_nouveau_conges($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

// affiche une chaine représentant un decimal sans 0 à la fin ... 
// (un point separe les unité et les décimales et on ne considère que 2 décimales !!!)
// ex : 10.00 devient 10  , 5.50 devient 5.5  , et 3.05 reste 3.05
//function affiche_decimal($str)

// verif validité des valeurs saisies lors d'une demande de congespar un user ou d'une saisie de conges par le responsable
//function verif_saisie_new_demande($new_debut, $new_fin, $new_nb_jours, $new_comment)

// renvoit la couleur de fond du jour indiqué par le timestamp
// (une couleur pour les jours de semaine et une pour les jours de week end)
//function get_bgcolor_of_the_day_in_the_week($timestamp_du_jour)

//#################################################################################################


function  affiche_calendrier_saisie_date_debut($year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;


	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
	$mois_name=date("F", $first_jour_mois_timestamp);
	//$first_jour_mois_name=date("D", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)
		
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"250\" bgcolor=$config_semaine_bgcolor>\n";
	/* affichage  2 premieres lignes */
	echo "	<tr align=\"center\" bgcolor=\"$config_light_grey_bgcolor\"><td colspan=7 class=\"titre\"> $mois_name $year </td></tr>\n" ;
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\"><td>L</td><td>M</td><td>M</td><td>J</td><td>V</td><td>S</td><td>D</td></tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>-</td>";
	}
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$j<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$j\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		if(($i==14-$first_jour_mois_rang) || ($i==15-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		if(($i==21-$first_jour_mois_rang) || ($i==22-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		if(($i==28-$first_jour_mois_rang) || ($i==29-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}



function  affiche_calendrier_saisie_date_fin($year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;


	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
	$mois_name=date("F", $first_jour_mois_timestamp);
	//$first_jour_mois_name=date("D", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)
		
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"250\" bgcolor=\"$config_semaine_bgcolor\">\n";
	/* affichage  2 premieres lignes */
	echo "	<tr align=\"center\" bgcolor=\"$config_light_grey_bgcolor\"><td colspan=7 class=\"titre\"> $mois_name $year </td></tr>\n" ;
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\"><td>L</td><td>M</td><td>M</td><td>J</td><td>V</td><td>S</td><td>D</td></tr>\n" ;
	
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>-</td>";
	}
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$j<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$j\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		if(($i==14-$first_jour_mois_rang) || ($i==15-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		if(($i==21-$first_jour_mois_rang) || ($i==22-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		if(($i==28-$first_jour_mois_rang) || ($i==29-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne)*/
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor>-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}


// saisie des jours d'abscence ARTT ou temps partiel:
function saisie_jours_absence_temps_partiel($login, $mysql_link)
{
	$sql1 = "SELECT * FROM conges_artt WHERE a_login='$login' "  ;
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : saisie_jours_absence_temps_partiel() : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		if($resultat1['sem_imp_lu_am']=="Y") $checked_option_sem_imp_lu_am=" checked"; else $checked_option_sem_imp_lu_am="";
		if($resultat1['sem_imp_lu_pm']=="Y") $checked_option_sem_imp_lu_pm=" checked"; else $checked_option_sem_imp_lu_pm="";
		if($resultat1['sem_imp_ma_am']=="Y") $checked_option_sem_imp_ma_am=" checked"; else $checked_option_sem_imp_ma_am="";
		if($resultat1['sem_imp_ma_pm']=="Y") $checked_option_sem_imp_ma_pm=" checked"; else $checked_option_sem_imp_ma_pm="";
		if($resultat1['sem_imp_me_am']=="Y") $checked_option_sem_imp_me_am=" checked"; else $checked_option_sem_imp_me_am="";
		if($resultat1['sem_imp_me_pm']=="Y") $checked_option_sem_imp_me_pm=" checked"; else $checked_option_sem_imp_me_pm="";
		if($resultat1['sem_imp_je_am']=="Y") $checked_option_sem_imp_je_am=" checked"; else $checked_option_sem_imp_je_am="";
		if($resultat1['sem_imp_je_pm']=="Y") $checked_option_sem_imp_je_pm=" checked"; else $checked_option_sem_imp_je_pm="";
		if($resultat1['sem_imp_ve_am']=="Y") $checked_option_sem_imp_ve_am=" checked"; else $checked_option_sem_imp_ve_am="";
		if($resultat1['sem_imp_ve_pm']=="Y") $checked_option_sem_imp_ve_pm=" checked"; else $checked_option_sem_imp_ve_pm="";
		
		if($resultat1['sem_p_lu_am']=="Y") $checked_option_sem_p_lu_am=" checked"; else $checked_option_sem_p_lu_am="";
		if($resultat1['sem_p_lu_pm']=="Y") $checked_option_sem_p_lu_pm=" checked"; else $checked_option_sem_p_lu_pm="";
		if($resultat1['sem_p_ma_am']=="Y") $checked_option_sem_p_ma_am=" checked"; else $checked_option_sem_p_ma_am="";
		if($resultat1['sem_p_ma_pm']=="Y") $checked_option_sem_p_ma_pm=" checked"; else $checked_option_sem_p_ma_pm="";
		if($resultat1['sem_p_me_am']=="Y") $checked_option_sem_p_me_am=" checked"; else $checked_option_sem_p_me_am="";
		if($resultat1['sem_p_me_pm']=="Y") $checked_option_sem_p_me_pm=" checked"; else $checked_option_sem_p_me_pm="";
		if($resultat1['sem_p_je_am']=="Y") $checked_option_sem_p_je_am=" checked"; else $checked_option_sem_p_je_am="";
		if($resultat1['sem_p_je_pm']=="Y") $checked_option_sem_p_je_pm=" checked"; else $checked_option_sem_p_je_pm="";
		if($resultat1['sem_p_ve_am']=="Y") $checked_option_sem_p_ve_am=" checked"; else $checked_option_sem_p_ve_am="";
		if($resultat1['sem_p_ve_pm']=="Y") $checked_option_sem_p_ve_pm=" checked"; else $checked_option_sem_p_ve_pm="";
	}

	
	echo "<h4>saisie des jours d'abscence pour ARTT ou temps partiel :</h4>\n";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr><td>\n";
		//tableau semaines impaires
		echo "<b><u>semaines Impaires:</u></b><br>\n";
		$tab_checkbox_sem_imp=array();
		$lu_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_lu_am]\" value=\"Y\" $checked_option_sem_imp_lu_am>";
		$lu_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_lu_pm]\" value=\"Y\" $checked_option_sem_imp_lu_pm>";
		$ma_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ma_am]\" value=\"Y\" $checked_option_sem_imp_ma_am>";
		$ma_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ma_pm]\" value=\"Y\" $checked_option_sem_imp_ma_pm>";
		$me_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_me_am]\" value=\"Y\" $checked_option_sem_imp_me_am>";
		$me_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_me_pm]\" value=\"Y\" $checked_option_sem_imp_me_pm>";
		$je_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_je_am]\" value=\"Y\" $checked_option_sem_imp_je_am>";
		$je_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_je_pm]\" value=\"Y\" $checked_option_sem_imp_je_pm>";
		$ve_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ve_am]\" value=\"Y\" $checked_option_sem_imp_ve_am>";
		$ve_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ve_pm]\" value=\"Y\" $checked_option_sem_imp_ve_pm>";
		
		echo "<table cellpadding=\"1\" cellspacing=\"1\" border=\"1\">\n";
		echo "<tr align=\"center\"><td></td><td>Lundi</td><td>Mardi</td><td>Mercredi</td><td>Jeudi</td><td>Vendredi</td></tr>\n";
		printf("<tr align=\"center\"><td>matin</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
						$lu_am,    $ma_am,    $me_am,    $je_am,    $ve_am);
		printf("<tr align=\"center\"><td>apres-midi</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
							$lu_pm,    $ma_pm,    $me_pm,    $je_pm,    $ve_pm);
		echo "</table>\n";
		
	echo "</td>\n";
	echo " <td><img src=\"../img/shim.gif\" width=\"15\" height=\"2\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
	echo " <td>\n";

		//tableau semaines paires
		echo "<b><u>semaines Paires:</u></b><br>\n";
		$tab_checkbox_sem_p=array();
		$lu_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_lu_am]\" value=\"Y\" $checked_option_sem_p_lu_am>";
		$lu_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_lu_pm]\" value=\"Y\" $checked_option_sem_p_lu_pm>";
		$ma_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ma_am]\" value=\"Y\" $checked_option_sem_p_ma_am>";
		$ma_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ma_pm]\" value=\"Y\" $checked_option_sem_p_ma_pm>";
		$me_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_me_am]\" value=\"Y\" $checked_option_sem_p_me_am>";
		$me_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_me_pm]\" value=\"Y\" $checked_option_sem_p_me_pm>";
		$je_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_je_am]\" value=\"Y\" $checked_option_sem_p_je_am>";
		$je_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_je_pm]\" value=\"Y\" $checked_option_sem_p_je_pm>";
		$ve_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ve_am]\" value=\"Y\" $checked_option_sem_p_ve_am>";
		$ve_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ve_pm]\" value=\"Y\" $checked_option_sem_p_ve_pm>";
		
		echo "<table cellpadding=\"1\" cellspacing=\"1\" border=\"1\" align=\"center\">\n";
		echo "<tr align=\"center\"><td></td><td>Lundi</td><td>Mardi</td><td>Mercredi</td><td>Jeudi</td><td>Vendredi</td></tr>\n";
		printf("<tr align=\"center\"><td>matin</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
						$lu_am,    $ma_am,    $me_am,    $je_am,    $ve_am);
		printf("<tr align=\"center\"><td>apres-midi</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
							$lu_pm,    $ma_pm,    $me_pm,    $je_pm,    $ve_pm);
		echo "</table>\n";
	echo "</td></tr>\n";
	echo "</table>\n";

}


// retourne le nom du jour de la semaine en francais sur 2 caracteres
function get_j_name_fr_2c($timestamp)
{
	setlocale (LC_TIME, "fr");
	$jour_name_fr_a=strftime("%a", $timestamp);
	switch($jour_name_fr_a) {
	 	case "Mon": 
			$jour_name_fr_2c="lu";
			break;
	 	case "Tue": 
			$jour_name_fr_2c="ma";
			break;
	 	case "Wed": 
			$jour_name_fr_2c="me";
			break;
	 	case "Thu": 
			$jour_name_fr_2c="je";
			break;
	 	case "Fri": 
			$jour_name_fr_2c="ve";
			break;
	 	case "Sat": 
			$jour_name_fr_2c="sa";
			break;
	 	case "Sun": 
			$jour_name_fr_2c="di";
			break;
		default:
			$jour_name_fr_2c=FALSE;
	}
	setlocale (LC_TIME, "fr");
	return $jour_name_fr_2c;
}



//affiche le formulaire de saisie d'une nouvelle demande de conges
function saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)
{
	global $PHP_SELF;
	global $session, $session_username;
	global $config_user_saisie_demande, $config_user_saisie_mission;
	
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;

			echo "<table cellpadding=\"0\" cellspacing=\"5\" border=\"0\">\n";
			echo "<tr align=\"center\">\n";
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					if($mois_calendrier_saisie_debut==1) {
						$mois_calendrier_saisie_debut_prec=12; 
						$year_calendrier_saisie_debut_prec=$year_calendrier_saisie_debut-1 ;
					}
					else {
						$mois_calendrier_saisie_debut_prec=$mois_calendrier_saisie_debut-1 ;
						$year_calendrier_saisie_debut_prec=$year_calendrier_saisie_debut ;
					}
					if($mois_calendrier_saisie_debut==12) {
						$mois_calendrier_saisie_debut_suiv=1; 
						$year_calendrier_saisie_debut_suiv=$year_calendrier_saisie_debut+1 ;
					}
					else {
						$mois_calendrier_saisie_debut_suiv=$mois_calendrier_saisie_debut+1 ;
						$year_calendrier_saisie_debut_suiv=$year_calendrier_saisie_debut ;
					}

					if($mois_calendrier_saisie_fin==1) {
						$mois_calendrier_saisie_fin_prec=12; 
						$year_calendrier_saisie_fin_prec=$year_calendrier_saisie_fin-1 ;
					}
					else {
						$mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
						$year_calendrier_saisie_fin_prec=$year_calendrier_saisie_fin ;
					}
					if($mois_calendrier_saisie_fin==12) {
						$mois_calendrier_saisie_fin_suiv=1; 
						$year_calendrier_saisie_fin_suiv=$year_calendrier_saisie_fin+1 ;
					}
					else {
						$mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;
						$year_calendrier_saisie_fin_suiv=$year_calendrier_saisie_fin ;
					}

				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login\"> << </a></td>\n";
				echo "<td align=\"center\" class=\"big\">Date Début :</td>\n";
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date debut ***/
				affiche_calendrier_saisie_date_debut($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);  
			echo "</td>\n";
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
					if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login\"> << </a></td>\n";
				echo "<td align=\"center\" class=\"big\">Date Fin :</td>\n";
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date fin ***/
				affiche_calendrier_saisie_date_fin($year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
			echo "</td>\n";
			echo "<td>\n";
			
				/***  formulaire ***/
				echo "<table cellpadding=\"0\" cellspacing=\"2\" border=\"0\" >\n";
				echo "<tr>\n";
				echo "<td valign=\"top\">\n";
					printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n");
					printf("<tr align=\"center\"><td><b>NB_Jours_Pris</b></td><td><b>Commentaire</b></td></tr>\n");

					$text_nb_jours="<input type=\"text\" name=\"new_nb_jours\" size=\"10\" maxlength=\"30\" value=\"".$nb_jours."\">" ;
					$text_commentaire="<input type=\"text\" name=\"new_comment\" size=\"25\" maxlength=\"30\" value=\"".$comment."\">" ;
					printf("<tr align=\"center\">\n");
					printf("<td>%s</td><td>%s</td>\n", $text_nb_jours, $text_commentaire);
					printf("</tr>\n");
					printf("<tr align=\"center\"><td><img src=\"../img/shim.gif\" width=\"15\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td><td></td></tr>\n");
					printf("<tr align=\"center\">\n");
					printf("<td colspan=2>\n");
						printf("<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n");
						printf("<input type=\"hidden\" name=\"new_demande_conges\" value=1>\n");
						printf("<input type=\"submit\" value=\"Valider\">   <input value=\"cancel\" type=\"reset\">\n");
					printf("</td>\n");
					printf("</tr>\n");
					printf("</table>\n");
				
				echo "</td>\n";
				echo "<td valign=\"top\">\n";
					// si le user a droit de saisir une demande de conges
					if($config_user_saisie_demande==1)
					{
					echo "<input type=\"radio\" name=\"new_etat\" value=\"conges\" checked> congés<br>\n";
					}
					// si le user a droit de saisir une mission ET si on est PAS dans une fenetre de responsable
					if(($config_user_saisie_mission==1)&&($user_login==$session_username))
					{
						echo "<input type=\"radio\" name=\"new_etat\" value=\"mission\"> mission<br>\n";
						echo "<input type=\"radio\" name=\"new_etat\" value=\"formation\"> formation<br>\n";
						echo "<input type=\"radio\" name=\"new_etat\" value=\"autre\"> autre<br>\n";
					}
				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

		printf("</form>\n" ) ;
}


// affiche une chaine reprï¿½entant un decimal sans 0 ï¿½la fin ... 
// (un point separe les unitï¿½ et les decimales et on ne considere que 2 decimales !!!)
// ex : 10.00 devient 10  , 5.50 devient 5.5  , et 3.05 reste 3.05
function affiche_decimal($str)
{
	$champs=explode(".", $str);
	$int=$champs[0];
	$decimal=$champs[1];
	
	if($decimal=="00")
		return $int ;
	elseif (ereg("([0-9])([1-9])", $decimal))
		return $str;
	elseif (ereg("([0-9])0", $decimal, $regs))
		return $int.".".$regs[1] ;
	else {
		echo "ERREUR: affiche_decimal($str) : $str n'a pas le format attendu !!!!<br>\n";
		exit;
	}
}


// verif validitï¿½des valeurs saisies lors d'une demande de congespar un user ou d'une saisie de conges par le responsable
//  (attention : le $new_nb_jours est passï¿½par rï¿½ï¿½ence car on le modifie si besoin)
function verif_saisie_new_demande($new_debut, $new_fin, &$new_nb_jours, $new_comment)
{
	$verif=TRUE ;
	
	// leur champs doivent etre renseignï¿½ dans le formulaire
	if( ($new_debut=="") || ($new_fin=="") || ($new_nb_jours=="") ) {
		echo "<br>ERREUR : mauvaise saise : valeurs <b>manquantes !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	if ( !ereg( "([0-9]+)([\.\,]*[0-9]{1,2})*", $new_nb_jours) ) {
		echo "<br>ERREUR : mauvaise saise : <b>le nombre de jours est invalide</b><br>\n";
		$verif=FALSE ;
	}
	else {
		if( ereg( "([0-9]+)\,([0-9]{1,2})", $new_nb_jours, $reg) )
			$new_nb_jours=$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les dï¿½imaux
	}
	
	if(strnatcmp($new_debut, $new_fin)>0) {
		echo "<br>ERREUR : mauvaise saise : <b>la date de fin ($new_fin) est anterieure a la date de dï¿½ut ($new_debut) !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	return $verif;
}


// renvoit la couleur de fond du jour indiquï¿½par le timestamp
// (une couleur pour les jours de semaine et une pour les jours de week end)
function get_bgcolor_of_the_day_in_the_week($timestamp_du_jour)
{
global $config_semaine_bgcolor, $config_week_end_bgcolor;

	$j_name=date("D", $timestamp_du_jour);
	if(($j_name=="Sat") || ($j_name=="Sun"))
		return $config_week_end_bgcolor;
	else
		return $config_semaine_bgcolor;

}


?>
