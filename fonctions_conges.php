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
//function saisie_nouveau_conges($login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

//affiche le formulaire d'échange d'un jour de rtt-temps partiel / jour travaillé
//function saisie_echange_rtt($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin)

// initialisation des variables pour la navigation mois précédent / mois suivant
// certains arguments sont passés par référence (avec &) car on change leur valeur
//function init_var_navigation_mois_year($mois_calendrier_saisie_debut, $mois_calendrier_saisie_fin, &$mois_calendrier_saisie_debut_prec, &$year_calendrier_saisie_debut_prec, &$mois_calendrier_saisie_fin_suiv, &$year_calendrier_saisie_fin_suiv )

// affiche une chaine représentant un decimal sans 0 à la fin ... 
// (un point separe les unité et les décimales et on ne considère que 2 décimales !!!)
// ex : 10.00 devient 10  , 5.50 devient 5.5  , et 3.05 reste 3.05
//function affiche_decimal($str)

// verif validité des valeurs saisies lors d'une demande de conges par un user ou d'une saisie de conges par le responsable
//function verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, &$new_nb_jours, $new_comment, $new_type)

// renvoit la couleur de fond du jour indiqué par le timestamp
// (une couleur pour les jours de semaine et une pour les jours de week end)
//function get_bgcolor_of_the_day_in_the_week($timestamp_du_jour)

// renvoit la class de cellule du jour indiquée par le timestamp
// (une classe pour les jours de semaine et une pour les jours de week end)
//function get_td_class_of_the_day_in_the_week($timestamp_du_jour)

// affichage URL de-connexion
//function   bouton_deconnexion()

// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
//function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem)

// recup des infos ARTT ou Temps Partiel depuis un tableau préalablement construit :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
//function recup_infos_artt_du_jour_from_tab($sql_login, $j_timestamp, &$val_matin, &$val_aprem, $tab_rtt_echange, $tab_rtt_planifiees)

// verif validité d'un nombre saisi (decimal ou non)
//  (attention : le $nombre est passé par référence car on le modifie si besoin)
//function verif_saisie_decimal(&$nombre)

// donne la date en francais (meme formats que la fonction PHP date() cf manuel php)
//function date_fr($format, $timestamp) 

// envoi d'un message d'avertissement au responsable (lors d'une nouvelle demande de conges)
//function alerte_resp_mail($nom_login)

// recuperation du mail d'un user
//function find_email_adress_for_user($login)

// recup des échanges de rtt de chaque jour du mois pour tous les users et stockage dans 1 tableau de tableaux 
// renvoit le tableau $tab_rtt_echange
//function recup_tableau_rtt_echange($mois, $first_jour, $year )

// recup dans un tableau des rtt planifiées  pour tous les users 
// renvoit le tableau $tab_rtt_planifiees
// function recup_tableau_rtt_planifiees($mois, $first_jour, $year )

// affiche une liste déroulante des jours du mois 
//  la variable du select est $new_jour , $default est le jour selectionné par défaut (sur 2 chiffres)
//function affiche_selection_new_jour($default)

// affiche une liste déroulante des mois de l'année
//  la variable du select est $new_mois , $default est le mois selectionné par défaut (sur 2 chiffres)
//function affiche_selection_new_mois($default)

// affiche une liste déroulante d'année 
//  la variable du select est $new_year
//  $default est l'année selectionnée par défaut (sur 4 chiffres), $an_debut et $an_fin sont les valeurs extremes du select
//function affiche_selection_new_year($an_debut, $an_fin, $default)

// met la date aaaa-mm-jj dans le format jj-mm-aaaa
//function eng_date_to_fr($une_date)

// met la date jj-mm-aaaa dans le format aaaa-mm-jj
//function fr_date_to_eng($une_date)

// affichage de la cellule correspondant au jour dans les calendrier de saisie (demande de conges, etc ...)
//function affiche_cellule_jour_cal_saisie($login, $j_timestamp, $td_second_class, $result)

// recup de la liste des users des groupes dont $resp_login est responsable 
// renvoit une liste de login entre quotes et séparés par des virgules
//function get_list_users_des_groupes_du_resp($resp_login)

// recup de la liste des groupes dont $resp_login est responsable 
// renvoit une liste de groupename entre quotes et séparés par des virgules
//function get_list_groupes_du_resp($resp_login)

// recup de la liste des users des groupes dont $user_login est membre 
// renvoit une liste de login entre quotes et séparés par des virgules
//function get_list_users_des_groupes_du_user($user_login, $link)

// recup de la liste des groupes dont $resp_login est membre 
// renvoit une liste de group_id séparés par des virgules
//function get_list_groupes_du_user($user_login, $link)

// fonction utilisée avec le mode d'authentification ldap.
// verifie si un user qui vient de s'authentifier sur le ldap est bien dans la table users de db_conges
//function valid_ldap_user($username)

// verifie si un user est responasble ou pas
// renvoit TRUE si le login est responsable dans la table conges_users, FALSE sinon.
//function is_resp($login, $link)

// verifie si un user est administrateur ou pas
// renvoit TRUE si le login est administrateur dans la table conges_users, FALSE sinon.
//function is_admin($login, $link)

// verifie si un administrateur est responsable de users ou pas
// renvoit TRUE si le login est responsable dans la table conges_users, FALSE sinon.
//function admin_is_responsable($login, $mysql_link)

// on insert une nouvelle periode dans la table periode
// retourne le resultat du mysql_query (TRUE ou FALSE)
//function insert_dans_periode($login, $date_deb, $demi_jour_deb, $date_fin, $demi_jour_fin, $nb_jours, $commentaire, $type, $etat, $mysql_link)


//#################################################################################################


// affichage du calendrier avec les case à cocher, du mois du début du congés 
function  affiche_calendrier_saisie_date_debut($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $config_samedi_travail, $config_dimanche_travail ;
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
	echo "		<td class=\"cal-saisie2\">L</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">J</td><td class=\"cal-saisie2\">V</td><td class=\"cal-saisie2\">S</td><td class=\"cal-saisie2\">D</td>\n";
	echo "	</tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_debut");
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_debut");
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_debut");
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_debut");
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_debut");
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_debut");
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)))
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}


// affichage du calendrier avec les case à cocher, du mois de fin du congés 
function  affiche_calendrier_saisie_date_fin($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $config_samedi_travail, $config_dimanche_travail ;
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
	echo "	<tr align=\"center\"  bgcolor=\"$config_light_grey_bgcolor\">\n" ;
	echo "		<td class=\"cal-saisie2\">L</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">J</td><td class=\"cal-saisie2\">V</td><td class=\"cal-saisie2\">S</td><td class=\"cal-saisie2\">D</td>\n" ;
	echo "	</tr>\n" ;
	
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_fin");
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_fin");
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_fin");
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_fin");
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne)*/
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_fin");
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, "new_fin");
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}




// affichage du calendrier du mois avec les case à cocher sur les jour d'absence 
function  affiche_calendrier_saisie_jour_absence($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $config_samedi_travail, $config_dimanche_travail ;
global $link ;

	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)
		
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"250\" bgcolor=$config_semaine_bgcolor>\n";
	/* affichage  2 premieres lignes */
	echo "	<tr align=\"center\" bgcolor=\"$config_light_grey_bgcolor\"><td colspan=7 class=\"titre\"> $mois_name $year </td></tr>\n" ;
	echo "	<tr bgcolor=\"$config_light_grey_bgcolor\">\n";
	echo "		<td class=\"cal-saisie2\">L</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">J</td><td class=\"cal-saisie2\">V</td><td class=\"cal-saisie2\">S</td><td class=\"cal-saisie2\">D</td>\n";
	echo "	</tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie2\">-</td>";
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
			// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
			if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
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
			// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
			if( (($i==14-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==15-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
			// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
			if( (($i==21-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==22-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
			// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
			if( (($i==28-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==29-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
			// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
			if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie2\">-</td>";	
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
			// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
			if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
				$bgcolor=$config_week_end_bgcolor;
			else
				$bgcolor=$config_semaine_bgcolor;
			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$i</td>";
		}
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) {
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}



// affichage du calendrier du mois avec les case à cocher sur les jour de présence
function  affiche_calendrier_saisie_jour_presence($user_login, $year, $mois) {
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_temps_partiel_bgcolor;
global $config_conges_bgcolor, $config_demande_conges_bgcolor, $config_light_grey_bgcolor;
global $config_samedi_travail, $config_dimanche_travail ;
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
	echo "		<td class=\"cal-saisie2\">L</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">M</td><td class=\"cal-saisie2\">J</td><td class=\"cal-saisie2\">V</td><td class=\"cal-saisie2\">S</td><td class=\"cal-saisie2\">D</td>\n";
	echo "	</tr>\n" ;
	
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) {
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		$val_matin="";
		$val_aprem="";
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==6)&&($config_samedi_travail==FALSE)) || (($i==7)&&($config_dimanche_travail==FALSE)) )
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
				echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$j<input type=\"radio\" name=\"new_fin\" value=\"$year-$mois-$j\"></td>";
			}
		}
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		$val_matin="";
		$val_aprem="";
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==14-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==15-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==21-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==22-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==28-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==29-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==35-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==36-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) {
		$val_matin="";
		$val_aprem="";
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
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
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==42-$first_jour_mois_rang)&&($config_samedi_travail==FALSE)) || (($i==43-$first_jour_mois_rang)&&($config_dimanche_travail==FALSE)) )
			$bgcolor=$config_week_end_bgcolor;
		else
			$bgcolor=$config_semaine_bgcolor;
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}



// saisie de la grille des jours d'abscence ARTT ou temps partiel:
function saisie_jours_absence_temps_partiel($login, $mysql_link)
{
global $config_samedi_travail, $config_dimanche_travail ;

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
	$checked_option_sem_imp_sa_am="";
	$checked_option_sem_imp_sa_pm="";
	$checked_option_sem_imp_di_am="";
	$checked_option_sem_imp_di_pm="";
		
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
	$checked_option_sem_p_sa_am="";
	$checked_option_sem_p_sa_pm="";
	$checked_option_sem_p_di_am="";
	$checked_option_sem_p_di_pm="";
	/*********************************************/
	
	// recup des données de la dernière table artt du user :
	$sql1 = "SELECT * FROM conges_artt WHERE a_login='$login' AND a_date_fin_grille='9999-12-31' "  ;
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
		if($resultat1['sem_imp_sa_am']=="Y") $checked_option_sem_imp_sa_am=" checked";
		if($resultat1['sem_imp_sa_pm']=="Y") $checked_option_sem_imp_sa_pm=" checked";
		if($resultat1['sem_imp_di_am']=="Y") $checked_option_sem_imp_di_am=" checked";
		if($resultat1['sem_imp_di_pm']=="Y") $checked_option_sem_imp_di_pm=" checked";
		
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
		if($resultat1['sem_p_sa_am']=="Y") $checked_option_sem_p_sa_am=" checked";
		if($resultat1['sem_p_sa_pm']=="Y") $checked_option_sem_p_sa_pm=" checked";
		if($resultat1['sem_p_di_am']=="Y") $checked_option_sem_p_di_am=" checked";
		if($resultat1['sem_p_di_pm']=="Y") $checked_option_sem_p_di_pm=" checked";
		$date_deb_grille=$resultat1['a_date_debut_grille'];
		$date_fin_grille=$resultat1['a_date_fin_grille'];
	}

	
	echo "<h4>saisie des jours d'abscence pour ARTT ou temps partiel :</h4>\n";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "<td>\n";
		//tableau semaines impaires
		echo "<b><u>semaines Impaires:</u></b><br>\n";
		$tab_checkbox_sem_imp=array();
		$imp_lu_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_lu_am]\" value=\"Y\" $checked_option_sem_imp_lu_am>";
		$imp_lu_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_lu_pm]\" value=\"Y\" $checked_option_sem_imp_lu_pm>";
		$imp_ma_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ma_am]\" value=\"Y\" $checked_option_sem_imp_ma_am>";
		$imp_ma_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ma_pm]\" value=\"Y\" $checked_option_sem_imp_ma_pm>";
		$imp_me_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_me_am]\" value=\"Y\" $checked_option_sem_imp_me_am>";
		$imp_me_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_me_pm]\" value=\"Y\" $checked_option_sem_imp_me_pm>";
		$imp_je_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_je_am]\" value=\"Y\" $checked_option_sem_imp_je_am>";
		$imp_je_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_je_pm]\" value=\"Y\" $checked_option_sem_imp_je_pm>";
		$imp_ve_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ve_am]\" value=\"Y\" $checked_option_sem_imp_ve_am>";
		$imp_ve_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_ve_pm]\" value=\"Y\" $checked_option_sem_imp_ve_pm>";
		if($config_samedi_travail==TRUE)
		{
			$imp_sa_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_sa_am]\" value=\"Y\" $checked_option_sem_imp_sa_am>";
			$imp_sa_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_sa_pm]\" value=\"Y\" $checked_option_sem_imp_sa_pm>";
		}
		if($config_dimanche_travail==TRUE)
		{
			$imp_di_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_di_am]\" value=\"Y\" $checked_option_sem_imp_di_am>";
			$imp_di_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_imp[sem_imp_di_pm]\" value=\"Y\" $checked_option_sem_imp_di_pm>";
		}
		
		echo "<table cellpadding=\"1\" class=\"tablo\">\n";
		echo "<tr align=\"center\">\n";
			echo "<td></td><td class=\"histo\">Lundi</td><td class=\"histo\">Mardi</td><td class=\"histo\">Mercredi</td><td class=\"histo\">Jeudi</td><td class=\"histo\">Vendredi</td>\n";
			if($config_samedi_travail==TRUE)
				echo "<td class=\"histo\">Samedi</td>\n";
			if($config_dimanche_travail==TRUE)
				echo "<td class=\"histo\">Dimanche</td>\n";
		echo "</tr>\n";
		echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">matin</td><td class=\"histo\">$imp_lu_am</td><td class=\"histo\">$imp_ma_am</td><td class=\"histo\">$imp_me_am</td><td class=\"histo\">$imp_je_am</td><td class=\"histo\">$imp_ve_am</td>\n";
			if($config_samedi_travail==TRUE)
				echo "<td class=\"histo\">$imp_sa_am</td>\n";
			if($config_dimanche_travail==TRUE)
				echo "<td class=\"histo\">$imp_di_am</td>\n";
		echo "</tr>\n";
		echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">apres-midi</td><td class=\"histo\">$imp_lu_pm</td><td class=\"histo\">$imp_ma_pm</td><td class=\"histo\">$imp_me_pm</td><td class=\"histo\">$imp_je_pm</td><td class=\"histo\">$imp_ve_pm</td>\n";
			if($config_samedi_travail==TRUE)
				echo "<td class=\"histo\">$imp_sa_pm</td>\n";
			if($config_dimanche_travail==TRUE)
				echo "<td class=\"histo\">$imp_di_pm</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	echo "</td>\n";
	echo " <td><img src=\"../img/shim.gif\" width=\"15\" height=\"2\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
	echo " <td>\n";

		//tableau semaines paires
		echo "<b><u>semaines Paires:</u></b><br>\n";
		$tab_checkbox_sem_p=array();
		$p_lu_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_lu_am]\" value=\"Y\" $checked_option_sem_p_lu_am>";
		$p_lu_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_lu_pm]\" value=\"Y\" $checked_option_sem_p_lu_pm>";
		$p_ma_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ma_am]\" value=\"Y\" $checked_option_sem_p_ma_am>";
		$p_ma_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ma_pm]\" value=\"Y\" $checked_option_sem_p_ma_pm>";
		$p_me_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_me_am]\" value=\"Y\" $checked_option_sem_p_me_am>";
		$p_me_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_me_pm]\" value=\"Y\" $checked_option_sem_p_me_pm>";
		$p_je_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_je_am]\" value=\"Y\" $checked_option_sem_p_je_am>";
		$p_je_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_je_pm]\" value=\"Y\" $checked_option_sem_p_je_pm>";
		$p_ve_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ve_am]\" value=\"Y\" $checked_option_sem_p_ve_am>";
		$p_ve_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_ve_pm]\" value=\"Y\" $checked_option_sem_p_ve_pm>";
		$p_sa_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_sa_am]\" value=\"Y\" $checked_option_sem_p_sa_am>";
		$p_sa_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_sa_pm]\" value=\"Y\" $checked_option_sem_p_sa_pm>";
		$p_di_am="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_di_am]\" value=\"Y\" $checked_option_sem_p_di_am>";
		$p_di_pm="<input type=\"checkbox\" name=\"tab_checkbox_sem_p[sem_p_di_pm]\" value=\"Y\" $checked_option_sem_p_di_pm>";
		
		echo "<table cellpadding=\"1\"  class=\"tablo\">\n";
		echo "<tr align=\"center\">\n";
			echo "<td></td><td class=\"histo\">Lundi</td><td class=\"histo\">Mardi</td><td class=\"histo\">Mercredi</td><td class=\"histo\">Jeudi</td><td class=\"histo\">Vendredi</td>\n";
			if($config_samedi_travail==TRUE)
				echo "<td class=\"histo\">Samedi</td>\n";
			if($config_dimanche_travail==TRUE)
				echo "<td class=\"histo\">Dimanche</td>\n";
		echo "</tr>\n";
		echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">matin</td><td class=\"histo\">$p_lu_am</td><td class=\"histo\">$p_ma_am</td><td class=\"histo\">$p_me_am</td><td class=\"histo\">$p_je_am</td><td class=\"histo\">$p_ve_am</td>\n";
			if($config_samedi_travail==TRUE)
				echo "<td class=\"histo\">$p_sa_am</td>\n";
			if($config_dimanche_travail==TRUE)
				echo "<td class=\"histo\">$p_di_am</td>\n";
		echo "</tr>\n";
		echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">apres-midi</td><td class=\"histo\">$p_lu_pm</td><td class=\"histo\">$p_ma_pm</td><td class=\"histo\">$p_me_pm</td><td class=\"histo\">$p_je_pm</td><td class=\"histo\">$p_ve_pm</td>\n";
			if($config_samedi_travail==TRUE)
				echo "<td class=\"histo\">$p_sa_pm</td>\n";
			if($config_dimanche_travail==TRUE)
				echo "<td class=\"histo\">$p_di_pm</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "<td colspan=\"3\">\n";
		$jour_default=date("d");
		$mois_default=date("m");
		$year_default=date("Y");
		echo "<br>Date de début de validité de cette grille :\n";
		affiche_selection_new_jour($jour_default);  // la variable est $new_jour
		affiche_selection_new_mois($mois_default);  // la variable est $new_mois
		affiche_selection_new_year($year_default-2, $year_default+10, $year_default );  // la variable est $new_year
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


// retourne le nom du jour de la semaine en francais sur 2 caracteres
function get_j_name_fr_2c($timestamp)
{
	setlocale (LC_TIME, "eng");
	//setLocale (LC_TIME, 'en_EN.ISO8859-1');
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
	//setlocale (LC_TIME, 'fr_FR.ISO8859-1');
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
								// affichage du calendrier de saisie de la date de début de congès
								echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\" border=\"0\">\n";
								echo "<tr>\n";
									init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
												$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec, 
												$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
												$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
												$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
												$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

								// affichage des boutons de défilement
								// recul du mois saisie début
								echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois précédent\" title=\"mois précédent\"> </a></td>\n";

								echo "<td align=\"center\" class=\"big\">DEBUT :</td>\n";

								// affichage des boutons de défilement
								// avance du mois saisie début
								// si le mois de saisie fin est antérieur ou égal au mois de saisie début, on avance les 2 , sinon on avance que le mois de saisie début
								if( (($year_calendrier_saisie_debut_suiv==$year_calendrier_saisie_fin) && ($mois_calendrier_saisie_debut_suiv>=$mois_calendrier_saisie_fin))
								    || ($year_calendrier_saisie_debut_suiv>$year_calendrier_saisie_fin)  )
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_debut_suiv&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois suivant\" title=\"mois suivant\"> </a></td>\n";
								else
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois suivant\" title=\"mois suivant\"> </a></td>\n";

								echo "</tr>\n";
								echo "</table>\n";
								/*** calendrier saisie date debut ***/
								affiche_calendrier_saisie_date_debut($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);  
							echo "</td>\n";
							// cellule 2 : boutons radio matin ou après midi
							echo "<td align=\"left\">\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_deb\" value=\"am\" checked><b><u>matin</u></b><br><br>\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_deb\" value=\"pm\"><b><u>après midi</u></b><br><br>\n";
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
								// affichage du calendrier de saisie de la date de fin de congès
								echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\" border=\"0\">\n";
								echo "<tr>\n";
									if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
									if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;

								// affichage des boutons de défilement
								// recul du mois saisie fin
								// si le mois de saisie fin est antérieur ou égal au mois de saisie début, on recule les 2 , sinon on recule que le mois de saisie fin
								if( (($year_calendrier_saisie_debut==$year_calendrier_saisie_fin_prec) && ($mois_calendrier_saisie_debut>=$mois_calendrier_saisie_fin_prec))
								    || ($year_calendrier_saisie_debut>$year_calendrier_saisie_fin_prec) )
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_fin_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois précédent\" title=\"mois précédent\"> </a></td>\n";
								else
									echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois précédent\" title=\"mois précédent\"> </a></td>\n";

								echo "<td align=\"center\" class=\"big\">FIN :</td>\n";

								// affichage des boutons de défilement
								// avance du mois saisie fin
								echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois suivant\" title=\"mois suivant\"> </a></td>\n";
								echo "</tr>\n";
								echo "</table>\n";
								/*** calendrier saisie date fin ***/
								affiche_calendrier_saisie_date_fin($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
							echo "</td>\n";
							// cellule 2 : boutons radio matin ou après midi
							echo "<td align=\"left\">\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_fin\" value=\"am\"><b><u>matin</u></b><br><br>\n";
								echo "<input type=\"radio\" name=\"new_demi_jour_fin\" value=\"pm\" checked><b><u>après midi</u></b><br><br>\n";
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
				echo "<td align=\"left\" valign=\"top\">\n";
					// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
					// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
					if( (($config_user_saisie_demande==TRUE)&&($user_login==$session_username)) ||
					    (($config_user_saisie_demande==FALSE)&&($user_login!=$session_username)) )
					{
						echo "<input type=\"radio\" name=\"new_type\" value=\"conges\" checked> congés<br>\n";
						if($config_rtt_comme_conges==TRUE) // si on gère les rtt comme des congés
							echo "<input type=\"radio\" name=\"new_type\" value=\"rtt\"> rtt<br>\n";
					}
					// si le user a droit de saisir une mission ET si on est PAS dans une fenetre de responsable
					// OU si le resp a droit de saisir une mission ET si on est dans une fenetre de responsable
					if( (($config_user_saisie_mission==TRUE)&&($user_login==$session_username)) ||
					    (($config_resp_saisie_mission==TRUE)&&($user_login!=$session_username)) )
					{
						echo "<input type=\"radio\" name=\"new_type\" value=\"mission\"> mission<br>\n";
						echo "<input type=\"radio\" name=\"new_type\" value=\"formation\"> formation<br>\n";
						echo "<input type=\"radio\" name=\"new_type\" value=\"autre\"> autre<br>\n";
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

					// affichage des boutons de défilement
					// recul du mois saisie debut
					echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_prec&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_prec&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois précédent\" title=\"mois précédent\"> </a></td>\n";

					echo "<td align=\"center\" class=\"big\">Jour d'absence ordinaire :</td>\n";

					// affichage des boutons de défilement
					// avance du mois saisie debut
					echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut_suiv&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut_suiv&year_calendrier_saisie_fin=$year_calendrier_saisie_fin&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois suivant\" title=\"mois suivant\"> </a></td>\n";
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
			
			// cellule 3 : calendrier de saisie du jour d'absence
			echo "<td>\n";
				echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"250\">\n";
				echo "<tr>\n";
					if($mois_calendrier_saisie_fin==1) $mois_calendrier_saisie_fin_prec=12; else $mois_calendrier_saisie_fin_prec=$mois_calendrier_saisie_fin-1 ;
					if($mois_calendrier_saisie_fin==12) $mois_calendrier_saisie_fin_suiv=1; else $mois_calendrier_saisie_fin_suiv=$mois_calendrier_saisie_fin+1 ;

					// affichage des boutons de défilement
					// recul du mois saisie fin
					echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_prec&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_prec&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois précédent\" title=\"mois précédent\"> </a></td>\n";

					echo "<td align=\"center\" class=\"big\">Jour d'absence souhaité :</td>\n";

					// affichage des boutons de défilement
					// avance du mois saisie fin
					echo "<td align=\"center\" class=\"big\"><a href=\"$PHP_SELF?session=$session&year_calendrier_saisie_debut=$year_calendrier_saisie_debut&mois_calendrier_saisie_debut=$mois_calendrier_saisie_debut&year_calendrier_saisie_fin=$year_calendrier_saisie_fin_suiv&mois_calendrier_saisie_fin=$mois_calendrier_saisie_fin_suiv&user_login=$user_login&onglet=$onglet\"> <img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"mois suivant\" title=\"mois suivant\"> </a></td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				/*** calendrier saisie date fin ***/
				affiche_calendrier_saisie_jour_presence($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);  
			echo "</td>\n";
			
			echo "</tr>\n";
			echo "<tr align=\"center\">\n";
			
			// cellule 1 : champs texte et boutons (valider/cancel)
			echo "<td colspan=3>\n";
			
				/***  formulaire ***/
					printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n");
					printf("<tr align=\"center\">\n");
						printf("<td><b>Commentaire : </b></td>\n");
						$text_commentaire="<input type=\"text\" name=\"new_comment\" size=\"25\" maxlength=\"30\" value=\"".$comment."\">" ;
						printf("<td>%s</td>\n", $text_commentaire);
					printf("</tr>\n");
					printf("<tr align=\"center\">\n");
						printf("<td colspan=2><img src=\"../img/shim.gif\" width=\"15\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n");
					printf("</tr>\n");
					printf("<tr align=\"center\">\n");
						printf("<td colspan=2>\n");
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
function verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, &$new_nb_jours, $new_comment, $new_type)
{
	$verif=TRUE ;
	
	// leur champs doivent etre renseignés dans le formulaire
	if( ($new_debut=="") || ($new_fin=="") || ($new_nb_jours=="") ) {
		echo "<br>ERREUR : mauvaise saisie : valeurs <b>manquantes !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	if ( !ereg( "([0-9]+)([\.\,]*[0-9]{1,2})*", $new_nb_jours) ) {
		echo "<br>ERREUR : mauvaise saisie : <b>le nombre de jours est invalide</b><br>\n";
		$verif=FALSE ;
	}
	else {
		if( ereg( "([0-9]+)\,([0-9]{1,2})", $new_nb_jours, $reg) )
			$new_nb_jours=$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les décimaux
	}
	
	// si la date de fin est antéreieure à la date debut 
	if(strnatcmp($new_debut, $new_fin)>0) { 
		echo "<br>ERREUR : mauvaise saisie : <b>la date de fin ($new_fin) est anterieure a la date de début ($new_debut) !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	// si la date debut et fin = même jour mais début=après midi et fin=matin !!
	if((strnatcmp($new_debut, $new_fin)==0)&&($new_demi_jour_deb=="pm")&&($new_demi_jour_fin=="am") ) {
		echo "<br>ERREUR : mauvaise saisie : <b>la date de fin ($new_fin) est anterieure a la date de début ($new_debut) !!!</b><br>\n";
		$verif=FALSE ;
	}
		
	// si le type de conges n'est pas dans l'enum de la table conges_periode!!
	if( ($new_type!="conges")&&($new_type!="rtt")&&($new_type!="formation")&&($new_type!="mission")&&($new_type!="autre")&&($new_type!="ajout") ) {
		echo "<br>ERREUR : mauvaise saisie : le type $new_type est invalide !!!</b><br>\n";
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


// renvoit la class de cellule du jour indiquée par le timestamp
// (une classe pour les jours de semaine et une pour les jours de week end)
function get_td_class_of_the_day_in_the_week($timestamp_du_jour)
{
global $config_samedi_travail, $config_dimanche_travail ;

	$j_name=date("D", $timestamp_du_jour);
	if( (($j_name=="Sat")&&($config_samedi_travail==FALSE)) || (($j_name=="Sun")&&($config_dimanche_travail==FALSE)))
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
      
	echo "<a href=\"../deconnexion.php?session=$session\" target=\"_top\"><img src=\"../img/exit.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Déconnexion\" alt=\"Déconnexion\"></a> Déconnexion\n";

}




// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem)
{
global $link;
//global $config_user_echange_rtt ;
global $config_samedi_travail, $config_dimanche_travail ;

	$num_semaine=strftime("%W", $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres
	
	// on ne cherche pas d'artt les samedis ou dimanches quand il ne sont pas travaillés (cf config.php)
	if( (($jour_name_fr_2c=="sa")&&($config_samedi_travail==FALSE)) || (($jour_name_fr_2c=="di")&&($config_dimanche_travail==FALSE)) ) 
	{
		// on ne cherche pas d'artt les samedis ou dimanches quand ils ne sont pas travaillés
	}
	else 
	{  
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
			$sql_artt="SELECT $key_artt_matin, $key_artt_aprem FROM conges_artt 
						WHERE a_login='$sql_login' AND a_date_debut_grille<='$date_j' AND a_date_fin_grille>='$date_j' ";
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
}


// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
function recup_infos_artt_du_jour_from_tab($sql_login, $j_timestamp, &$val_matin, &$val_aprem, $tab_rtt_echange, $tab_rtt_planifiees)
{
global $link;
//global $config_user_echange_rtt ;
global $config_samedi_travail, $config_dimanche_travail;

	//$tab_rtt_echange  //tableau indexé dont la clé est la date sous forme yyyy-mm-dd
						//il contient pour chaque clé (chaque jour): un tableau indéxé ($tab_jour_rtt_echange) (clé= login)
						// qui contient lui même un tableau ($tab_echange) contenant les infos des echanges de rtt pour ce
						// jour et ce login (valeur du matin + valeur de l'apres midi ('Y' si rtt, 'N' sinon) )
	//$tab_rtt_planifiees  //tableau indexé dont la clé est le login_user
					// il contient pour chaque clé login : un tableau ($tab_user_grille) indexé dont la 
					// clé est la date_fin_grille.
					// qui contient lui meme pour chaque clé : un tableau ($tab_user_rtt) qui contient enfin 
					// les infos pour le matin et l'après midi ('Y' si rtt, 'N' sinon) sur 2 semaines 
					// ( du sem_imp_lu_am au sem_p_ve_pm ) + la date de début et de fin de la grille
	
	$num_semaine=strftime("%W", $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres
	
	// on ne cherche pas d'artt les samedis ou dimanches quand il ne sont pas travaillés (cf config.php)
	if( (($jour_name_fr_2c=="sa")&&($config_samedi_travail==FALSE)) || (($jour_name_fr_2c=="di")&&($config_dimanche_travail==FALSE)) ) 
	{  
		// on ne cherche pas d'artt les samedis ou dimanches quand il ne sont pas travaillés
	}
	else 
	{
		// verif si le jour fait l'objet d'un echange ....
		$date_j=date("Y-m-d", $j_timestamp);
		//echo "$date_j<br>\n";
		// si le jour est l'objet d'un echange, on tient compte de l'échange
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

			// recup des ARTT et temps-partiels du user :
			// recup des grille du user 
			$tab_grille_user=$tab_rtt_planifiees[$sql_login];
			// parcours du tableau des grille pour trouver la key qui correspond à la bonne période
			foreach ($tab_grille_user as $key => $value) {
				if( ($date_j>=$value["date_debut_grille"]) && ($date_j<=$value["date_fin_grille"]) ) // date_jour comprise entre date_deb_grille et date_fin grille
				{
					$val_matin=$value[$key_artt_matin];
					$val_aprem=$value[$key_artt_aprem];
					//echo "$sql_login : ".$value["login"]."<br>\n";
				}
				else
				{
				}
			}
		}
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



// donne la date en francais (meme formats que la fonction PHP date() cf manuel php)
function date_fr($code, $timestmp)
{
	$les_mois_longs  = array("pas_de_zero", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

	$les_jours_courts = array("dim", "lun", "mar", "mer", "jeu", "ven", "sam");
	$les_jours_longs  = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");

	switch ($code) {
		case "F":
			$tmp = date("n", $timestmp);
			return $les_mois_longs[$tmp];
			break;

		case "l":
			$tmp = date("w", $timestmp);
			return $les_jours_longs[$tmp];
			break;

		case "D":
			$tmp = date("w", $timestmp);
			return $les_jours_courts[$tmp];
			break;

		default:
			return date($code, $timestmp);
			break;
	}
}



// envoi d'un message d'avertissement au responsable (lors d'une nouvelle demande de conges)
function alerte_resp_mail($nom_login)
{
	require_once('INCLUDE.PHP/phpmailer/class.phpmailer.php');	// ajout de la classe phpmailer
	//require_once('INCLUDE.PHP/fonction.php');			// pour la cnx à la base de données
	include('config.php');						// pour les paramètres généraux...


	$mail = new PHPMailer();
	if($config_serveur_smtp=="")
		$mail->IsMail();
	else
		$mail->IsSMTP();
	// initialisation du langage utilisé par php_mailer
	if(is_dir('INCLUDE.PHP/phpmailer/language'))
		$mail->SetLanguage("fr", "INCLUDE.PHP/phpmailer/language/");
	else
		$mail->SetLanguage("fr", "../INCLUDE.PHP/phpmailer/language/");
	$mail->Host = $config_serveur_smtp;


	$mail_array=find_email_adress_for_user($nom_login);
	
	$mail->FromName = $mail_array[0];
	$mail->From = $mail_array[1];      

	$mail->Subject  =  $config_mail_sujet;
	$mail->Body     =  $mail->FromName." ".$config_mail_contenu;

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
	include('config.php');						// pour les paramètres généraux...
	
	if($config_where_to_find_user_email=="ldap") // recherche du mail du user dans un annuaire LDAP
	{
		include('config_ldap.php');
		// cnx à l'annuaire ldap :
		$ds = ldap_connect($config_ldap_server);
		//ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) ;
		if ($config_ldap_user == "")
		     $bound = ldap_bind($ds);
		else $bound = ldap_bind($ds, $config_ldap_user, $config_ldap_pass);

		// recherche des entrées correspondantes au "login" passé en paramètre :
		$filter = "(".$config_ldap_login."=".$login.")";

		$sr   = ldap_search($ds, $config_searchdn, $filter);
		$data = ldap_get_entries($ds,$sr);

		foreach ($data as $info)
		{  
			$found_mail=array();
			// On récupère le nom et le mail de la personne.
			// Utilisation de la fonction utf8_decode pour corriger les caractères accentués
			// (qnd les noms ou prénoms ont des accents, "ç", ...

			// Les champs LDAP utilisés, bien que censés être uniformes, sont ceux d'un AD 2003.

			$found_mail[] = utf8_decode($info[$config_ldap_prenom][0])." ".strtoupper(utf8_decode($info[$config_ldap_nom][0]));
			$found_mail[] = $info[$config_ldap_mail][0];      
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
/* recup des échanges de rtt de chaque jour du mois pour tous les users et stockage dans 1 tableau de tableaux */
/**************************************************/
function recup_tableau_rtt_echange($mois, $first_jour, $year ,$mysql_link)
{
	$tab_rtt_echange=array();  //tableau indexé dont la clé est la date sous forme yyyy-mm-dd
						//il contient pour chaque clé (chaque jour): un tableau indéxé ($tab_jour_rtt_echange) (clé= login)
						// qui contient lui même un tableau ($tab_echange) contenant les infos des echanges de rtt pour ce
						// jour et ce login (valeur du matin + valeur de l'apres midi ('Y' si rtt, 'N' sinon) )
						
	// construction du tableau $tab_rtt_echange:
		
	// pour chaque jour : (du premier jour demandé à la fin du mois ...)
	for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
	{
		$j_timestamp=mktime (0,0,0,$mois, $j, $year);
		$date_j=date("Y-m-d", $j_timestamp);
		$tab_jour_rtt_echange=array();
		
		$sql_echange_rtt="SELECT e_login, e_absence FROM conges_echange_rtt WHERE e_date_jour='$date_j' ";
		$res_echange_rtt = mysql_query($sql_echange_rtt, $mysql_link) or die("ERREUR : recup_tableau_rtt_echange() <br>\n$sql_echange_rtt<br>\n".mysql_error());
		$num_echange_rtt = mysql_num_rows($res_echange_rtt);
		// si le jour est l'objet d'un echange, on tient compte de l'échange
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

	// si le premier jour demandé n'est pas le 1ier du mois , on va jusqu'à la meme date le mois suivant :
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
			// si le jour est l'objet d'un echange, on tient compte de l'échange
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
/* recup dans un tableau des rtt planifiées  pour tous les users */
/**************************************************/
function recup_tableau_rtt_planifiees($mois, $first_jour, $year, $mysql_link )
{
	$tab_rtt_planifiees=array();  //tableau indexé dont la clé est le login_user
					// il contient pour chaque clé login : un tableau ($tab_user_grille) indexé dont la 
					// clé est la date_fin_grille.
					// qui contient lui meme pour chaque clé : un tableau ($tab_user_rtt) qui contient enfin 
					// les infos pour le matin et l'après midi ('Y' si rtt, 'N' sinon) sur 2 semaines 
					// ( du sem_imp_lu_am au sem_p_ve_pm ) + la date de début et de fin de la grille
					
	$tab_user_grille=array();
	$tab_user_rtt=array();
	
	// construction du tableau $tab_rtt_planifie:
	$req_artt_login="SELECT DISTINCT(a_login) FROM conges_artt ";
	$res_artt_login = mysql_query($req_artt_login, $mysql_link) or die("ERREUR : recup_tableau_rtt_planifiees() <br>\n$req_artt_login<br>\n".mysql_error());
	//$num_artt_login = mysql_num_rows($res_artt_login);
	while($result_artt_login = mysql_fetch_array($res_artt_login)) // pour chaque login trouvé
	{
		$sql_artt_login=$result_artt_login["a_login"];
		$tab_user_grille=array();
		
		$req_artt = "SELECT * FROM conges_artt WHERE a_login='$sql_artt_login' ";
		$res_artt = mysql_query($req_artt, $mysql_link) or die("ERREUR : recup_tableau_rtt_planifiees() <br>\n$req_artt<br>\n".mysql_error());
		$num_artt = mysql_num_rows($res_artt);
		while($result_artt = mysql_fetch_array($res_artt))
		{
			$tab_user_rtt=array();
			$sql_date_fin_grille=$result_artt["a_date_fin_grille"];
			$key_grille=$sql_date_fin_grille ;

			$tab_user_rtt["login"]=$sql_artt_login;
			
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
			$tab_user_rtt["sem_imp_sa_am"]=$result_artt["sem_imp_sa_am"];
			$tab_user_rtt["sem_imp_sa_pm"]=$result_artt["sem_imp_sa_pm"];
			$tab_user_rtt["sem_imp_di_am"]=$result_artt["sem_imp_di_am"];
			$tab_user_rtt["sem_imp_di_pm"]=$result_artt["sem_imp_di_pm"];
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
			$tab_user_rtt["sem_p_sa_am"]=$result_artt["sem_p_sa_am"];
			$tab_user_rtt["sem_p_sa_pm"]=$result_artt["sem_p_sa_pm"];
			$tab_user_rtt["sem_p_di_am"]=$result_artt["sem_p_di_am"];
			$tab_user_rtt["sem_p_di_pm"]=$result_artt["sem_p_di_pm"];

			$tab_user_rtt["date_debut_grille"]=$result_artt["a_date_debut_grille"];
			$tab_user_rtt["date_fin_grille"]=$result_artt["a_date_fin_grille"];
			
			//array_walk ($tab_user_rtt, 'test_print_array');
			$tab_user_grille[$key_grille]=$tab_user_rtt;
		}
		//array_walk ($tab_user_grille, 'test_print_array');
		$tab_rtt_planifiees[$sql_artt_login]=$tab_user_grille;
	}
	//array_walk ($tab_rtt_planifiees, 'test_print_array');
	return $tab_rtt_planifiees;
}


// affiche une liste déroulante des jours du mois : la variable est $new_jour
function affiche_selection_new_jour($default)
{
	echo "<select name=\"new_jour\" >\n";
	for($i=1; $i<10; $i++)
	{
		if($default=="0$i")
			echo "<option value=\"0$i\" selected >0$i</option>\n";
		else
			echo "<option value=\"0$i\">0$i</option>\n";
	}
	for($i=10; $i<32; $i++)
	{
		if($default=="$i")
			echo "<option value=\"$i\" selected >$i</option>\n";
		else
			echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>\n";
}

// affiche une liste déroulante des mois de l'année : la variable est $new_mois
function affiche_selection_new_mois($default)
{
	echo "<select name=\"new_mois\" >\n";
	for($i=1; $i<10; $i++)
	{
		echo "$default : $i<br>\n";
		if($default=="0$i")
			echo "<option value=\"0$i\" selected >0$i</option>\n";
		else
			echo "<option value=\"0$i\">0$i</option>\n";
	}
	for($i=10; $i<13; $i++)
	{
		if($default=="$i")
			echo "<option value=\"$i\" selected >$i</option>\n";
		else
			echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>\n";
}

// affiche une liste déroulante d'année : la variable est $new_year
function affiche_selection_new_year($an_debut, $an_fin, $default)
{
	echo "<select name=\"new_year\" >\n";
	for($i=$an_debut; $i<$an_fin+1; $i++)
	{
		if($default=="$i")
			echo "<option value=\"$i\" selected >$i</option>\n";
		else
			echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>\n";
}

// met la date aaaa-mm-jj dans le format jj-mm-aaaa
function eng_date_to_fr($une_date)
{
 return substr($une_date, 8)."-".substr($une_date, 5, 2)."-".substr($une_date, 0, 4);       
    
}

// met la date jj-mm-aaaa dans le format aaaa-mm-jj
function fr_date_to_eng($une_date)
{
 return substr($une_date, 6)."-".substr($une_date, 3, 2)."-".substr($une_date, 0, 2);       
    
}


// affichage de la cellule correspondant au jour dans les calendrier de saisie (demande de conges, etc ...)
function affiche_cellule_jour_cal_saisie($login, $j_timestamp, $td_second_class, $result)
{
global $session;
global $link;

	//echo "$j_timestamp, $year, $mois, $j, $td_second_class<br>\n";
	$date_j=date("Y-m-d", $j_timestamp);
	$j=date("d", $j_timestamp);

	$class_am="travail_am";
	$class_pm="travail_pm";

	// recup des infos ARTT ou Temps Partiel :
	//recup_infos_artt_du_jour($sql_login, $j_timestamp, $val_matin, $val_aprem);
	recup_infos_artt_du_jour($login, $j_timestamp, $val_matin, $val_aprem);
	
	//## AFICHAGE ##
	if($val_matin=="Y") 
	{
		$class_am="rtt_am";
	}
	if($val_aprem=="Y")
	{
		$class_pm = "rtt_pm";
	}
	
	// Si le client est sous IE, on affiche pas les rtt (car IE ne gère pas certains standarts d'appel de classes de feuille e style)
	if( (isset($_SERVER["HTTP_USER_AGENT"])) && (stristr($_SERVER["HTTP_USER_AGENT"], "MSIE")!=FALSE) )
		echo "<td  class=\"cal-saisie\">$j<input type=\"radio\" name=\"$result\" value=\"$date_j\"></td>";
	else
		echo "<td  class=\"cal-saisie $td_second_class $class_am $class_pm\">$j<input type=\"radio\" name=\"$result\" value=\"$date_j\"></td>";
}

// recup de la liste des users des groupes dont $resp_login est responsable 
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_users_des_groupes_du_resp($resp_login)
{
	global $PHP_SELF, $link;
	global $session, $session_username;

	$list_users="";
	
	$list_groups=get_list_groupes_du_resp($resp_login);
	if($list_groups!="") // si $resp_login est responsable d'au moins un groupe
	{
		$sql="SELECT DISTINCT(gu_login) FROM conges_groupe_users WHERE gu_gid IN ($list_groups) ORDER BY gu_login ";
		$ReqLog1 = mysql_query($sql, $link) or die("ERREUR : get_list_users_des_groupes_du_resp() : ".mysql_error());
		while ($resultat1 = mysql_fetch_array($ReqLog1)) 
		{
			$current_login=$resultat1["gu_login"];
			if($list_users=="")
				$list_users="'$current_login'";
			else
				$list_users=$list_users.", '$current_login'";
		}
	}
	return $list_users;

}

// recup de la liste des groupes dont $resp_login est responsable 
// renvoit une liste de group_id séparés par des virgules
function get_list_groupes_du_resp($resp_login)
{
	global $PHP_SELF, $link;
	global $session, $session_username;

	$list_group="";
	
	$sql="SELECT gr_gid FROM conges_groupe_resp WHERE gr_login='$resp_login' ORDER BY gr_gid";
	$ReqLog1 = mysql_query($sql, $link) or die("ERREUR : get_list_groupes_du_resp() : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$current_group=$resultat1["gr_gid"];
		if($list_group=="")
			$list_group="$current_group";
		else
			$list_group=$list_group.", $current_group";
	}
	return $list_group;
}

// recup de la liste des users des groupes dont $user_login est membre 
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_users_des_groupes_du_user($user_login, $link)
{
	global $PHP_SELF;
	global $session;

	$list_users="";
	
	$list_groups=get_list_groupes_du_user($user_login, $link);
	if($list_groups!="") // si $user_login est membre d'au moins un groupe
	{
		$sql="SELECT DISTINCT(gu_login) FROM conges_groupe_users WHERE gu_gid IN ($list_groups) ORDER BY gu_login ";
		$ReqLog1 = mysql_query($sql, $link) or die("ERREUR : get_list_users_des_groupes_du_user() : <br>\n".mysql_error());
		while ($resultat1 = mysql_fetch_array($ReqLog1)) 
		{
			$current_login=$resultat1["gu_login"];
			if($list_users=="")
				$list_users="'$current_login'";
			else
				$list_users=$list_users.", '$current_login'";
		}
	}
	return $list_users;

}

// recup de la liste des groupes dont $resp_login est membre 
// renvoit une liste de group_id séparés par des virgules
function get_list_groupes_du_user($user_login, $link)
{
	global $PHP_SELF;
	global $session;

	$list_group="";
	
	$sql="SELECT gu_gid FROM conges_groupe_users WHERE gu_login='$user_login' ORDER BY gu_gid";
	$ReqLog1 = mysql_query($sql, $link) or die("ERREUR : get_list_groupes_du_user() : <br>\n".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$current_group=$resultat1["gu_gid"];
		if($list_group=="")
			$list_group="$current_group";
		else
			$list_group=$list_group.", $current_group";
	}
	return $list_group;
}


function valid_ldap_user($username)
{
/* fonction utilisée avec le mode d'authentification ldap.
   En effet, si un utilisateur (enregistré dans le ldap) tente de se
connecter alors qu'il n'a pas de compte dans
   la base, il n'y a aucun message qui lui indique !
   
   Retourne TRUE, si tout est ok... ($username dans la table conges_users)
   False, sinon

*/ 
	// connexion MySQL + selection de la database sur le serveur
	$mysql_link=connexion_mysql();
		
	$req = "SELECT COUNT(*) FROM conges_users WHERE u_login='$username'";
	$res = mysql_query($req);
	$cpt = mysql_fetch_array($res);
	$cpt = $cpt[0];

	if ($cpt == 0)
		return FALSE;
	else
		return TRUE;
	
} 


// verifie si un user est responasble ou pas
// renvoit TRUE si le login est responsable dans la table conges_users, FALSE sinon.
//function is_resp($login, $link)
function is_resp($login, $mysql_link)
{
	// recup de qq infos sur le user
	$select_info="SELECT u_is_resp FROM conges_users WHERE u_login='$login' ";
	$ReqLog_info = mysql_query($select_info, $mysql_link) or die("ERREUR : mysql_query : ".$select_info." --> ".mysql_error());
	$resultat_info = mysql_fetch_array($ReqLog_info);
	$sql_is_resp=$resultat_info["u_is_resp"];
	
	if($sql_is_resp=='Y')
		return TRUE;
	else
		return FALSE;
}


// verifie si un user est administrateur ou pas
// renvoit TRUE si le login est administrateur dans la table conges_users, FALSE sinon.
function is_admin($login, $mysql_link)
{
	// recup de qq infos sur le user
	$select_info="SELECT u_is_admin FROM conges_users WHERE u_login='$login' ";
	$ReqLog_info = mysql_query($select_info, $mysql_link) or die("ERREUR : mysql_query : ".$select_info." --> ".mysql_error());
	$resultat_info = mysql_fetch_array($ReqLog_info);
	$sql_is_admin=$resultat_info["u_is_admin"];
	
	if($sql_is_admin=='Y')
		return TRUE;
	else
		return FALSE;
}


// verifie si un administrateur est responsable de users ou pas
// renvoit TRUE si le login est responsable dans la table conges_users, FALSE sinon.
function admin_is_responsable($login, $mysql_link)
{
	// recup de qq infos sur le responsable
	$select_info="SELECT u_is_resp FROM conges_users WHERE u_login='$login' ";
	$ReqLog_info = mysql_query($select_info, $mysql_link) or die("ERREUR : mysql_query : ".$select_info." --> ".mysql_error());
	$resultat_info = mysql_fetch_array($ReqLog_info);
	$sql_is_resp=$resultat_info["u_is_resp"];
	
	if($sql_is_resp=='Y')
		return TRUE;
	else
		return FALSE;
}



// on insert une nouvelle periode dans la table periode
// retourne le resultat du mysql_query (TRUE ou FALSE)
function insert_dans_periode($login, $date_deb, $demi_jour_deb, $date_fin, $demi_jour_fin, $nb_jours, $commentaire, $type, $etat, $mysql_link)
{
	// Récupération du + grand p_num (+ grand numero identifiant de conges)
	$sql1 = "SELECT max(p_num) FROM conges_periode" ;
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : insert_dans_periode() :<br>\n $sql1 <br>\n".mysql_error());
	if(mysql_result($ReqLog1, 0))
		$num_new_demande = mysql_result($ReqLog1, 0)+1;
	else
		$num_new_demande = 0;

	$sql2 = "INSERT INTO conges_periode 
			SET p_login='$login', 
			p_date_deb='$date_deb', p_demi_jour_deb='$demi_jour_deb', 
			p_date_fin='$date_fin', p_demi_jour_fin='$demi_jour_fin', 
			p_nb_jours='$nb_jours', p_commentaire='$commentaire', 
			p_type='$type', p_etat='$etat', p_num='$num_new_demande' " ;

	$result = mysql_query($sql2, $mysql_link) or die("ERREUR : insert_dans_periode() :<br>\n $sql2 <br>\n".mysql_error());
	return $result;
}

?>
