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



//#################################################################################################

// affichage du calendrier avec les case � cocher, du mois du d�but du cong�s 
//function  affiche_calendrier_saisie_date_debut($user_login, $year, $mois) {

// affichage du calendrier avec les case � cocher, du mois de fin du cong�s 
//function  affiche_calendrier_saisie_date_fin($user_login, $year, $mois) {

// affichage du calendrier du mois avec les case � cocher sur les jour d'absence 
//function  affiche_calendrier_saisie_jour_absence($user_login, $year, $mois) {

// affichage du calendrier du mois avec les case � cocher sur les jour de pr�sence
//function  affiche_calendrier_saisie_jour_presence($user_login, $year, $mois) {

// saisie des jours d'abscence ARTT ou temps partiel:
//function saisie_jours_absence_temps_partiel($login, $mysql_link)

// retourne le nom du jour de la semaine en francais sur 2 caracteres
//function get_j_name_fr_2c($timestamp)

//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
//function saisie_nouveau_conges($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

//affiche le formulaire d'�change d'un jour de rtt-temps partiel / jour travaill�
//function saisie_echange_rtt($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

// initialisation des variables pour la mavigation mois pr�c�dent / mois suivant
// certains arguments sont pass�s par r�f�rence (avec &) car on change leur valeur
//function init_var_navigation_mois_year($mois_calendrier_saisie_debut, $mois_calendrier_saisie_fin, &$mois_calendrier_saisie_debut_prec, &$year_calendrier_saisie_debut_prec, &$mois_calendrier_saisie_fin_suiv, &$year_calendrier_saisie_fin_suiv )

// affiche une chaine repr�sentant un decimal sans 0 � la fin ... 
// (un point separe les unit� et les d�cimales et on ne consid�re que 2 d�cimales !!!)
// ex : 10.00 devient 10  , 5.50 devient 5.5  , et 3.05 reste 3.05
//function affiche_decimal($str)

// verif validit� des valeurs saisies lors d'une demande de conges par un user ou d'une saisie de conges par le responsable
//function verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, &$new_nb_jours, $new_comment)

// renvoit la couleur de fond du jour indiqu� par le timestamp
// (une couleur pour les jours de semaine et une pour les jours de week end)
//function get_bgcolor_of_the_day_in_the_week($timestamp_du_jour)

// renvoit la class de cellule du jour indiqu�e par le timestamp
// (une classe pour les jours de semaine et une pour les jours de week end)
//function get_td_class_of_the_day_in_the_week($timestamp_du_jour)

// affichage URL de-connexion
//function   bouton_deconnexion()

// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont pass�es par r�f�rence (avec &) car on change leur valeur
//function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem)

// verif validit� d'un nombre saisi (decimal ou non)
//  (attention : le $nombre est pass� par r�f�rence car on le modifie si besoin)
//function verif_saisie_decimal(&$nombre)

// donne la date en francais (meme formats que la fonction PHP date() cf manuel php)
//function date_fr($format, $timestamp) 

// envoi d'un message d'avertissement au responsable (lors d'une nouvelle demande de conges)
//function alerte_resp_mail($nom_login)

// recuperation du mail d'un user
//function find_email_adress_for_user($login)

// recup des �changes de rtt de chaque jour du mois pour tous les users et stockage dans 1 tableau de tableaux 
// renvoit le tableau $tab_rtt_echange
//function recup_tableau_rtt_echange($mois, $first_jour, $year )

// recup dans un tableau des rtt planifi�es  pour tous les users 
// renvoit le tableau $tab_rtt_planifiees
// function recup_tableau_rtt_planifiees($mois, $first_jour, $year )


//#################################################################################################


// affichage du calendrier avec les case � cocher, du mois du d�but du cong�s 
function  affiche_calendrier_saisie_date_debut($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;

	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date("F", $first_jour_mois_timestamp);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
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
	// affichage des cellules cochables du 1 du mois � la fin de la ligne ...
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
			$bgcolor=$config_wecalendrier.phpek_end_bgcolor;
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



// affichage du calendrier avec les case � cocher, du mois de fin du cong�s 
function  affiche_calendrier_saisie_date_fin($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;


	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date("F", $first_jour_mois_timestamp);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
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
	// affichage des cellules cochables du 1 du mois � la fin de la ligne ...
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




// affichage du calendrier du mois avec les case � cocher sur les jour d'absence 
function  affiche_calendrier_saisie_jour_absence($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;

	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date("F", $first_jour_mois_timestamp);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
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
	// affichage des cellules cochables du 1 du mois � la fin de la ligne ...
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



// affichage du calendrier du mois avec les case � cocher sur les jour de pr�sence
function  affiche_calendrier_saisie_jour_presence($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $link ;


	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date("F", $first_jour_mois_timestamp);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
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
	// affichage des cellules cochables du 1 du mois � la fin de la ligne ...
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
				echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
				echo "<tr align=\"center\">\n";
					echo "<td>\n";
					echo "<fieldset class=\"cal_saisie\">\n";
						echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
						echo "<tr align=\"center\">\n";
							echo "<td>\n";
								// affichage du calendrier de saisie de la date de d�but de cong�s
								echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\" border=\"0\">\n";
								echo "<tr>\n";
									init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
												$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec, 
												$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
												$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
												$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
												$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

								// affichage des boutons de d�filement
								// recul du mois saisie d�but
								echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";

								echo "<td align=\"center\" class=\"big\">DEBUT :</td>\n";

								// affichage des boutons de d�filement
								// avance du mois saisie d�but
								// si le mois de saisie fin est ant�rieur ou �gal au mois de saisie d�but, on avance les 2 , sinon on avance que le mois de saisie d�but
								if( ($year_calendrier_saisie_debut_suiv>=$year_calendrier_saisie_fin) && ($mois_calendrier_saisie_debut_suiv>=$mois_calendrier_saisie_fin) )
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_debut_suiv&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
								else
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";

								echo "</tr>\n";
								echo "</table>\n";
								/*** calendrier saisie date debut ***/
								affiche_calendrier_saisie_date_debut($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);  
							echo "</td>\n";
							// cellule 2 : boutons radio matin ou apr�s midi
							echo "<td align=\"left\">\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_deb\" value=\"am\" checked><b><u>matin</u></b><br><br>\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_deb\" value=\"pm\"><b><u>apr�s midi</u></b><br><br>\n";
							echo "</td>\n";
						echo "</tr>\n";
						echo "</table>\n";
					echo "</fieldset>\n";
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr align=\"center\">\n";
					echo "<td><img src=\"../img/shim.gif\" width=\"15\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
				echo "</tr>\n";
				echo "<tr align=\"center\">\n";
					echo "<td>\n";
					echo "<fieldset class=\"cal_saisie\">\n";
						echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
						echo "<tr align=\"center\">\n";
							echo "<td>\n";
								// affichage du calendrier de saisie de la date de d�but de cong�s
								echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\" border=\"0\">\n";
								echo "<tr>\n";
									if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
									if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;

								// affichage des boutons de d�filement
								// recul du mois saisie fin
								// si le mois de saisie fin est ant�rieur ou �gal au mois de saisie d�but, on recule les 2 , sinon on recule que le mois de saisie fin
								if( ($year_calendrier_saisie_debut>=$year_calendrier_saisie_fin_prec) && ($mois_calendrier_saisie_debut>=$mois_calendrier_saisie_fin_prec) )
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_fin_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
								else
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";

								echo "<td align=\"center\" class=\"big\">FIN :</td>\n";

								// affichage des boutons de d�filement
								// avance du mois saisie fin
								echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
								echo "</tr>\n";
								echo "</table>\n";
								/*** calendrier saisie date fin ***/
								affiche_calendrier_saisie_date_fin($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
							echo "</td>\n";
							// cellule 2 : boutons radio matin ou apr�s midi
							echo "<td align=\"left\">\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_fin\" value=\"am\"><b><u>matin</u></b><br><br>\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_fin\" value=\"pm\" checked><b><u>apr�s midi</u></b><br><br>\n";
							echo "</td>\n";
						echo "</tr>\n";
						echo "</table>\n";
					echo "</fieldset>\n";
					echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
			echo "</td>\n";
			echo "<td><img src=\"../img/shim.gif\" width=\"15\" height=\"2\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
			echo "<td>\n";
			
				/***  formulaire ***/
				echo "<table cellpadding=\"0\" cellspacing=\"2\" border=\"0\" >\n";
				echo "<tr>\n";
				echo "<td valign=\"top\">\n";
					printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n");
					printf("<tr align=\"center\"><td><b>NB_Jours_Pris</b></td><td><b>Commentaire</b></td></tr>\n");

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
						echo "<input type=\"radio\" name=\"new_etat\" value=\"conges\" checked> cong�s<br>\n";
						if($config_rtt_comme_conges==1) // si on g�re les rtt comme des cong�s
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


//affiche le formulaire d'�change d'un jour de rtt-temps partiel / jour travaill�
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

				// affichage des boutons de d�filement
				// recul du mois saisie debut
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
				
				echo "<td align=\"center\" class=\"big\">Jour d'absence ordinaire :</td>\n";
				
				// affichage des boutons de d�filement
				// avance du mois saisie debut
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date debut ***/
				affiche_calendrier_saisie_jour_absence($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);  
			echo "</td>\n";
			
			// cellule 2 : boutons radio 1/2 journ�e ou jour complet
			echo "<td>\n";
				echo "<input type=\"radio\" name=\"moment_absence_ordinaire\" value=\"M\"><b><u>matin</u></b><input type=\"radio\" name=\"moment_absence_souhaitee\" value=\"M\"><br><br>\n";
				echo "<input type=\"radio\" name=\"moment_absence_ordinaire\" value=\"A\"><b><u>apr�s midi</u></b><input type=\"radio\" name=\"moment_absence_souhaitee\" value=\"A\"><br><br>\n";
				echo "<input type=\"radio\" name=\"moment_absence_ordinaire\" value=\"J\" checked><b><u>journ�e compl�te</u></b><input type=\"radio\" name=\"moment_absence_souhaitee\" value=\"J\" checked><br>\n";
			echo "</td>\n";
			
			// cellule 3 : calendrier de saisie du jour d'absence
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
				if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
				if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;
				
				// affichage des boutons de d�filement
				// recul du mois saisie fin
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> << </a></td>\n";
				
				echo "<td align=\"center\" class=\"big\">Jour d'absence souhait� :</td>\n";
				
				// affichage des boutons de d�filement
				// avance du mois saisie fin
				echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login&onglet=$onglet\"> >> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date fin ***/
				affiche_calendrier_saisie_jour_presence($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
			echo "</td>\n";
			
			// cellule 4 : champs texte et boutons (valider/cancel)
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


// initialisation des variables pour la mavigation mois pr�c�dent / mois suivant
// certains arguments sont pass�s par r�f�rence (avec &) car on change leur valeur
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


// affiche une chaine repr�sentant un decimal sans 0 � la fin ... 
// (un point separe les unit�s et les decimales et on ne considere que 2 decimales !!!)
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


// verif validit� des valeurs saisies lors d'une demande de congespar un user ou d'une saisie de conges par le responsable
//  (attention : le $new_nb_jours est pass� par r�f�rence car on le modifie si besoin)
function verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, &$new_nb_jours, $new_comment)
{
	$verif=TRUE ;
	
	// leur champs doivent etre renseign�s dans le formulaire
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
			$new_nb_jours=$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les d�cimaux
	}
	
	// si la date de fin est ant�reieure � la date debut 
	if(strnatcmp($new_debut, $new_fin)>0) { 
		echo "<br>ERREUR : mauvaise saise : <b>la date de fin ($new_fin) est anterieure a la date de d�but ($new_debut) !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	// si la date debut et fin = m�me jour mais d�but=apr�s midi et fin=matin !!
	if((strnatcmp($new_debut, $new_fin)==0)&&($new_demi_jour_deb=="pm")&&($new_demi_jour_fin=="am") ) {
		echo "<br>ERREUR : mauvaise saise : <b>la date de fin ($new_fin) est anterieure a la date de d�but ($new_debut) !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	return $verif;
}


// renvoit la couleur de fond du jour indiqu�par le timestamp
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


// renvoit la class de cellule du jour indiqu�e par le timestamp
// (une classe pour les jours de semaine et une pour les jours de week end)
function get_td_class_of_the_day_in_the_week($timestamp_du_jour)
{
	$j_name=date("D", $timestamp_du_jour);
	if(($j_name=="Sat") || ($j_name=="Sun"))
		return "weekend";
	else
		return "semaine";
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
	echo "<a href=\"../deconnexion.php?session=$session\" target=\"_top\"><img src=\"../img/exit.png\" width=\"22\" height=\"22\" border=\"0\" title=\"D�connexion\" alt=\"D�connexion\"></a> D�connexion\n";


}




// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont pass�es par r�f�rence (avec &) car on change leur valeur
function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem)
{
global $link;
global $config_user_echange_rtt ;

	$num_semaine=strftime("%W", $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres
	
	// on ne cherche pas d'artt les samedis ou dimanches
	if(($jour_name_fr_2c!="sa")&&($jour_name_fr_2c!="di")) 
	{  
		// verif si le jour fait l'objet d'un echange ....
		$date_j=date("Y-m-d", $j_timestamp);
		$sql_echange_rtt="SELECT e_absence FROM conges_echange_rtt WHERE e_login='$sql_login' AND e_date_jour='$date_j' ";
		$res_echange_rtt = mysql_query($sql_echange_rtt, $link) or die("ERREUR : recup_infos_artt_du_jour() <br>\n$sql_echange_rtt<br>\n".mysql_error());
		$num_echange_rtt = mysql_num_rows($res_echange_rtt);
		// si le jour est l'objet d'un echange, on tient compte de l'�change
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


// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont pass�es par r�f�rence (avec &) car on change leur valeur
function recup_infos_artt_du_jour_from_tab($sql_login, $j_timestamp, &$val_matin, &$val_aprem, $tab_rtt_echange, $tab_rtt_planifiees)
{
global $link;
global $config_user_echange_rtt ;

	//$tab_rtt_echange  //tableau index� dont la cl� est la date sous forme yyyy-mm-dd
						//il contient pour chaque cl� (chaque jour): un tableau ind�x� ($tab_jour_rtt_echange) (cl�= login)
						// qui contient lui m�me un tableau ($tab_echange) contenant les infos des echanges de rtt pour ce
						// jour et ce login (valeur du matin + valeur de l'apres midi ('Y' si rtt, 'N' sinon) )
	//$tab_rtt_planifiees  //tableau index� dont la cl� est le login du user
					// il contient pour chaque cl� : un tableau ($tab_user_rtt) qui contient lui m�me 
					// les infos pour le matin et l'apr�s midi ('Y' si rtt, 'N' sinon) sur 2 semaines 
					// ( du sem_imp_lu_am au sem_p_ve_pm )
	
	$num_semaine=strftime("%W", $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres
	
	// on ne cherche pas d'artt les samedis ou dimanches
	if(($jour_name_fr_2c!="sa")&&($jour_name_fr_2c!="di")) 
	{  
		// verif si le jour fait l'objet d'un echange ....
		$date_j=date("Y-m-d", $j_timestamp);
		// si le jour est l'objet d'un echange, on tient compte de l'�change
		$tab_day=$tab_rtt_echange[$date_j];  // on recup le tableau du jour
		if(in_array($sql_login, $tab_day))   // si la periode corespond au user que l'on est en train de traiter
		{
			$val_matin=$tab_day[$sql_login]["val_matin"];
			$val_aprem=$tab_day[$sql_login]["val_aprem"];
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
			$val_matin=$tab_rtt_planifiees[$sql_login][$key_artt_matin];
			$val_aprem=$tab_rtt_planifiees[$sql_login][$key_artt_aprem];
		}
	}
	else {
	}
}




// verif validit� d'un nombre saisi (decimal ou non)
//  (attention : le $nombre est pass� par r�f�rence car on le modifie si besoin)
function verif_saisie_decimal(&$nombre)
{
	$verif=TRUE ;
	
	if ( !ereg( "([0-9]+)([\.\,]*[0-9]{1,2})*", $nombre) ) {
		echo "<br>ERREUR : mauvaise saise : <b>le nombre saisi est invalide</b><br>\n";
		$verif=FALSE ;
	}
	else {
		if( ereg( "([0-9]+)\,([0-9]{1,2})", $nombre, $reg) )
			$nombre=$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les d�cimaux
	}
	
	return $verif;
}



// donne la date en francais (meme formats que la fonction PHP date() cf manuel php)
function date_fr($format, $timestamp) 
{ 
	$strDate = date($format, $timestamp); 

	/*CONVERSION*/ 
	//Format "F" 

	$mois_en = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); 
	$mois_fr = array("Janvier", "F�vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Ao�t", "Septembre", "Octobre", "Novembre", "D�cembre"); 
	$strDate = str_replace ($mois_en, $mois_fr, $strDate); 

	//Format "M" (et "r") 
	$mois_en = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); 
	$mois_fr = array("Jan", "F�v", "Mar", "Avr", "Mai", "Juin", "Juil", "Ao�t", "Sep", "Oct", "Nov", "D�c"); 
	$strDate = str_replace ($mois_en, $mois_fr, $strDate); 

	//Format "l" 
	$jour_en = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday","Friday", "Saturday"); 
	$jour_fr = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi","Vendredi", "Samedi"); 
	$strDate = str_replace ($jour_en, $jour_fr, $strDate); 

	//Format "D" (et "r") 
	$jour_en = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"); 
	$jour_fr = array("Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"); 
	$strDate = str_replace ($jour_en, $jour_fr, $strDate); 

	//Format "S" - st, th, nd et rd 
	//-On a besoin d'outils plus puissant pour 
	// remplacer "st" par "er" apr�s 1 et supprimer le "st" apr�s 21 et 31. 
	// ne pas supprimer les lettres "st", "nd" et "rd" des mots fran�ais!
	// (luNDi, veNDredi, maRDi, eST) 

	//$strDate = preg_replace("/(\D)1st/", "\${1}1er", $strDate); //1st qui n'est pas pr�c�d� par un chiffre 
	//$strDate = preg_replace("/(\d)(st|th|nd|rd)/", "\${1}", $strDate); //st, th, nd ou rd qui est pr�c�d� d'un chiffre 

	return $strDate; 
} 


// envoi d'un message d'avertissement au responsable (lors d'une nouvelle demande de conges)
function alerte_resp_mail($nom_login)
{
	require_once('INCLUDE.PHP/phpmailer/class.phpmailer.php');	// ajout de la classe phpmailer
	//require_once('INCLUDE.PHP/fonction.php');			// pour la cnx � la base de donn�es
	include('config.php');						// pour les param�tres g�n�raux...


	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = $config_serveur_smtp;


	$mail_array=find_email_adress_for_user($nom_login);
	
	$mail->FromName = $mail_array[0];
	$mail->From = $mail_array[1];      

	$mail->Subject  =  "APPLI CONGES - Demande de cong�s";
	$mail->Body     =  $mail->FromName." a solicit� une demande de cong�s dans l'application.

				Merci de consulter l'application $URL_ACCUEIL_CONGES

	-------------------------------------------------------------------------------------------------------									
										Ceci est un message automatique";


	// recherche du login du destinataire (du responsable) dans la base
	$link = connexion_mysql();
	$req = "SELECT u_resp_login FROM conges_users WHERE u_login='$nom_login'";
	$res = mysql_query($req, $link) or die('erreur '.mysql_error());
	$rec = mysql_fetch_array($res);

	// recherche de l'adresse mail du responsable :
	$resp = $rec[u_resp_login];
	
	$mail_array_resp=find_email_adress_for_user($resp);
	$resp_mail = $mail_array_resp[1];   
	   
	$mail->AddAddress($resp_mail);
	if(!$mail->Send())
	{
		echo "Message was not sent <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
	}

}

// recuperation du mail d'un user
function find_email_adress_for_user($login)
{
	include('config.php');						// pour les param�tres g�n�raux...
	
	if($config_where_to_find_user_email=="ldap") // recherche du mail du user dans un annuaire LDAP
	{
		// cnx � l'annuaire ldap :
		$ds = ldap_connect($config_ldap_server);
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) ;
		$bound = ldap_bind($ds, $config_ldap_user, $config_ldap_pass);

		// recherche des entr�es correspondantes au "login" pass� en param�tre :
		$filter = "(&(samaccountname=".$login.")"."(objectclass=user))";   

		$sr   = ldap_search($ds, $config_searchdn, $filter);
		$data = ldap_get_entries($ds,$sr);

		foreach ($data as $info)
		{  
			$found_mail=array();
			// On r�cup�re le nom et le mail de la personne.
			// Utilisation de la fonction utf8_decode pour corriger les caract�res accentu�s
			// (qnd les noms ou pr�noms ont des accents, "�", ...

			// Les champs LDAP utilis�s, bien que cens�s �tre uniformes, sont ceux d'un AD 2003.

			$found_mail[] = utf8_decode($info["givenname"][0])." ".strtoupper(utf8_decode($info["sn"][0]));
			$found_mail[] = $info["mail"][0];      
		}
	}
	elseif($config_where_to_find_user_email=="dbconges") // recherche du mail du user dans la base db_conges
	{
		$link = connexion_mysql();
		$req = "SELECT u_nom, u_prenom, u_email FROM conges_users WHERE u_login='$login'";
		$res = mysql_query($req, $link) or die('erreur '.mysql_error());
		$rec = mysql_fetch_array($res);

		$sql_nom = $rec[u_nom];
		$sql_prenom = $rec[u_prenom];
		$sql_email = $rec[u_email];
		
		$found_mail=array();
		$found_mail[] = utf8_decode($sql_prenom)." ".strtoupper(utf8_decode($sql_nom));
		$found_mail[] = $sql_email;      

	}
	else
	{
		return FALSE;
	}
	return $found_mail ;
}


/**************************************************/
/* recup des �changes de rtt de chaque jour du mois pour tous les users et stockage dans 1 tableau de tableaux */
/**************************************************/
function recup_tableau_rtt_echange($mois, $first_jour, $year ,$mysql_link)
{
	$tab_rtt_echange=array();  //tableau index� dont la cl� est la date sous forme yyyy-mm-dd
						//il contient pour chaque cl� (chaque jour): un tableau ind�x� ($tab_jour_rtt_echange) (cl�= login)
						// qui contient lui m�me un tableau ($tab_echange) contenant les infos des echanges de rtt pour ce
						// jour et ce login (valeur du matin + valeur de l'apres midi ('Y' si rtt, 'N' sinon) )
						
	// construction du tableau $tab_rtt_echange:
		
	// pour chaque jour : (du premier jour demand� � la fin du mois ...)
	for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
	{
		$j_timestamp=mktime (0,0,0,$mois, $j, $year);
		$date_j=date("Y-m-d", $j_timestamp);
		$tab_jour_rtt_echange=array();
		
		$sql_echange_rtt="SELECT e_login, e_absence FROM conges_echange_rtt WHERE e_date_jour='$date_j' ";
		$res_echange_rtt = mysql_query($sql_echange_rtt, $mysql_link) or die("ERREUR : recup_tableau_rtt_echange() <br>\n$sql_echange_rtt<br>\n".mysql_error());
		$num_echange_rtt = mysql_num_rows($res_echange_rtt);
		// si le jour est l'objet d'un echange, on tient compte de l'�change
		if($num_echange_rtt!=0)
		{
			while($result_echange_rtt = mysql_fetch_array($res_echange_rtt))
			{
				$tab_echange=array();
				$login=$result_echange_rtt["e_login"];
				if ($result_echange_rtt["e_absence"]=='J') // jour entier
				{
					$tab_echange["val_matin"]='Y';
					$tab_echange["val_aprem"]='Y';
				}
				elseif ($result_echange_rtt["e_absence"]=='M') // matin
				{
					$tab_echange["val_matin"]='Y';
					$tab_echange["val_aprem"]='N';
				}
				elseif ($result_echange_rtt["e_absence"]=='A') // apres-midi
				{
					$tab_echange["val_matin"]='N';
					$tab_echange["val_aprem"]='Y';
				}
				else
				{
					$tab_echange["val_matin"]='N';
					$tab_echange["val_aprem"]='N';
				}
				//array_walk ($tab_echange, 'test_print_array');
				$tab_jour_rtt_echange[$login]=$tab_echange;
			}
		}
		//array_walk ($tab_jour_rtt_echange, 'test_print_array');
		$tab_rtt_echange[$date_j]=$tab_jour_rtt_echange;
	}

	// si le premier jour demand� n'est pas le 1ier du mois , on va jusqu'� la meme date le mois suivant :
	if($first_jour!=1) {
		// pour chaque jour jusqu'a la date voulue : (meme num de jour le mois suivant)
		for($j=1; $j<$first_jour; $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois+1, $j, $year);
			$date_j=date("Y-m-d", $j_timestamp);
			$tab_jour_rtt_echange=array();

			$sql_echange_rtt="SELECT e_login, e_absence FROM conges_echange_rtt WHERE e_date_jour='$date_j' ";
			$res_echange_rtt = mysql_query($sql_echange_rtt, $mysql_link) or die("ERREUR : recup_tableau_rtt_echange() <br>\n$sql_echange_rtt<br>\n".mysql_error());
			$num_echange_rtt = mysql_num_rows($res_echange_rtt);
			// si le jour est l'objet d'un echange, on tient compte de l'�change
			if($num_echange_rtt!=0)
			{
				while($result_echange_rtt = mysql_fetch_array($res_echange_rtt))
				{
					$tab_echange=array();
					$login=$result_echange_rtt["e_login"];
					if ($result_echange_rtt["e_absence"]=='J') // jour entier
					{
						$tab_echange["val_matin"]='Y';
						$tab_echange["val_aprem"]='Y';
					}
					elseif ($result_echange_rtt["e_absence"]=='M') // matin
					{
						$tab_echange["val_matin"]='Y';
						$tab_echange["val_aprem"]='N';
					}
					elseif ($result_echange_rtt["e_absence"]=='A') // apres-midi
					{
						$tab_echange["val_matin"]='N';
						$tab_echange["val_aprem"]='Y';
					}
					else
					{
						$tab_echange["val_matin"]='N';
						$tab_echange["val_aprem"]='N';
					}
					//array_walk ($tab_echange, 'test_print_array');
					$tab_jour_rtt_echange[$login]=$tab_echange;
				}
			}
			//array_walk ($tab_jour_rtt_echange, 'test_print_array');
			$tab_rtt_echange[$date_j]=$tab_jour_rtt_echange;
		}
	}
	//array_walk ($tab_rtt_echange, 'test_print_array');
	return $tab_rtt_echange;
}



/**************************************************/
/* recup dans un tableau des rtt planifi�es  pour tous les users */
/**************************************************/
function recup_tableau_rtt_planifiees($mois, $first_jour, $year, $mysql_link )
{
	$tab_rtt_planifiees=array();  //tableau index� dont la cl� est le login du user
					// il contient pour chaque cl� : un tableau ($tab_user_rtt) qui contient lui m�me 
					// les infos pour le matin et l'apr�s midi ('Y' si rtt, 'N' sinon) sur 2 semaines 
					// ( du sem_imp_lu_am au sem_p_ve_pm )
					
	// construction du tableau $tab_rtt_planifie:
	$tab_user_rtt=array();
	
	$sql_artt="SELECT * FROM conges_artt ";
	$res_artt = mysql_query($sql_artt, $mysql_link) or die("ERREUR : recup_tableau_rtt_planifiees() <br>\n$sql_artt<br>\n".mysql_error());
	$num_artt = mysql_num_rows($res_artt);
	while($result_artt = mysql_fetch_array($res_artt))
	{
		$tab_user_rtt=array();
		$sql_artt_login=$result_artt["a_login"];
		
		$tab_user_rtt["sem_imp_lu_am"]=$result_artt["sem_imp_lu_am"];
		$tab_user_rtt["sem_imp_lu_pm"]=$result_artt["sem_imp_lu_pm"];
		$tab_user_rtt["sem_imp_ma_am"]=$result_artt["sem_imp_ma_am"];
		$tab_user_rtt["sem_imp_ma_pm"]=$result_artt["sem_imp_ma_pm"];
		$tab_user_rtt["sem_imp_me_am"]=$result_artt["sem_imp_me_am"];
		$tab_user_rtt["sem_imp_me_pm"]=$result_artt["sem_imp_me_pm"];
		$tab_user_rtt["sem_imp_je_am"]=$result_artt["sem_imp_je_am"];
		$tab_user_rtt["sem_imp_je_pm"]=$result_artt["sem_imp_je_pm"];
		$tab_user_rtt["sem_imp_ve_am"]=$result_artt["sem_imp_ve_am"];
		$tab_user_rtt["sem_imp_ve_pm"]=$result_artt["sem_imp_ve_pm"];
		$tab_user_rtt["sem_p_lu_am"]=$result_artt["sem_p_lu_am"];
		$tab_user_rtt["sem_p_lu_pm"]=$result_artt["sem_p_lu_pm"];
		$tab_user_rtt["sem_p_ma_am"]=$result_artt["sem_p_ma_am"];
		$tab_user_rtt["sem_p_ma_pm"]=$result_artt["sem_p_ma_pm"];
		$tab_user_rtt["sem_p_me_am"]=$result_artt["sem_p_me_am"];
		$tab_user_rtt["sem_p_me_pm"]=$result_artt["sem_p_me_pm"];
		$tab_user_rtt["sem_p_je_am"]=$result_artt["sem_p_je_am"];
		$tab_user_rtt["sem_p_je_pm"]=$result_artt["sem_p_je_pm"];
		$tab_user_rtt["sem_p_ve_am"]=$result_artt["sem_p_ve_am"];
		$tab_user_rtt["sem_p_ve_pm"]=$result_artt["sem_p_ve_pm"];
		
		//array_walk ($tab_user_rtt, 'test_print_array');
		$tab_rtt_planifiees[$sql_artt_login]=$tab_user_rtt;
	}
	//array_walk ($tab_rtt_planifiees, 'test_print_array');
	return $tab_rtt_planifiees;
}

?>
