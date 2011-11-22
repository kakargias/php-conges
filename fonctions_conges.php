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

// affichage du calendrier avec les case à cocher, du mois du début du congés 
//function  affiche_calendrier_saisie_date_debut($user_login, $year, $mois) {

// affichage du calendrier avec les case à cocher, du mois de fin du congés 
//function  affiche_calendrier_saisie_date_fin($user_login, $year, $mois) {

// affichage du calendrier du mois avec les case à cocher sur les jour d'absence 
//function  affiche_calendrier_saisie_jour_absence($user_login, $year, $mois) {

// affichage du calendrier du mois avec les case à cocher sur les jour de présence
//function  affiche_calendrier_saisie_jour_presence($user_login, $year, $mois) {

// saisie des jours d'abscence ARTT ou temps partiel:
//function saisie_jours_absence_temps_partiel($login, $mysql_link)

// retourne le nom du jour de la semaine en francais sur 2 caracteres
//function get_j_name_fr_2c($timestamp)

//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
//function saisie_nouveau_conges($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

//affiche le formulaire d'échange d'un jour de rtt-temps partiel / jour travaillé
//function saisie_echange_rtt($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

// initialisation des variables pour la mavigation mois précédent / mois suivant
// certains arguments sont passés par référence (avec &) car on change leur valeur
//function init_var_navigation_mois_year($mois_calendrier_saisie_debut, $mois_calendrier_saisie_fin, &$mois_calendrier_saisie_debut_prec, &$year_calendrier_saisie_debut_prec, &$mois_calendrier_saisie_fin_suiv, &$year_calendrier_saisie_fin_suiv )

// affiche une chaine représentant un decimal sans 0 à la fin ... 
// (un point separe les unité et les décimales et on ne considère que 2 décimales !!!)
// ex : 10.00 devient 10  , 5.50 devient 5.5  , et 3.05 reste 3.05
//function affiche_decimal($str)

// verif validité des valeurs saisies lors d'une demande de conges par un user ou d'une saisie de conges par le responsable
//function verif_saisie_new_demande($new_debut, $new_fin, $new_nb_jours, $new_comment)

// renvoit la couleur de fond du jour indiqué par le timestamp
// (une couleur pour les jours de semaine et une pour les jours de week end)
//function get_bgcolor_of_the_day_in_the_week($timestamp_du_jour)

// affichage URL de-connexion
//function   bouton_deconnexion()

// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
//function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem)

// verif validité d'un nombre saisi (decimal ou non)
//  (attention : le $nombre est passé par référence car on le modifie si besoin)
//function verif_saisie_decimal(&$nombre)

//#################################################################################################


// affichage du calendrier avec les case à cocher, du mois du début du congés 
function  affiche_calendrier_saisie_date_debut($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;

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
	echo "	<tr align=\"center\" bgcolor=\"$config_light_grey_bgcolor\">\n";
	echo "		<td class=\"cal-saisie\">L</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">J</td><td class=\"cal-saisie\">V</td><td class=\"cal-saisie\">S</td><td class=\"cal-saisie\">D</td>\n";
	echo "	</tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$j,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$j<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$j\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		if(($i==14-$first_jour_mois_rang) || ($i==15-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		if(($i==21-$first_jour_mois_rang) || ($i==22-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		if(($i==28-$first_jour_mois_rang) || ($i==29-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}



// affichage du calendrier avec les case à cocher, du mois de fin du congés 
function  affiche_calendrier_saisie_date_fin($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;


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
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\">\n" ;
	echo "		<td class=\"cal-saisie\">L</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">J</td><td class=\"cal-saisie\">V</td><td class=\"cal-saisie\">S</td><td class=\"cal-saisie\">D</td>\n" ;
	echo "	</tr>\n" ;
	
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$j,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$j<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$j\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		if(($i==14-$first_jour_mois_rang) || ($i==15-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		if(($i==21-$first_jour_mois_rang) || ($i==22-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		if(($i==28-$first_jour_mois_rang) || ($i==29-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne)*/
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y"))
				$bgcolor=$config_temps_partiel_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
		}
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";	
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}




// affichage du calendrier du mois avec les case à cocher sur les jour d'absence 
function  affiche_calendrier_saisie_jour_absence($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;

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
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\">\n";
	echo "		<td class=\"cal-saisie\">L</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">J</td><td class=\"cal-saisie\">V</td><td class=\"cal-saisie\">S</td><td class=\"cal-saisie\">D</td>\n";
	echo "	</tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		$val_matin="";
		$val_aprem="";
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
		if(($val_matin=="Y")||($val_aprem=="Y"))
		{
			$bgcolor=$config_temps_partiel_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$j<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$j\"></td>";
		}
		else
		{
			if(($i==6) || ($i==7))
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$j</td>";
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
		if(($val_matin=="Y")||($val_aprem=="Y"))
		{
			$bgcolor=$config_temps_partiel_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
		}
		else
		{
			if(($i==14-$first_jour_mois_rang) || ($i==15-$first_jour_mois_rang))
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
		if(($val_matin=="Y")||($val_aprem=="Y"))
		{
			$bgcolor=$config_temps_partiel_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
		}
		else
		{
			if(($i==21-$first_jour_mois_rang) || ($i==22-$first_jour_mois_rang))
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
		if(($val_matin=="Y")||($val_aprem=="Y"))
		{
			$bgcolor=$config_temps_partiel_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
		}
		else
		{
			if(($i==28-$first_jour_mois_rang) || ($i==29-$first_jour_mois_rang))
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		$val_matin="";
		$val_aprem="";
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
		if(($val_matin=="Y")||($val_aprem=="Y"))
		{
			$bgcolor=$config_temps_partiel_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
		}
		else
		{
			if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		$val_matin="";
		$val_aprem="";
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
		if(($val_matin=="Y")||($val_aprem=="Y"))
		{
			$bgcolor=$config_temps_partiel_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_debut\" value=\"$year-$mois-$i\"></td>";
		}
		else
		{
			if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}



// affichage du calendrier du mois avec les case à cocher sur les jour de présence
function  affiche_calendrier_saisie_jour_presence($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;


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
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\">\n";
	echo "		<td class=\"cal-saisie\">L</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">M</td><td class=\"cal-saisie\">J</td><td class=\"cal-saisie\">V</td><td class=\"cal-saisie\">S</td><td class=\"cal-saisie\">D</td>\n";
	echo "	</tr>\n" ;
	
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if(($i==6) || ($i==7))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		$val_matin="";
		$val_aprem="";
		if(($i==6) || ($i==7))
		{
			$bgcolor=$config_week_end_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$j</td>";
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$j,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y")) {
				$bgcolor=$config_temps_partiel_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$j</td>";
			}
			else {
				$bgcolor=$config_semaine_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$j\"></td>";
			}
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		if(($i==14-$first_jour_mois_rang) || ($i==15-$first_jour_mois_rang))
		{
			$bgcolor=$config_week_end_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y")) {
				$bgcolor=$config_temps_partiel_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
			}
			else {
				$bgcolor=$config_semaine_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
			}
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		if(($i==21-$first_jour_mois_rang) || ($i==22-$first_jour_mois_rang))
		{
			$bgcolor=$config_week_end_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y")) {
				$bgcolor=$config_temps_partiel_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
			}
			else {
				$bgcolor=$config_semaine_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
			}
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		if(($i==28-$first_jour_mois_rang) || ($i==29-$first_jour_mois_rang))
		{
			$bgcolor=$config_week_end_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y")) {
				$bgcolor=$config_temps_partiel_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
			}
			else {
				$bgcolor=$config_semaine_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
			}
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne)*/
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		$val_matin="";
		$val_aprem="";
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
		{
			$bgcolor=$config_week_end_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y")) {
				$bgcolor=$config_temps_partiel_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
			}
			else {
				$bgcolor=$config_semaine_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
			}
		}
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if(($i==35-$first_jour_mois_rang) || ($i==36-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		$val_matin="";
		$val_aprem="";
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
		{
			$bgcolor=$config_week_end_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem);
			if(($val_matin=="Y")||($val_aprem=="Y")) {
				$bgcolor=$config_temps_partiel_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
			}
			else {
				$bgcolor=$config_semaine_bgcolor;
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$i\"></td>";
			}
		}
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if(($i==42-$first_jour_mois_rang) || ($i==43-$first_jour_mois_rang))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}



// saisie des jours d'abscence ARTT ou temps partiel:
function saisie_jours_absence_temps_partiel($login, $mysql_link)
{

	/* initialisation des variables **************/
	$checked_option_sem_imp_lu_am="";
	$checked_option_sem_imp_lu_pm="";
	$checked_option_sem_imp_ma_am="";
	$checked_option_sem_imp_ma_pm="";
	$checked_option_sem_imp_me_am="";
	$checked_option_sem_imp_me_pm="";
	$checked_option_sem_imp_je_am="";
	$checked_option_sem_imp_je_pm="";
	$checked_option_sem_imp_ve_am="";
	$checked_option_sem_imp_ve_pm="";
		
	$checked_option_sem_p_lu_am="";
	$checked_option_sem_p_lu_pm="";
	$checked_option_sem_p_ma_am="";
	$checked_option_sem_p_ma_pm="";
	$checked_option_sem_p_me_am="";
	$checked_option_sem_p_me_pm="";
	$checked_option_sem_p_je_am="";
	$checked_option_sem_p_je_pm="";
	$checked_option_sem_p_ve_am="";
	$checked_option_sem_p_ve_pm="";
	/*********************************************/
	
	$sql1 = "SELECT * FROM conges_artt WHERE a_login='$login' "  ;
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : saisie_jours_absence_temps_partiel() : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		if($resultat1['sem_imp_lu_am']=="Y") $checked_option_sem_imp_lu_am=" checked";
		if($resultat1['sem_imp_lu_pm']=="Y") $checked_option_sem_imp_lu_pm=" checked";
		if($resultat1['sem_imp_ma_am']=="Y") $checked_option_sem_imp_ma_am=" checked";
		if($resultat1['sem_imp_ma_pm']=="Y") $checked_option_sem_imp_ma_pm=" checked";
		if($resultat1['sem_imp_me_am']=="Y") $checked_option_sem_imp_me_am=" checked";
		if($resultat1['sem_imp_me_pm']=="Y") $checked_option_sem_imp_me_pm=" checked";
		if($resultat1['sem_imp_je_am']=="Y") $checked_option_sem_imp_je_am=" checked";
		if($resultat1['sem_imp_je_pm']=="Y") $checked_option_sem_imp_je_pm=" checked";
		if($resultat1['sem_imp_ve_am']=="Y") $checked_option_sem_imp_ve_am=" checked";
		if($resultat1['sem_imp_ve_pm']=="Y") $checked_option_sem_imp_ve_pm=" checked";
		
		if($resultat1['sem_p_lu_am']=="Y") $checked_option_sem_p_lu_am=" checked";
		if($resultat1['sem_p_lu_pm']=="Y") $checked_option_sem_p_lu_pm=" checked";
		if($resultat1['sem_p_ma_am']=="Y") $checked_option_sem_p_ma_am=" checked";
		if($resultat1['sem_p_ma_pm']=="Y") $checked_option_sem_p_ma_pm=" checked";
		if($resultat1['sem_p_me_am']=="Y") $checked_option_sem_p_me_am=" checked";
		if($resultat1['sem_p_me_pm']=="Y") $checked_option_sem_p_me_pm=" checked";
		if($resultat1['sem_p_je_am']=="Y") $checked_option_sem_p_je_am=" checked";
		if($resultat1['sem_p_je_pm']=="Y") $checked_option_sem_p_je_pm=" checked";
		if($resultat1['sem_p_ve_am']=="Y") $checked_option_sem_p_ve_am=" checked";
		if($resultat1['sem_p_ve_pm']=="Y") $checked_option_sem_p_ve_pm=" checked";
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
		
//		echo "<table cellpadding=\"1\" cellspacing=\"1\" border=\"1\">\n";
		echo "<table cellpadding=\"1\" class=\"tablo\">\n";
		echo "<tr align=\"center\"><td></td><td class=\"histo\">Lundi</td><td class=\"histo\">Mardi</td><td class=\"histo\">Mercredi</td><td class=\"histo\">Jeudi</td><td class=\"histo\">Vendredi</td></tr>\n";
		printf("<tr align=\"center\"><td class=\"histo\">matin</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td></tr>\n",
						$lu_am,    $ma_am,    $me_am,    $je_am,    $ve_am);
		printf("<tr align=\"center\"><td class=\"histo\">apres-midi</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td></tr>\n",
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
		
		echo "<table cellpadding=\"1\"  class=\"tablo\">\n";
		echo "<tr align=\"center\"><td></td><td class=\"histo\">Lundi</td><td class=\"histo\">Mardi</td><td class=\"histo\">Mercredi</td><td class=\"histo\">Jeudi</td><td class=\"histo\">Vendredi</td></tr>\n";
		printf("<tr align=\"center\"><td class=\"histo\">matin</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td></tr>\n",
						$lu_am,    $ma_am,    $me_am,    $je_am,    $ve_am);
		printf("<tr align=\"center\"><td class=\"histo\">apres-midi</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td></tr>\n",
							$lu_pm,    $ma_pm,    $me_pm,    $je_pm,    $ve_pm);
		echo "</table>\n";
	echo "</td></tr>\n";
	echo "</table>\n";

}


// retourne le nom du jour de la semaine en francais sur 2 caracteres
function get_j_name_fr_2c($timestamp)
{
	setlocale (LC_TIME, "en");
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
	global $config_user_saisie_demande, $config_user_saisie_mission, $config_resp_saisie_mission, $config_rtt_comme_conges;
	global $onglet;
	
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;

			echo "<table cellpadding=\"0\" cellspacing=\"5\" border=\"0\">\n";
			echo "<tr align=\"center\">\n";
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
								$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec, 
								$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
								$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
								$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
								$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
				echo "<td align=\"center\" class=\"big\">Date Début :</td>\n";
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date debut ***/
				affiche_calendrier_saisie_date_debut($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);  
			echo "</td>\n";
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
					if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
				echo "<td align=\"center\" class=\"big\">Date Fin :</td>\n";
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date fin ***/
				affiche_calendrier_saisie_date_fin($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
			echo "</td>\n";
			echo "<td>\n";
			
				/***  formulaire ***/
				echo "<table cellpadding=\"0\" cellspacing=\"2\" border=\"0\" >\n";
				echo "<tr>\n";
				echo "<td valign=\"top\">\n";
					printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n");
					printf("<tr align=\"center\"><td><b>NB_Jours_Pris</b></td><td><b>Commentaire</b></td></tr>\n");

//					$text_nb_jours="<input type=\"text\" name=\"new_nb_jours\" size=\"10\" maxlength=\"30\" value=\"".$nb_jours."\">" ;
//					$text_commentaire="<input type=\"text\" name=\"new_comment\" size=\"25\" maxlength=\"30\" value=\"".$comment."\">" ;
					$text_nb_jours="<input type=\"text\" name=\"new_nb_jours\" size=\"10\" maxlength=\"30\" value=\"\">" ;
					$text_commentaire="<input type=\"text\" name=\"new_comment\" size=\"25\" maxlength=\"30\" value=\"\">" ;
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
					// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
					// OU si le user n'apas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
					if( (($config_user_saisie_demande==1)&&($user_login==$session_username)) ||
					    (($config_user_saisie_demande==0)&&($user_login!=$session_username)) )
					{
						echo "<input type=\"radio\" name=\"new_etat\" value=\"conges\" checked> congés<br>\n";
						if($config_rtt_comme_conges==1) // si on gère les rtt comme des congés
							echo "<input type=\"radio\" name=\"new_etat\" value=\"rtt\"> rtt<br>\n";
					}
					// si le user a droit de saisir une mission ET si on est PAS dans une fenetre de responsable
					// OU si le resp a droit de saisir une mission ET si on est dans une fenetre de responsable
					if( (($config_user_saisie_mission==1)&&($user_login==$session_username)) ||
					    (($config_resp_saisie_mission==1)&&($user_login!=$session_username)) )
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


//affiche le formulaire d'échange d'un jour de rtt-temps partiel / jour travaillé
function saisie_echange_rtt($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)
{
	global $PHP_SELF;
	global $session, $session_username;
	global $config_user_saisie_demande, $config_user_saisie_mission, $config_resp_saisie_mission;
	global $link , $onglet;
	
		printf("<form action=\"$PHP_SELF?session=$session&&onglet=$onglet\" method=\"POST\">\n" ) ;

			echo "<table cellpadding=\"0\" cellspacing=\"5\" border=\"0\">\n";
			echo "<tr align=\"center\">\n";
			// cellule 1 : calendrier de saisie du jour d'absence
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
								$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec, 
								$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
								$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
								$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
								$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
				echo "<td align=\"center\" class=\"big\">Jour d'absence ordinaire :</td>\n";
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date debut ***/
				affiche_calendrier_saisie_jour_absence($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);  
			echo "</td>\n";
			// cellule 2 : boutons radio 1/2 journée ou jour complet
			echo "<td>\n";
				echo "<input type=\"radio\" name=\"moment_absence_ordinaire\" value=\"M\"><b><u>matin</u></b><input type=\"radio\" name=\"moment_absence_souhaitee\" value=\"M\"><br><br>\n";
				echo "<input type=\"radio\" name=\"moment_absence_ordinaire\" value=\"A\"><b><u>après midi</u></b><input type=\"radio\" name=\"moment_absence_souhaitee\" value=\"A\"><br><br>\n";
				echo "<input type=\"radio\" name=\"moment_absence_ordinaire\" value=\"J\" checked><b><u>journée complète</u></b><input type=\"radio\" name=\"moment_absence_souhaitee\" value=\"J\" checked><br>\n";
			echo "</td>\n";
			// cellule 2 : calendrier de saisie du jour d'absence
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
					if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
				echo "<td align=\"center\" class=\"big\">Jour d'absence souhaité :</td>\n";
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date fin ***/
				affiche_calendrier_saisie_jour_presence($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
			echo "</td>\n";
			// cellule 3 : champs texte et boutons (valider/cancel)
			echo "<td>\n";
			
				/***  formulaire ***/
					printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n");
					printf("<tr align=\"center\"><td><b>Commentaire</b></td></tr>\n");

					$text_commentaire="<input type=\"text\" name=\"new_comment\" size=\"25\" maxlength=\"30\" value=\"".$comment."\">" ;
					printf("<tr align=\"center\">\n");
					printf("<td>%s</td>\n", $text_commentaire);
					printf("</tr>\n");
					printf("<tr align=\"center\"><td><img src=\"../img/shim.gif\" width=\"15\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td></tr>\n");
					printf("<tr align=\"center\">\n");
					printf("<td>\n");
						printf("<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n");
						printf("<input type=\"hidden\" name=\"new_echange_rtt\" value=1>\n");
						printf("<input type=\"submit\" value=\"Valider\">   <input value=\"cancel\" type=\"reset\">\n");
					printf("</td>\n");
					printf("</tr>\n");
					printf("</table>\n");
				
				
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

		printf("</form>\n" ) ;
}


// initialisation des variables pour la mavigation mois précédent / mois suivant
// certains arguments sont passés par référence (avec &) car on change leur valeur
function init_var_navigation_mois_year(	$mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
								&$mois_calendrier_saisie_debut_prec, &$year_calendrier_saisie_debut_prec, 
								&$mois_calendrier_saisie_debut_suiv, &$year_calendrier_saisie_debut_suiv,
								$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
								&$mois_calendrier_saisie_fin_prec, &$year_calendrier_saisie_fin_prec,
								&$mois_calendrier_saisie_fin_suiv, &$year_calendrier_saisie_fin_suiv )
{
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
}


// affiche une chaine représentant un decimal sans 0 à la fin ... 
// (un point separe les unités et les decimales et on ne considere que 2 decimales !!!)
// ex : 10.00 devient 10  , 5.50 devient 5.5  , et 3.05 reste 3.05
function affiche_decimal($str)
{
	$champs=explode(".", $str);
	$int=$champs[0];
	$decimal="00";
	if (count($champs)>1) 
		$decimal = $champs[1];
	//$decimal=$champs[1];
	
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


// verif validité des valeurs saisies lors d'une demande de congespar un user ou d'une saisie de conges par le responsable
//  (attention : le $new_nb_jours est passé par référence car on le modifie si besoin)
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
		echo "<br>ERREUR : mauvaise saise : <b>la date de fin ($new_fin) est anterieure a la date de début ($new_debut) !!!</b><br>\n";
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


//
// affichage URL de-connexion
//
function   bouton_deconnexion()
{
   global   $session;
      
//		printf("<form action=\"../deconnexion.php?session=$session\" method=\"POST\" target=_top>\n" ) ;
//		printf("<input type=\"submit\" value=\"* DECONNEXION *\" class=\"bouton-alerte\">\n");
//		printf("</form>\n" ) ;
	echo "<a href=\"../deconnexion.php?session=$session\" target=\"_top\"><img src=\"../img/exit.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Déconnexion\" alt=\"Déconnexion\"></a> Déconnexion\n";


}




// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem)
{
global $link;
global $config_user_echange_rtt ;

	$num_semaine=strftime("%W", $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres
	if(($jour_name_fr_2c!="sa")&&($jour_name_fr_2c!="di")) {  // on ne cherche pas d'artt les samedis ou dimanches
		// verif si le jour fait l'objet d'un echange ....
		$date_j=date("Y-m-d", $j_timestamp);
		$sql_echange_rtt="SELECT e_absence FROM conges_echange_rtt WHERE e_login='$sql_login' AND e_date_jour='$date_j' ";
		$res_echange_rtt = mysql_query($sql_echange_rtt, $link) or die("ERREUR : recup_infos_artt_du_jour() <br>\n$sql_echange_rtt<br>\n".mysql_error());
		$num_echange_rtt = mysql_num_rows($res_echange_rtt);
		// si le jour est l'objet d'un echange, on tient compte de l'échange
		if($num_echange_rtt!=0)
		{
			$result_echange_rtt = mysql_fetch_array($res_echange_rtt);
			if ($result_echange_rtt["e_absence"]=='J') // jour entier
			{
				$val_matin='Y';
				$val_aprem='Y';
			}
			elseif ($result_echange_rtt["e_absence"]=='M') // matin
			{
				$val_matin='Y';
				$val_aprem='N';
			}
			elseif ($result_echange_rtt["e_absence"]=='A') // apres-midi
			{
				$val_matin='N';
				$val_aprem='Y';
			}
			else
			{
				$val_matin='N';
				$val_aprem='N';
			}
		}
		// sinon, on lit la table conges_artt normalement
		else
		{
			$parite_semaine=($num_semaine % 2);   //(modulo) =1 si sem impaire, =o si sem paire 
			if ($parite_semaine==0) $par_sem="p"; else $par_sem="imp" ;

			//on calcule la key du tableau $result_artt qui correspond au jour j que l'on est en train d'afficher
			$key_artt_matin="sem_".$par_sem."_".$jour_name_fr_2c."_am" ;
			$key_artt_aprem="sem_".$par_sem."_".$jour_name_fr_2c."_pm" ;

			// recup des ARTT et temps-partiels du user
			$sql_artt="SELECT $key_artt_matin, $key_artt_aprem FROM conges_artt WHERE a_login='$sql_login' ";
			$res_artt = mysql_query($sql_artt, $link) or die("ERREUR : recup_infos_artt_du_jour() <br>\n $sql_artt<br>\n".mysql_error());
			$result_artt = mysql_fetch_array($res_artt);
			if($result_artt["$key_artt_matin"]=='Y')
				$val_matin='Y';
			else
				$val_matin='N';
			if($result_artt["$key_artt_aprem"]=='Y')
				$val_aprem='Y';
			else
				$val_aprem='N';
		}
	}
	else {
	}
}



// verif validité d'un nombre saisi (decimal ou non)
//  (attention : le $nombre est passé par référence car on le modifie si besoin)
function verif_saisie_decimal(&$nombre)
{
	$verif=TRUE ;
	
	if ( !ereg( "([0-9]+)([\.\,]*[0-9]{1,2})*", $nombre) ) {
		echo "<br>ERREUR : mauvaise saise : <b>le nombre saisi est invalide</b><br>\n";
		$verif=FALSE ;
	}
	else {
		if( ereg( "([0-9]+)\,([0-9]{1,2})", $nombre, $reg) )
			$nombre=$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les décimaux
	}
	
	return $verif;
}


?>
