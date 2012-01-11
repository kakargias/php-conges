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


defined( '_PHP_CONGES' ) or die( 'Restricted access' );

include_once  INCLUDE_PATH .'sql.class.php';
include_once  INCLUDE_PATH .'get_text.php';

// affichage du calendrier avec les case à cocher, du mois du début du congés
function  affiche_calendrier_saisie_date_debut($user_login, $year, $mois,  $DEBUG=FALSE)
{

	$jour_today=date("j");
	$jour_today_name=date("D");

	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date("F", $first_jour_mois_timestamp);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
	//$first_jour_mois_name=date("D", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)

	echo '<table cellpadding="0" cellspacing="0" border="1" width="250" bgcolor="'.$_SESSION['config']['semaine_bgcolor'].'">';
	/* affichage  2 premieres lignes */
	echo '	<tr align="center" bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'"><td colspan=7 class="titre"> '.$mois_name.' '.$year.' </td></tr>' ;
	echo '	<tr bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'">';
	echo '		<td class="cal-saisie2">'. _('lundi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('mardi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('mercredi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('jeudi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('vendredi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('samedi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('dimanche_1c') .'</td>';
	echo '	</tr>' ;

	/* affichage ligne 1 du mois*/
	echo '<tr>';
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++)
	{
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE)) || (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++)
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_debut', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 2 du mois*/
	echo '<tr>';
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_debut', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 3 du mois*/
	echo '<tr>';
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_debut', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 4 du mois*/
	echo '<tr>';
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_debut', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo '<tr>';
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_debut', $DEBUG);
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) {
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	/* affichage ligne 6 du mois (derniere ligne)*/
	echo '<tr>';
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_debut', $DEBUG);
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++)
	{
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)))
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	echo '</table>';
}


// affichage du calendrier avec les case à cocher, du mois de fin du congés
function  affiche_calendrier_saisie_date_fin($user_login, $year, $mois, $DEBUG=FALSE)
{


	$jour_today=date('j');
	$jour_today_name=date('D');

	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date('F', $first_jour_mois_timestamp);
	$mois_name=date_fr('F', $first_jour_mois_timestamp);
	//$first_jour_mois_name=date('D', $first_jour_mois_timestamp);
	$first_jour_mois_rang=date('w', $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)

	echo '<table cellpadding="0" cellspacing="0" border="1" width="250" bgcolor="'.$_SESSION['config']['semaine_bgcolor'].'">';
	/* affichage  2 premieres lignes */
	echo '	<tr align="center" bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'"><td colspan=7 class="titre"> '.$mois_name.' '.$year.' </td></tr>' ;
	echo '	<tr align="center"  bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'">' ;
	echo '		<td class="cal-saisie2">'. _('lundi_1c') .'</td>' ;
	echo '		<td class="cal-saisie2">'. _('mardi_1c') .'</td>' ;
	echo '		<td class="cal-saisie2">'. _('mercredi_1c') .'</td>' ;
	echo '		<td class="cal-saisie2">'. _('jeudi_1c') .'</td>' ;
	echo '		<td class="cal-saisie2">'. _('vendredi_1c') .'</td>' ;
	echo '		<td class="cal-saisie2">'. _('samedi_1c') .'</td>' ;
	echo '		<td class="cal-saisie2">'. _('dimanche_1c') .'</td>' ;
	echo '	</tr>' ;


	/* affichage ligne 1 du mois*/
	echo '<tr>';
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++)
	{
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE)) || (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) {
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_fin', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 2 du mois*/
	echo '<tr>';
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_fin', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 3 du mois*/
	echo '<tr>';
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_fin', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 4 du mois*/
	echo '<tr>';
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) {
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_fin', $DEBUG);
	}
	echo '</tr>';

	/* affichage ligne 5 du mois (peut etre la derniere ligne)*/
	echo '<tr>';
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_fin', $DEBUG);
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++)
	{
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	/* affichage ligne 6 du mois (derniere ligne)*/
	echo '<tr>';
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++)
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		affiche_cellule_jour_cal_saisie($user_login, $j_timestamp, $td_second_class, 'new_fin', $DEBUG);
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++)
	{
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	echo '</table>';
}




// affichage du calendrier du mois avec les case à cocher sur les jour d'absence
function  affiche_calendrier_saisie_jour_absence($user_login, $year, $mois, $DEBUG=FALSE)
{
	$jour_today=date('j');
	$jour_today_name=date('D');

	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
	$mois_name=date_fr('F', $first_jour_mois_timestamp);
	$first_jour_mois_rang=date('w', $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)

//	echo '<table cellpadding="0" cellspacing="0" border="1" width="250" bgcolor='.$_SESSION['config']['semaine_bgcolor'].'>';
	echo '<table cellpadding="0" cellspacing="0" border="1" bgcolor='.$_SESSION['config']['semaine_bgcolor'].'>';
	/* affichage  2 premieres lignes */
	echo '	<tr align="center" bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'"><td colspan=7 class="titre"> '.$mois_name.' '.$year.' </td></tr>' ;
	echo '	<tr bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'">';
	echo '		<td class="cal-saisie2">'. _('lundi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('mardi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('mercredi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('jeudi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('vendredi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('samedi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('dimanche_1c') .'</td>';
	echo '	</tr>' ;

	/* affichage ligne 1 du mois*/
	echo '<tr>';
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++)
	{
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
//		echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie2\">-</td>";
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++)
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
//			echo "<td bgcolor=$bgcolor width=\"14%\" class=\"cal-saisie\">$j</td>";
			echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$j.'</td>';
		}
		else
		{
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $j, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 2 du mois*/
	echo '<tr>';
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==14-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==15-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 3 du mois*/
	echo '<tr>';
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==21-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==22-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 4 du mois*/
	echo '<tr>';
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==28-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==29-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo '<tr>';
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++)
	{
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	/* affichage ligne 6 du mois (derniere ligne)*/
	echo '<tr>';
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++)
	{
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	echo '</table>';
}

function affiche_cellule_calendrier_echange_absence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $j, $DEBUG=FALSE)
{
	$bgcolor=$_SESSION['config']['semaine_bgcolor'];
	if(($val_matin=='Y')&&($val_aprem=='Y'))
	{
		$bgcolor=$_SESSION['config']['temps_partiel_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$j.'<input type="radio" name="new_debut" value="'.$year.'-'.$mois.'-'.$j.'-j"></td>';
	}
	elseif(($val_matin=='Y')&&($val_aprem=='N'))
	{
		$bgcolor=$_SESSION['config']['temps_partiel_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-day_semaine_rtt_am_travail_pm_w35">'.$j.'<input type="radio" name="new_debut" value="'.$year.'-'.$mois.'-'.$j.'-a"></td>';
	}
	elseif(($val_matin=='N')&&($val_aprem=='Y'))
	{
		$bgcolor=$_SESSION['config']['temps_partiel_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-day_semaine_travail_am_rtt_pm_w35">'.$j.'<input type="radio" name="new_debut" value="'.$year.'-'.$mois.'-'.$j.'-p"></td>';
	}
	else
	{
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$j.'</td>';
	}
}

// affichage du calendrier du mois avec les case à cocher sur les jour de présence
function  affiche_calendrier_saisie_jour_presence($user_login, $year, $mois, $DEBUG=FALSE)
{
	$jour_today=date('j');
	$jour_today_name=date('D');

	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date('F', $first_jour_mois_timestamp);
	$mois_name=date_fr('F', $first_jour_mois_timestamp);
	//$first_jour_mois_name=date('D', $first_jour_mois_timestamp);
	$first_jour_mois_rang=date('w', $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)

	echo '<table cellpadding="0" cellspacing="0" border="1" width="250" bgcolor="'.$_SESSION['config']['semaine_bgcolor'].'">';
	/* affichage  2 premieres lignes */
	echo '	<tr align="center" bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'"><td colspan=7 class="titre"> '.$mois_name.' '.$year.' </td></tr>' ;
	echo '	<tr bgcolor="'.$_SESSION['config']['light_grey_bgcolor'].'">';
	echo '		<td class="cal-saisie2">'. _('lundi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('mardi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('mercredi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('jeudi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('vendredi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('samedi_1c') .'</td>';
	echo '		<td class="cal-saisie2">'. _('dimanche_1c') .'</td>';
	echo '	</tr>' ;


	/* affichage ligne 1 du mois*/
	echo '<tr>';
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++)
	{
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	// affichage des cellules cochables du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++)
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' width="14%" class="cal-saisie">'.$j.'</td>';
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$j,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem, $DEBUG);
			affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $j, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 2 du mois*/
	echo '<tr>';
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==14-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==15-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' width="14%" class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem, $DEBUG);
			affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 3 du mois*/
	echo '<tr>';
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==21-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==22-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' width="14%" class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem, $DEBUG);
			affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 4 du mois*/
	echo '<tr>';
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==28-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==29-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' width="14%" class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	echo '</tr>';

	/* affichage ligne 5 du mois (peut etre la derniere ligne)*/
	echo '<tr>';
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' width="14%" class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++)
	{
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	/* affichage ligne 6 du mois (derniere ligne)*/
	echo '<tr>';
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++)
	{
		$val_matin='';
		$val_aprem='';
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);

		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé ou jour chomé
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE))
			|| (est_chome($j_timestamp)==TRUE) || (est_ferme($j_timestamp)==TRUE) )
		{
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			echo '<td bgcolor='.$bgcolor.' width="14%" class="cal-saisie">'.$i.'</td>';
		}
		else
		{
			$j_timestamp=mktime (0,0,0,$mois,$i,$year);
			recup_infos_artt_du_jour($user_login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);
			affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $i, $DEBUG);
		}
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++)
	{
		// si on est samedi et sam non travaillé ou dimanche et dim non travaillé
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE))
			|| (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie2">-</td>';
	}
	echo '</tr>';

	echo '</table>';
}


function affiche_cellule_calendrier_echange_presence_saisie_semaine($val_matin, $val_aprem, $year, $mois, $j, $DEBUG=FALSE)
{
	$bgcolor=$_SESSION['config']['semaine_bgcolor'];
	if(($val_matin=='Y')&&($val_aprem=='Y'))  // rtt le matin et l'apres midi !
	{
		$bgcolor=$_SESSION['config']['temps_partiel_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$j.'</td>';
	}
	elseif(($val_matin=='Y')&&($val_aprem=='N'))
	{
		$bgcolor=$_SESSION['config']['temps_partiel_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-day_semaine_rtt_am_travail_pm_w35">'.$j.'<input type="radio" name="new_fin" value="'.$year.'-'.$mois.'-'.$j.'-p"></td>';
	}
	elseif(($val_matin=='N')&&($val_aprem=='Y'))
	{
		$bgcolor=$_SESSION['config']['temps_partiel_bgcolor'];
		echo '<td bgcolor='.$bgcolor.' class="cal-day_semaine_travail_am_rtt_pm_w35">'.$j.'<input type="radio" name="new_fin" value="'.$year.'-'.$mois.'-'.$j.'-a"></td>';
	}
	else
	{
		echo '<td bgcolor='.$bgcolor.' class="cal-saisie">'.$j.'<input type="radio" name="new_fin" value="'.$year.'-'.$mois.'-'.$j.'-j"></td>';
	}
}




// saisie de la grille des jours d'abscence ARTT ou temps partiel:
function saisie_jours_absence_temps_partiel($login,  $DEBUG=FALSE)
{

	/* initialisation des variables **************/
	$checked_option_sem_imp_lu_am='';
	$checked_option_sem_imp_lu_pm='';
	$checked_option_sem_imp_ma_am='';
	$checked_option_sem_imp_ma_pm='';
	$checked_option_sem_imp_me_am='';
	$checked_option_sem_imp_me_pm='';
	$checked_option_sem_imp_je_am='';
	$checked_option_sem_imp_je_pm='';
	$checked_option_sem_imp_ve_am='';
	$checked_option_sem_imp_ve_pm='';
	$checked_option_sem_imp_sa_am='';
	$checked_option_sem_imp_sa_pm='';
	$checked_option_sem_imp_di_am='';
	$checked_option_sem_imp_di_pm='';

	$checked_option_sem_p_lu_am='';
	$checked_option_sem_p_lu_pm='';
	$checked_option_sem_p_ma_am='';
	$checked_option_sem_p_ma_pm='';
	$checked_option_sem_p_me_am='';
	$checked_option_sem_p_me_pm='';
	$checked_option_sem_p_je_am='';
	$checked_option_sem_p_je_pm='';
	$checked_option_sem_p_ve_am='';
	$checked_option_sem_p_ve_pm='';
	$checked_option_sem_p_sa_am='';
	$checked_option_sem_p_sa_pm='';
	$checked_option_sem_p_di_am='';
	$checked_option_sem_p_di_pm='';
	/*********************************************/

	// recup des données de la dernière table artt du user :
	$sql1 = 'SELECT * FROM conges_artt WHERE a_login=\''.SQL::quote($login).'\' AND a_date_fin_grille=\'9999-12-31\' '  ;
	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array()) {
		if($resultat1['sem_imp_lu_am']=='Y') $checked_option_sem_imp_lu_am=' checked';
		if($resultat1['sem_imp_lu_pm']=='Y') $checked_option_sem_imp_lu_pm=' checked';
		if($resultat1['sem_imp_ma_am']=='Y') $checked_option_sem_imp_ma_am=' checked';
		if($resultat1['sem_imp_ma_pm']=='Y') $checked_option_sem_imp_ma_pm=' checked';
		if($resultat1['sem_imp_me_am']=='Y') $checked_option_sem_imp_me_am=' checked';
		if($resultat1['sem_imp_me_pm']=='Y') $checked_option_sem_imp_me_pm=' checked';
		if($resultat1['sem_imp_je_am']=='Y') $checked_option_sem_imp_je_am=' checked';
		if($resultat1['sem_imp_je_pm']=='Y') $checked_option_sem_imp_je_pm=' checked';
		if($resultat1['sem_imp_ve_am']=='Y') $checked_option_sem_imp_ve_am=' checked';
		if($resultat1['sem_imp_ve_pm']=='Y') $checked_option_sem_imp_ve_pm=' checked';
		if($resultat1['sem_imp_sa_am']=='Y') $checked_option_sem_imp_sa_am=' checked';
		if($resultat1['sem_imp_sa_pm']=='Y') $checked_option_sem_imp_sa_pm=' checked';
		if($resultat1['sem_imp_di_am']=='Y') $checked_option_sem_imp_di_am=' checked';
		if($resultat1['sem_imp_di_pm']=='Y') $checked_option_sem_imp_di_pm=' checked';

		if($resultat1['sem_p_lu_am']=='Y') $checked_option_sem_p_lu_am=' checked';
		if($resultat1['sem_p_lu_pm']=='Y') $checked_option_sem_p_lu_pm=' checked';
		if($resultat1['sem_p_ma_am']=='Y') $checked_option_sem_p_ma_am=' checked';
		if($resultat1['sem_p_ma_pm']=='Y') $checked_option_sem_p_ma_pm=' checked';
		if($resultat1['sem_p_me_am']=='Y') $checked_option_sem_p_me_am=' checked';
		if($resultat1['sem_p_me_pm']=='Y') $checked_option_sem_p_me_pm=' checked';
		if($resultat1['sem_p_je_am']=='Y') $checked_option_sem_p_je_am=' checked';
		if($resultat1['sem_p_je_pm']=='Y') $checked_option_sem_p_je_pm=' checked';
		if($resultat1['sem_p_ve_am']=='Y') $checked_option_sem_p_ve_am=' checked';
		if($resultat1['sem_p_ve_pm']=='Y') $checked_option_sem_p_ve_pm=' checked';
		if($resultat1['sem_p_sa_am']=='Y') $checked_option_sem_p_sa_am=' checked';
		if($resultat1['sem_p_sa_pm']=='Y') $checked_option_sem_p_sa_pm=' checked';
		if($resultat1['sem_p_di_am']=='Y') $checked_option_sem_p_di_am=' checked';
		if($resultat1['sem_p_di_pm']=='Y') $checked_option_sem_p_di_pm=' checked';
		$date_deb_grille=$resultat1['a_date_debut_grille'];
		$date_fin_grille=$resultat1['a_date_fin_grille'];
	}


	echo '<h4>'. _('admin_temps_partiel_titre') .' :</h4>';
	echo '<table cellpadding="0" cellspacing="0" border="0">';
	echo '<tr>';
	echo '<td>';
		//tableau semaines impaires
		echo '<b><u>'. _('admin_temps_partiel_sem_impaires') .' :</u></b><br>';
		$tab_checkbox_sem_imp=array();
		$imp_lu_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_lu_am]" value="Y" '.$checked_option_sem_imp_lu_am.'>';
		$imp_lu_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_lu_pm]" value="Y" '.$checked_option_sem_imp_lu_pm.'>';
		$imp_ma_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_ma_am]" value="Y" '.$checked_option_sem_imp_ma_am.'>';
		$imp_ma_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_ma_pm]" value="Y" '.$checked_option_sem_imp_ma_pm.'>';
		$imp_me_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_me_am]" value="Y" '.$checked_option_sem_imp_me_am.'>';
		$imp_me_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_me_pm]" value="Y" '.$checked_option_sem_imp_me_pm.'>';
		$imp_je_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_je_am]" value="Y" '.$checked_option_sem_imp_je_am.'>';
		$imp_je_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_je_pm]" value="Y" '.$checked_option_sem_imp_je_pm.'>';
		$imp_ve_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_ve_am]" value="Y" '.$checked_option_sem_imp_ve_am.'>';
		$imp_ve_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_ve_pm]" value="Y" '.$checked_option_sem_imp_ve_pm.'>';
		if($_SESSION['config']['samedi_travail']==TRUE)
		{
			$imp_sa_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_sa_am]" value="Y" '.$checked_option_sem_imp_sa_am.'>';
			$imp_sa_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_sa_pm]" value="Y" '.$checked_option_sem_imp_sa_pm.'>';
		}
		if($_SESSION['config']['dimanche_travail']==TRUE)
		{
			$imp_di_am='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_di_am]" value="Y" '.$checked_option_sem_imp_di_am.'>';
			$imp_di_pm='<input type="checkbox" name="tab_checkbox_sem_imp[sem_imp_di_pm]" value="Y" '.$checked_option_sem_imp_di_pm.'>';
		}

		echo '<table cellpadding="1" class="tablo">';
		echo '<tr align="center">';
			echo '<td></td>';
			echo '<td class="histo">'. _('lundi') .'</td>';
			echo '<td class="histo">'. _('mardi') .'</td>';
			echo '<td class="histo">'. _('mercredi') .'</td>';
			echo '<td class="histo">'. _('jeudi') .'</td>';
			echo '<td class="histo">'. _('vendredi') .'</td>';
			if($_SESSION['config']['samedi_travail']==TRUE)
				echo '<td class="histo">'. _('samedi') .'</td>';
			if($_SESSION['config']['dimanche_travail']==TRUE)
				echo '<td class="histo">'. _('dimanche') .'</td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td class="histo">'. _('admin_temps_partiel_am') .'</td>';
			echo '<td class="histo">'.$imp_lu_am.'</td>';
			echo '<td class="histo">'.$imp_ma_am.'</td>';
			echo '<td class="histo">'.$imp_me_am.'</td>';
			echo '<td class="histo">'.$imp_je_am.'</td>';
			echo '<td class="histo">'.$imp_ve_am.'</td>';
			if($_SESSION['config']['samedi_travail']==TRUE)
				echo '<td class="histo">'.$imp_sa_am.'</td>';
			if($_SESSION['config']['dimanche_travail']==TRUE)
				echo '<td class="histo">'.$imp_di_am.'</td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td class="histo">'. _('admin_temps_partiel_pm') .'</td>';
			echo '<td class="histo">'.$imp_lu_pm.'</td>';
			echo '<td class="histo">'.$imp_ma_pm.'</td>';
			echo '<td class="histo">'.$imp_me_pm.'</td>';
			echo '<td class="histo">'.$imp_je_pm.'</td>';
			echo '<td class="histo">'.$imp_ve_pm.'</td>';
			if($_SESSION['config']['samedi_travail']==TRUE)
				echo '<td class="histo">'.$imp_sa_pm.'</td>';
			if($_SESSION['config']['dimanche_travail']==TRUE)
				echo '<td class="histo">'.$imp_di_pm.'</td>';
		echo '</tr>';
		echo '</table>';

	echo '</td>';
	echo ' <td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="2" border="0" vspace="0" hspace="0"></td>';
	echo ' <td>';

		//tableau semaines paires
		echo '<b><u>'. _('admin_temps_partiel_sem_paires') .':</u></b><br>';
		$tab_checkbox_sem_p=array();
		$p_lu_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_lu_am]" value="Y" '.$checked_option_sem_p_lu_am.'>';
		$p_lu_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_lu_pm]" value="Y" '.$checked_option_sem_p_lu_pm.'>';
		$p_ma_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_ma_am]" value="Y" '.$checked_option_sem_p_ma_am.'>';
		$p_ma_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_ma_pm]" value="Y" '.$checked_option_sem_p_ma_pm.'>';
		$p_me_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_me_am]" value="Y" '.$checked_option_sem_p_me_am.'>';
		$p_me_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_me_pm]" value="Y" '.$checked_option_sem_p_me_pm.'>';
		$p_je_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_je_am]" value="Y" '.$checked_option_sem_p_je_am.'>';
		$p_je_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_je_pm]" value="Y" '.$checked_option_sem_p_je_pm.'>';
		$p_ve_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_ve_am]" value="Y" '.$checked_option_sem_p_ve_am.'>';
		$p_ve_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_ve_pm]" value="Y" '.$checked_option_sem_p_ve_pm.'>';
		$p_sa_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_sa_am]" value="Y" '.$checked_option_sem_p_sa_am.'>';
		$p_sa_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_sa_pm]" value="Y" '.$checked_option_sem_p_sa_pm.'>';
		$p_di_am='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_di_am]" value="Y" '.$checked_option_sem_p_di_am.'>';
		$p_di_pm='<input type="checkbox" name="tab_checkbox_sem_p[sem_p_di_pm]" value="Y" '.$checked_option_sem_p_di_pm.'>';

		echo '<table cellpadding="1"  class="tablo">';
		echo '<tr align="center">';
			echo '<td></td>';
			echo '<td class="histo">'. _('lundi') .'</td>';
			echo '<td class="histo">'. _('mardi') .'</td>';
			echo '<td class="histo">'. _('mercredi') .'</td>';
			echo '<td class="histo">'. _('jeudi') .'</td>';
			echo '<td class="histo">'. _('vendredi') .'</td>';
			if($_SESSION['config']['samedi_travail']==TRUE)
				echo '<td class="histo">'. _('samedi') .'</td>';
			if($_SESSION['config']['dimanche_travail']==TRUE)
				echo '<td class="histo">'. _('dimanche') .'</td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td class="histo">'. _('admin_temps_partiel_am') .'</td>';
			echo '<td class="histo">'.$p_lu_am.'</td>';
			echo '<td class="histo">'.$p_ma_am.'</td>';
			echo '<td class="histo">'.$p_me_am.'</td>';
			echo '<td class="histo">'.$p_je_am.'</td>';
			echo '<td class="histo">'.$p_ve_am.'</td>';
			if($_SESSION['config']['samedi_travail']==TRUE)
				echo '<td class="histo">'.$p_sa_am.'</td>';
			if($_SESSION['config']['dimanche_travail']==TRUE)
				echo '<td class="histo">'.$p_di_am.'</td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td class="histo">'. _('admin_temps_partiel_pm') .'</td>';
			echo '<td class="histo">'.$p_lu_pm.'</td>';
			echo '<td class="histo">'.$p_ma_pm.'</td>';
			echo '<td class="histo">'.$p_me_pm.'</td>';
			echo '<td class="histo">'.$p_je_pm.'</td>';
			echo '<td class="histo">'.$p_ve_pm.'</td>';
			if($_SESSION['config']['samedi_travail']==TRUE)
				echo '<td class="histo">'.$p_sa_pm.'</td>';
			if($_SESSION['config']['dimanche_travail']==TRUE)
				echo '<td class="histo">'.$p_di_pm.'</td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
	echo '</tr>';
	echo '<tr align="center">';
	echo '<td colspan="3">';
		$jour_default=date('d');
		$mois_default=date('m');
		$year_default=date('Y');
		echo '<br>'. _('admin_temps_partiel_date_valid') .' :';
		affiche_selection_new_jour($jour_default);  // la variable est $new_jour
		affiche_selection_new_mois($mois_default);  // la variable est $new_mois
		affiche_selection_new_year($year_default-2, $year_default+10, $year_default );  // la variable est $new_year
	echo '</td>';
	echo '</tr>';
	echo '</table>';

}


// retourne le nom du jour de la semaine en francais sur 2 caracteres
function get_j_name_fr_2c($timestamp)
{
	$jour_name_fr_2c=array(0=>'di',1=>'lu', 2=>'ma',3=>'me',4=>'je',5=>'ve',6=>'sa',);

	$jour_num=date('w', $timestamp);
	if (isset($jour_name_fr_2c[$jour_num]))
		return $jour_name_fr_2c[$jour_num];
	else
		return false;
}


//affiche le formulaire de saisie d'une nouvelle demande de conges
function saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $onglet,  $DEBUG=FALSE)
{
//$DEBUG=TRUE;
	if($DEBUG==TRUE) { echo 'user_login = '.$user_login.', year_calendrier_saisie_debut = '.$year_calendrier_saisie_debut.', mois_calendrier_saisie_debut = '.$mois_calendrier_saisie_debut.', year_calendrier_saisie_fin = '.$year_calendrier_saisie_fin.', mois_calendrier_saisie_fin = '.$mois_calendrier_saisie_fin.', onglet = '.$onglet.'<br>';}

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	$mois_calendrier_saisie_debut_prec=0; $year_calendrier_saisie_debut_prec=0;
	$mois_calendrier_saisie_debut_suiv=0; $year_calendrier_saisie_debut_suiv=0;
	$mois_calendrier_saisie_fin_prec=0; $year_calendrier_saisie_fin_prec=0;
	$mois_calendrier_saisie_fin_suiv=0; $year_calendrier_saisie_fin_suiv=0;

	init_tab_jours_fermeture($user_login);

//		echo '<form action="'.$PHP_SELF.'?session='.$session.'" method="POST">' ;
		echo '<form action="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'" method="POST">' ;
//		echo '<form action="'.$PHP_SELF.'?session='.$session.'&login_user='.$user_login.'" method="POST">' ;
		// il faut indiquer le champ de formulaire 'login_user' car il est récupéré par le javascript qui apelle le calcul automatique.
//		echo '<input type="hidden" name="login_user" value="'.$user_login.'">';

			echo '<table cellpadding="0" cellspacing="5" border="0">';
			echo '<tr align="center">';
			echo '<td>';
				echo '<table cellpadding="0" cellspacing="0" border="0">';
				echo '<tr align="center">';
					echo '<td>';
					echo '<fieldset class="cal_saisie">';
						echo '<table cellpadding="0" cellspacing="0" border="0">';
						echo '<tr align="center">';
							echo "<td>\n";
								/******************************************************************/
								// affichage du calendrier de saisie de la date de DEBUT de congès
								/******************************************************************/
								echo '<table cellpadding="0" cellspacing="0" width="250" border="0">';
								echo '<tr>';
									init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
												$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec,
												$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
												$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
												$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
												$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

								// affichage des boutons de défilement
								// recul du mois saisie début
								echo '<td align="center" class="big">';
								echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_prec.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_prec.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin.'&user_login='.$user_login.'&onglet='.$onglet.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simfirs.gif" width="16" height="16" border="0" alt="'. _('divers_mois_precedent') .'" title="'. _('divers_mois_precedent') .'"> ';
								echo '</a>';
								echo '</td>';

								echo '<td align="center" class="big">'. _('divers_debut_maj') .' :</td>';

								// affichage des boutons de défilement
								// avance du mois saisie début
								// si le mois de saisie fin est antérieur ou égal au mois de saisie début, on avance les 2 , sinon on avance que le mois de saisie début
								if( (($year_calendrier_saisie_debut_suiv==$year_calendrier_saisie_fin) && ($mois_calendrier_saisie_debut_suiv>=$mois_calendrier_saisie_fin))
								    || ($year_calendrier_saisie_debut_suiv>$year_calendrier_saisie_fin)  )
									$lien_mois_debut_suivant = $PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_suiv.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_debut_suiv.'&user_login='.$user_login.'&onglet='.$onglet ;
								else
									$lien_mois_debut_suivant = $PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_suiv.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin.'&user_login='.$user_login.'&onglet='.$onglet ;
								echo '<td align="center" class="big">';
								echo '<a href="'.$lien_mois_debut_suivant.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simlast.gif" width="16" height="16" border="0" alt="'. _('divers_mois_suivant') .'" title="'. _('divers_mois_suivant') .'"> ';
								echo '</a>';
								echo '</td>';


								echo '</tr>';
								echo '</table>';
								/*** calendrier saisie date debut ***/
								affiche_calendrier_saisie_date_debut($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut,  $DEBUG);
							echo '</td>';
							/**************************************************/
							/* cellule 2 : boutons radio matin ou après midi */
							echo '<td align="left">';
								echo '<input type="radio" name="new_demi_jour_deb" ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE)
								{
									// attention : IE6 : bug avec les "OnChange" sur les boutons radio!!! (on remplace par OnClick)
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="am" checked><b><u>'. _('form_am') .'</u></b><br><br>';

								echo '<input type="radio" name="new_demi_jour_deb" ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE)
								{
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="pm"><b><u>'. _('form_pm') .'</u></b><br><br>';
							echo '</td>';
							/**************************************************/
						echo '</tr>';
						echo '</table>';
					echo '</fieldset>';
					echo '</td>';
				echo '</tr>';
				echo '<tr align="center">';
					echo '<td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="10" border="0" vspace="0" hspace="0"></td>';
				echo '</tr>';
				echo '<tr align="center">';
					echo '<td>';
					echo '<fieldset class="cal_saisie">';
						echo '<table cellpadding="0" cellspacing="0" border="0">';
						echo '<tr align="center">';
							echo '<td>';
								/******************************************************************/
								// affichage du calendrier de saisie de la date de FIN de congès
								/******************************************************************/
								echo '<table cellpadding="0" cellspacing="0" width="250" border="0">';
								echo '<tr>';
									$mois_calendrier_saisie_fin_prec = $mois_calendrier_saisie_fin==1 ? 12 : $mois_calendrier_saisie_fin-1 ;
									$mois_calendrier_saisie_fin_suiv = $mois_calendrier_saisie_fin==12 ? 1 : $mois_calendrier_saisie_fin+1 ;

								// affichage des boutons de défilement
								// recul du mois saisie fin
								// si le mois de saisie fin est antérieur ou égal au mois de saisie début, on recule les 2 , sinon on recule que le mois de saisie fin
								if( (($year_calendrier_saisie_debut==$year_calendrier_saisie_fin_prec) && ($mois_calendrier_saisie_debut>=$mois_calendrier_saisie_fin_prec))
								    || ($year_calendrier_saisie_debut>$year_calendrier_saisie_fin_prec) )
								    $lien_mois_fin_precedent = ''.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_fin_prec.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_prec.'&user_login='.$user_login.'&onglet='.$onglet;
								else
									$lien_mois_fin_precedent = ''.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_prec.'&user_login='.$user_login.'&onglet='.$onglet;
								echo '<td align="center" class="big">';
								echo '<a href="'.$lien_mois_fin_precedent.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simfirs.gif" width="16" height="16" border="0" alt="'. _('divers_mois_precedent') .'" title="'. _('divers_mois_precedent') .'">';
								echo ' </a>';
								echo '</td>';

								echo '<td align="center" class="big">'. _('divers_fin_maj') .' :</td>';

								// affichage des boutons de défilement
								// avance du mois saisie fin
								echo '<td align="center" class="big">';
								echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_suiv.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_suiv.'&user_login='.$user_login.'&onglet='.$onglet.'">';
								echo ' <img src="'. TEMPLATE_PATH . 'img/simlast.gif" width="16" height="16" border="0" alt="'. _('divers_mois_suivant') .'" title="'. _('divers_mois_suivant') .'"> ';
								echo '</a>';
								echo '</td>';
								echo '</tr>';
								echo '</table>';
								/*** calendrier saisie date fin ***/
								affiche_calendrier_saisie_date_fin($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin,  $DEBUG);
							echo '</td>';
							/**************************************************/
							/* cellule 2 : boutons radio matin ou après midi */
							echo '<td align="left">';
								echo '<input type="radio" name="new_demi_jour_fin" ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE)
								{
									// attention : IE6 : bug avec les "OnChange" sur les boutons radio!!! (on remplace par OnClick)
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="am"><b><u>'. _('form_am') .'</u></b><br><br>';

								echo '<input type="radio" name="new_demi_jour_fin"  ';
								if($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE)
								{
									if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
										echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
									else
										echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
								}
								echo 'value="pm" checked><b><u>'. _('form_pm') .'</u></b><br><br>';
							echo '</td>';
							/**************************************************/
						echo '</tr>';
						echo '</table>';
					echo '</fieldset>';
					echo '</td>';
				echo '</tr>';
				echo '</table>';
			echo '</td>';
			echo '<td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="2" border="0" vspace="0" hspace="0"></td>';
			echo '<td>';

				/*******************/
				/*   formulaire    */
				/*******************/
				echo '<table cellpadding="0" cellspacing="2" border="0" >';
				echo '<tr>';
				echo '<td valign="top">';
					echo '<table cellpadding="2" cellspacing="3" border="0" >';
//					echo '<input type="hidden" name="login_user" value="'.'.$_SESSION['userlogin'].'.'">';
					echo '<input type="hidden" name="login_user" value="'.$user_login.'">';
					echo '<input type="hidden" name="session" value="'.$session.'">';
					// bouton 'compter les jours'
					if($_SESSION['config']['affiche_bouton_calcul_nb_jours_pris']==TRUE)
					{
						echo '<tr><td colspan="2">';
							echo '<input type="button" onclick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;" value="'. _('saisie_conges_compter_jours') .'">';
						echo '</td></tr>';
					}
					// zones de texte
					echo '<tr align="center"><td><b>'. _('saisie_conges_nb_jours') .'</b></td><td><b>'. _('divers_comment_maj_1') .'</b></td></tr>';

					if($_SESSION['config']['disable_saise_champ_nb_jours_pris']==TRUE)  // zone de texte en readonly et grisée
						$text_nb_jours ='<input type="text" name="new_nb_jours" size="10" maxlength="30" value="" style="background-color: #D4D4D4; " readonly="readonly">' ;
					else
						$text_nb_jours ='<input type="text" name="new_nb_jours" size="10" maxlength="30" value="">' ;

					$text_commentaire='<input type="text" name="new_comment" size="25" maxlength="30" value="">' ;
					echo '<tr align="center">';
					echo '<td>'.($text_nb_jours).'</td><td>'.($text_commentaire).'</td>';
					echo '</tr>';
					echo '<tr align="center"><td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="10" border="0" vspace="0" hspace="0"></td><td></td></tr>';
					echo '<tr align="center">';
					echo '<td colspan=2>';
						echo '<input type="hidden" name="user_login" value="'.$user_login.'">';
						echo '<input type="hidden" name="new_demande_conges" value=1>';
						// boutons du formulaire
						echo '<input type="submit" value="'. _('form_submit') .'">   <input type="reset" value="'. _('form_cancel') .'">';
					echo '</td>';
					echo '</tr>';
					echo '</table>';

				echo '</td>';
				/*****************/
				/* boutons radio */
				/*****************/
				// recup d tableau des types de conges
				$tab_type_conges=recup_tableau_types_conges( $DEBUG);
				// recup du tableau des types d'absence
				$tab_type_absence=recup_tableau_types_absence( $DEBUG);
				// recup d tableau des types de conges exceptionnels
				$tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels( $DEBUG);

				echo '<td align="left" valign="top">';
				// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
				// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
				if( (($_SESSION['config']['user_saisie_demande']==TRUE)&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['user_saisie_demande']==FALSE)&&($user_login!=$_SESSION['userlogin'])) )
				{
					// congés
					echo '<b><i><u>'. _('divers_conges') .' :</u></i></b><br>';
					foreach($tab_type_conges as $id => $libelle)
					{
						if($id==1)
							echo '<input type="radio" name="new_type" value="'.$id.'" checked> '.$libelle.'<br>';
						else
							echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					}
				}
				// si le user a droit de saisir une mission ET si on est PAS dans une fenetre de responsable
				// OU si le resp a droit de saisir une mission ET si on est PAS dans une fenetre dd'utilisateur
				// OU si le resp a droit de saisir une mission ET si le resp est resp de lui meme
				if( (($_SESSION['config']['user_saisie_mission']==TRUE)&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['resp_saisie_mission']==TRUE)&&($user_login!=$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['resp_saisie_mission']==TRUE)&&(is_resp_of_user($_SESSION['userlogin'], $user_login,  $DEBUG)==TRUE)) )
				{
					echo '<br>';
					// absences
					echo '<b><i><u>'. _('divers_absences') .' :</u></i></b><br>';
					foreach($tab_type_absence as $id => $libelle)
					{
						echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					}
				}
				// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
				// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
				if( ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) && (
				    (($_SESSION['config']['user_saisie_demande']==TRUE)&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['user_saisie_demande']==FALSE)&&($user_login!=$_SESSION['userlogin'])) ) )
				{
					echo '<br>';
					// congés exceptionnels
					echo '<b><i><u>'. _('divers_conges_exceptionnels') .' :</u></i></b><br>';
					 foreach($tab_type_conges_exceptionnels as $id => $libelle)
					{
						 if($id==1)
							 echo '<input type="radio" name="new_type" value="'.$id.'" checked> '.$libelle.'<br>';
						 else
							 echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					 }
				}

				echo '</td>';
				echo '</tr>';
				echo '</table>';

			echo '</td>';
			echo '</tr>';
			echo '</table>';

		echo '</form>' ;
}


//affiche le formulaire d'échange d'un jour de rtt-temps partiel / jour travaillé
function saisie_echange_rtt($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $onglet,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	$mois_calendrier_saisie_debut_prec=0; $year_calendrier_saisie_debut_prec=0;
	$mois_calendrier_saisie_debut_suiv=0; $year_calendrier_saisie_debut_suiv=0;
	$mois_calendrier_saisie_fin_prec=0; $year_calendrier_saisie_fin_prec=0;
	$mois_calendrier_saisie_fin_suiv=0; $year_calendrier_saisie_fin_suiv=0;

	if($DEBUG==TRUE) { echo 'param = '.$user_login.', '.$year_calendrier_saisie_debut.', '.$mois_calendrier_saisie_debut.', '.$year_calendrier_saisie_fin.', '.$mois_calendrier_saisie_fin.' <br>' ; }

	echo '<form action="'.$PHP_SELF.'?session='.$session.'&&onglet='.$onglet.'" method="POST">' ;

			echo '<table cellpadding="0" cellspacing="5" border="0">';
			echo '<tr align="center">';

			// cellule 1 : calendrier de saisie du jour d'absence
			echo '<td>';
				echo '<table cellpadding="0" cellspacing="0" width="250">';
				echo '<tr>';
					init_var_navigation_mois_year($mois_calendrier_saisie_debut, $year_calendrier_saisie_debut,
								$mois_calendrier_saisie_debut_prec, $year_calendrier_saisie_debut_prec,
								$mois_calendrier_saisie_debut_suiv, $year_calendrier_saisie_debut_suiv,
								$mois_calendrier_saisie_fin, $year_calendrier_saisie_fin,
								$mois_calendrier_saisie_fin_prec, $year_calendrier_saisie_fin_prec,
								$mois_calendrier_saisie_fin_suiv, $year_calendrier_saisie_fin_suiv );

					// affichage des boutons de défilement
					// recul du mois saisie debut
					echo '<td align="center" class="big">';
					echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_prec.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_prec.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin.'&user_login='.$user_login.'&onglet='.$onglet.'">';
					echo ' <img src="'. TEMPLATE_PATH . 'img/simfirs.gif" width="16" height="16" border="0" alt="'. _('divers_mois_precedent') .'" title="'. _('divers_mois_precedent') .'"> ';
					echo '</a>';
					echo '</td>';

					// titre du calendrier de saisie du jour d'absence
					echo '<td align="center" class="big">'. _('saisie_echange_titre_calendrier_1') .' :</td>';

					// affichage des boutons de défilement
					// avance du mois saisie debut
					echo '<td align="center" class="big">';
					echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut_suiv.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut_suiv.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin.'&user_login='.$user_login.'&onglet='.$onglet.'">';
					echo ' <img src="'. TEMPLATE_PATH . 'img/simlast.gif" width="16" height="16" border="0" alt="'. _('divers_mois_suivant') .'" title="'. _('divers_mois_suivant') .'"> ';
					echo '</a>';
					echo '</td>';
				echo '</tr>';
				echo '</table>';
				//*** calendrier saisie date debut ***/
				affiche_calendrier_saisie_jour_absence($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut);
			echo '</td>';

			// cellule 2 : boutons radio 1/2 journée ou jour complet
			echo '<td>';
				echo '<input type="radio" name="moment_absence_ordinaire" value="a"><b><u>'. _('form_am') .'</u></b><input type="radio" name="moment_absence_souhaitee" value="a"><br><br>';
				echo '<input type="radio" name="moment_absence_ordinaire" value="p"><b><u>'. _('form_pm') .'</u></b><input type="radio" name="moment_absence_souhaitee" value="p"><br><br>';
				echo '<input type="radio" name="moment_absence_ordinaire" value="j" checked><b><u>'. _('form_day') .'</u></b><input type="radio" name="moment_absence_souhaitee" value="j" checked><br>';
			echo '</td>';

			// cellule 3 : calendrier de saisie du jour d'absence
			echo '<td>';
				echo '<table cellpadding="0" cellspacing="0" width="250">';
				echo '<tr>';
					$mois_calendrier_saisie_fin_prec = $mois_calendrier_saisie_fin==1 ? 12 : $mois_calendrier_saisie_fin-1 ;
					$mois_calendrier_saisie_fin_suiv = $mois_calendrier_saisie_fin==12 ? 1 : $mois_calendrier_saisie_fin+1 ;

					// affichage des boutons de défilement
					// recul du mois saisie fin
					echo '<td align="center" class="big">';
					echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_prec.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_prec.'&user_login='.$user_login.'&onglet='.$onglet.'">';
					echo ' <img src="'. TEMPLATE_PATH . 'img/simfirs.gif" width="16" height="16" border="0" alt="'. _('divers_mois_precedent') .'" title="'. _('divers_mois_precedent') .'"> ';
					echo '</a>';
					echo '</td>';

					// titre du ecalendrier de saisie du jour d'absence
					echo '<td align="center" class="big">'. _('saisie_echange_titre_calendrier_2') .' :</td>';

					// affichage des boutons de défilement
					// avance du mois saisie fin
					echo '<td align="center" class="big">';
					echo '<a href="'.$PHP_SELF.'?session='.$session.'&year_calendrier_saisie_debut='.$year_calendrier_saisie_debut.'&mois_calendrier_saisie_debut='.$mois_calendrier_saisie_debut.'&year_calendrier_saisie_fin='.$year_calendrier_saisie_fin_suiv.'&mois_calendrier_saisie_fin='.$mois_calendrier_saisie_fin_suiv.'&user_login='.$user_login.'&onglet='.$onglet.'">';
					echo ' <img src="'. TEMPLATE_PATH . 'img/simlast.gif" width="16" height="16" border="0" alt="'. _('divers_mois_suivant') .'" title="'. _('divers_mois_suivant') .'"> ';
					echo '</a>';
					echo '</td>';
				echo '</tr>';
				echo '</table>';

				//*** calendrier saisie date fin ***/
				affiche_calendrier_saisie_jour_presence($user_login, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);
			echo '</td>';

			echo '</tr>';
			echo '<tr align="center">';

			// cellule 1 : champs texte et boutons (valider/cancel)
			echo '<td colspan=3>';

				/***  formulaire ***/
					echo '<table cellpadding="2" cellspacing="3" border="0" >';
					echo '<tr align="center">';
						echo '<td><b>'. _('divers_comment_maj_1') .' : </b></td>';
						$text_commentaire ='<input type="text" name="new_comment" size="25" maxlength="30" value="">' ;
						echo '<td>'.$text_commentaire.'</td>';
					echo '</tr>';
					echo '<tr align="center">';
						echo '<td colspan=2><img src="". TEMPLATE_PATH . "img/shim.gif" width="15" height="10" border="0" vspace="0" hspace="0"></td>';
					echo '</tr>';
					echo '<tr align="center">';
						echo '<td colspan=2>';
							echo '<input type="hidden" name="user_login" value="'.schars($user_login).'">';
							echo '<input type="hidden" name="new_echange_rtt" value=1>';
							echo '<input type="submit" value="'. _('form_submit') .'">   <input type="reset" value="'. _('form_cancel') .'">';
						echo '</td>';
					echo '</tr>';
					echo '</table>';


			echo '</td>';
			echo '</tr>';
			echo '</table>';

		echo '</form>' ;
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
function affiche_decimal($str, $DEBUG=FALSE)
{
	$champs=explode('.', $str);
	$int=$champs[0];
	$decimal='00';
	if (count($champs)>1)
		$decimal = $champs[1];
	//$decimal=$champs[1];

	if($decimal=='00')
		return $int ;
	elseif (preg_match('/[0-9][1-9]$/' , $decimal ))
		return $str;
	elseif (preg_match('/([0-9])0$/' , $decimal, $regs ))
		return $int.'.'.$regs[1] ;
	else {
		echo 'ERREUR: affiche_decimal('.$str.') : '.$str.' n\'a pas le format attendu !!!!<br>';
		exit;
	}
}


// verif validité des valeurs saisies lors d'une demande de conges par un user ou d'une saisie de conges par le responsable
//  (attention : le $new_nb_jours est passé par référence car on le modifie si besoin)
function verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, &$new_nb_jours, $new_comment, $DEBUG=FALSE)
{
	$verif=TRUE ;

	// leur champs doivent etre renseignés dans le formulaire
	if( ($new_debut=='') || ($new_fin=='') || ($new_nb_jours=='') ) {
		echo '<br>'. _('verif_saisie_erreur_valeur_manque') .'<br>';
		$verif=FALSE ;
	}

	if ( !preg_match('/([0-9]+)([\.\,]*[0-9]{1,2})*$/', $new_nb_jours) ) 
	{
		echo '<br>'. _('verif_saisie_erreur_nb_jours_bad') .'<br>';
		$verif=FALSE ;
	}
	else 
	{
		if ( preg_match('/([0-9]+)\,([0-9]{1,2})$/', $new_nb_jours, $reg) ) 
			$new_nb_jours=$reg[1].'.'.$reg[2];    // on remplace la virgule par un point pour les décimaux
	}

	// si la date de fin est antéreieure à la date debut
	if(strnatcmp($new_debut, $new_fin)>0) {
		echo '<br>'. _('verif_saisie_erreur_fin_avant_debut') .'<br>';
		$verif=FALSE ;
	}

	// si la date debut et fin = même jour mais début=après midi et fin=matin !!
	if((strnatcmp($new_debut, $new_fin)==0)&&($new_demi_jour_deb=="pm")&&($new_demi_jour_fin=="am") ) {
		echo '<br>'. _('verif_saisie_erreur_debut_apres_fin') .'<br>';
		$verif=FALSE ;
	}

	return $verif;
}


// renvoit la class de cellule du jour indiquée par le timestamp
// (une classe pour les jours de semaine et une pour les jours de week end)
function get_td_class_of_the_day_in_the_week($timestamp_du_jour)
{
	$j_name=date('D', $timestamp_du_jour);
	$j_date=date('Y-m-d', $timestamp_du_jour);

	if( (($j_name=='Sat')&&($_SESSION['config']['samedi_travail']==FALSE))
	|| (($j_name=='Sun')&&($_SESSION['config']['dimanche_travail']==FALSE))
	|| (est_chome($timestamp_du_jour)==TRUE) || (est_ferme($timestamp_du_jour)==TRUE) )
		return 'weekend';
	else
		return 'semaine';
}


//
// affichage bouton de déconnexion
function   bouton_deconnexion($DEBUG=FALSE)
{
   $session=session_id();

	echo '<a href="../deconnexion.php?session='.$session.'" target="_top">' .
			'<img src="'. TEMPLATE_PATH . 'img/exit.png" width="22" height="22" border="0" title="'. _('button_deconnect') .'" alt="'. _('button_deconnect') .'">' .
			 _('button_deconnect') .'</a>';

}

// affichage bouton actualiser la page
function bouton_actualiser($onglet, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo '<a href="'.$PHP_SELF.'?session='.$session.'&onglet='.$onglet.'">';
	echo '<img src="'. TEMPLATE_PATH . 'img/reload_page.png" width="22" height="22" border="0" title="'. _('button_refresh') .'" alt="'. _('button_refresh') .'">';
	echo  _('button_refresh') .'</a>';
}



// recup des infos ARTT ou Temps Partiel :
// attention : les param $val_matin et $val_aprem sont passées par référence (avec &) car on change leur valeur
function recup_infos_artt_du_jour($sql_login, $j_timestamp, &$val_matin, &$val_aprem,  $DEBUG=FALSE)
{

	$num_semaine=date('W', $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres

	// on ne cherche pas d'artt les samedis ou dimanches quand il ne sont pas travaillés (cf config de php_conges)
	if( (($jour_name_fr_2c=='sa')&&($_SESSION['config']['samedi_travail']==FALSE)) || (($jour_name_fr_2c=='di')&&($_SESSION['config']['dimanche_travail']==FALSE)) )
	{
		// on ne cherche pas d'artt les samedis ou dimanches quand ils ne sont pas travaillés
	}
	else
	{
		// verif si le jour fait l'objet d'un echange ....
		$date_j=date('Y-m-d', $j_timestamp);
		$sql_echange_rtt='SELECT e_absence FROM conges_echange_rtt WHERE e_login=\''.SQL::quote($sql_login).'\' AND e_date_jour=\''.SQL::quote($date_j).'\' ';
		$res_echange_rtt = SQL::query($sql_echange_rtt);

		$num_echange_rtt = $res_echange_rtt->num_rows;
		// si le jour est l'objet d'un echange, on tient compte de l'échange
		if($num_echange_rtt!=0)
		{
			$result_echange_rtt = $res_echange_rtt->fetch_array();
			if ($result_echange_rtt['e_absence']=='J') // jour entier
			{
				$val_matin='Y';
				$val_aprem='Y';
			}
			elseif ($result_echange_rtt['e_absence']=='M') // matin
			{
				$val_matin='Y';
				$val_aprem='N';
			}
			elseif ($result_echange_rtt['e_absence']=='A') // apres-midi
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
			$par_sem = $parite_semaine==0 ? 'p' : 'imp';

			//on calcule la key du tableau $result_artt qui correspond au jour j que l'on est en train d'afficher
			$key_artt_matin ='sem_'.$par_sem.'_'.$jour_name_fr_2c.'_am' ;
			$key_artt_aprem ='sem_'.$par_sem.'_'.$jour_name_fr_2c.'_pm' ;

			// recup des ARTT et temps-partiels du user
			$sql_artt='SELECT '.SQL::quote($key_artt_matin).', '.SQL::quote($key_artt_aprem).' FROM conges_artt
				WHERE a_login=\''.SQL::quote($sql_login).'\' AND a_date_debut_grille<=\''.SQL::quote($date_j).'\' AND a_date_fin_grille>=\''.SQL::quote($date_j).' \'';
			$res_artt = SQL::query($sql_artt);
			$result_artt = $res_artt->fetch_array();
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
function recup_infos_artt_du_jour_from_tab($sql_login, $j_timestamp, &$val_matin, &$val_aprem, $tab_rtt_echange, $tab_rtt_planifiees, $DEBUG=FALSE)
{

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

	$num_semaine=date('W', $j_timestamp);
	$jour_name_fr_2c=get_j_name_fr_2c($j_timestamp); // nom du jour de la semaine en francais sur 2 caracteres

	// on ne cherche pas d'artt les samedis ou dimanches quand il ne sont pas travaillés (cf config de php_conges)
	if( (($jour_name_fr_2c=="sa")&&($_SESSION['config']['samedi_travail']==FALSE)) || (($jour_name_fr_2c=="di")&&($_SESSION['config']['dimanche_travail']==FALSE)) )
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
		if(array_key_exists($sql_login, $tab_day))   // si la periode correspond au user que l'on est en train de traiter
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
			if(count($tab_grille_user))
			{
				foreach ($tab_grille_user as $key => $value) 
				{
					if( ($date_j>=$value["date_debut_grille"]) && ($date_j<=$value["date_fin_grille"]) ) // date_jour comprise entre date_deb_grille et date_fin grille
					{
						$val_matin=$value[$key_artt_matin];
						$val_aprem=$value[$key_artt_aprem];
						//echo "$sql_login : ".$value["login"]."<br>\n";
					}
					else
					{
						// ne fait rien
					}
				}
			}
		}
	}
}




// verif validité d'un nombre saisi (decimal ou non)
//  (attention : le $nombre est passé par référence car on le modifie si besoin)
function verif_saisie_decimal(&$nombre, $DEBUG=FALSE)
{
	$verif=TRUE ;

	if ( !preg_match('/^-?([0-9]+)([\.\,]?[0-9]?[0-9]?)$/', $nombre) ) 
	{
		echo "<br>". _('verif_saisie_erreur_nb_bad') ." ($nombre)<br>\n";
		$verif=FALSE ;
	}
	else
	{
		if( preg_match('/^([0-9]+)\,([0-9]{1,2})$/', $nombre, $reg) ) 
			$nombre=$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les décimaux
		elseif( preg_match('/^-([0-9]+)\,([0-9]{1,2})$/', $nombre, $reg) )
			$nombre="-".$reg[1].".".$reg[2];    // on remplace la virgule par un point pour les décimaux
	}

	return $verif;
}



// donne la date en francais (dans la langue voulue)(meme formats que la fonction PHP date() cf manuel php)
function date_fr($code, $timestmp)
{
	$les_mois_longs  = array("pas_de_zero",  _('janvier') ,  _('fevrier') ,  _('mars') ,  _('avril') ,
								 _('mai') ,  _('juin') ,  _('juillet') ,  _('aout') ,
								 _('septembre') ,  _('octobre') ,  _('novembre') ,  _('decembre') );

	$les_jours_longs  = array( _('dimanche') ,  _('lundi') ,  _('mardi') ,  _('mercredi') ,
								 _('jeudi') ,  _('vendredi') ,  _('samedi') );
	$les_jours_courts = array( _('dimanche_short') ,  _('lundi_short') ,  _('mardi_short') ,
								 _('mercredi_short') ,  _('jeudi_short') ,  _('vendredi_short') ,  _('samedi_short') );

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



// envoi d'un message d'avertissement
// parametre 1=login de l'expéditeur
// parametre 2=login du destnataire (ou ":responsable:" si envoi au(x) responsable(s))
// parametre 3= numero de l'absence concernée
// parametre 4=objet du message (cf table conges_mail pour les diff valeurs possibles)
function alerte_mail($login_expediteur, $destinataire, $num_periode, $objet,  $DEBUG=FALSE)
{
//$DEBUG=TRUE;

	$phpmailer_filename = LIBRARY_PATH .'phpmailer/class.phpmailer.php';
	// verif si la librairie phpmailer est présente
	if(!is_readable($phpmailer_filename))
	{
		echo  _('phpmailer_not_valid') ."<br> !";
	}
	else
	{
		require_once($phpmailer_filename);	// ajout de la classe phpmailer


		/*********************************************/
		// recup des infos concernant l'expéditeur ....
		$mail_array=find_email_adress_for_user($login_expediteur, $DEBUG);
		$mail_sender_name = $mail_array[0];
		$mail_sender_addr = $mail_array[1];

		/*********************************************/
		// recherche des infos concernant le destinataire ...
		// recherche du login du (des) destinataire(s) dans la base
		$dest_mail="";
		if($destinataire==":responsable:")  // c'est une message au responsable
		{
//			$tab_resp=array();
//			get_tab_resp_du_user($login_expediteur, $tab_resp,  $DEBUG);
			$tab_resp=get_tab_resp_du_user($login_expediteur,  $DEBUG);
			if( $DEBUG==TRUE ) { echo "tab_resp :<br>"; print_r($tab_resp); echo "<br>\n"; }

			foreach($tab_resp as $item_login => $item_presence)
			{
				// recherche de l'adresse mail du (des) responsable(s) :
				$mail_array_dest=find_email_adress_for_user($item_login, $DEBUG);
				$mail_dest_name = $mail_array_dest[0];
				$mail_dest_addr = $mail_array_dest[1];
				if( $DEBUG==TRUE )
					echo "TO = $mail_dest_addr<br>\n";

				if($mail_dest_addr=="")
					echo "<b>ERROR : $mail_dest_name : no mail address !</b><br>\n";
				else
				{
					// on change l'objet si c'est un "new_demande" à un resp absent et qu'on gere les absence de resp !
					if( ($_SESSION['config']['gestion_cas_absence_responsable']==TRUE) && ($item_presence=="absent") && ($objet="new_demande") )
						$new_objet="new_demande_resp_absent";
					else
						$new_objet=$objet;
						
					constuct_and_send_mail($new_objet, $mail_sender_name, $mail_sender_addr, $mail_dest_name, $mail_dest_addr, $num_periode,  $DEBUG);
				}
			}
			
		}
		else   // c'est un message du responsale à un user
		{
			$dest_login = $destinataire ;
			$mail_array_dest=find_email_adress_for_user($dest_login, $DEBUG);
			$mail_dest_name = $mail_array_dest[0];
			$mail_dest_addr = $mail_array_dest[1];
			if( $DEBUG==TRUE )
				echo "TO = $mail_dest_addr<br>\n";

			if($mail_dest_addr=="")
				echo "<b>ERROR : $mail_dest_name : no mail address !</b><br>\n";
			else
				constuct_and_send_mail($objet, $mail_sender_name, $mail_sender_addr, $mail_dest_name, $mail_dest_addr, $num_periode,  $DEBUG);

			/****************************/
			if($objet=="valid_conges")  // c'est un mail de première validation de demande : il faut faire une copie au(x) grand(s) responsable(s)
			{
				// on recup la liste des grands resp du user
				$tab_grd_resp=array();
				get_tab_grd_resp_du_user($dest_login, $tab_grd_resp,  $DEBUG);
				if( $DEBUG==TRUE ) { echo "tab_grd_resp :<br>"; print_r($tab_grd_resp); echo "<br>\n"; }

				if(count($tab_grd_resp)!=0)  // si tableau n'est pas vide
				{
					foreach($tab_grd_resp as $item_login)
					{
						// recherche de l'adresse mail du (des) responsable(s) :
						$mail_array_dest=find_email_adress_for_user($item_login, $DEBUG);
						$mail_dest_name = $mail_array_dest[0];
						$mail_dest_addr = $mail_array_dest[1];
						if( $DEBUG==TRUE )
							echo "TO = $mail_dest_addr<br>\n";

						if($mail_dest_addr=="")
							echo "<b>ERROR : $mail_dest_name : no mail address !</b><br>\n";
						else
							constuct_and_send_mail($objet, $mail_sender_name, $mail_sender_addr, $mail_dest_name, $mail_dest_addr, $num_periode,  $DEBUG);
					}
				}
			}
		}



	}

}


// construit et envoie le mail 
function constuct_and_send_mail($objet, $mail_sender_name, $mail_sender_addr, $mail_dest_name, $mail_dest_addr, $num_periode,  $DEBUG=FALSE)
{
	if( $DEBUG==TRUE ) {echo "constuct_and_send_mail($objet, $mail_sender_name, $mail_sender_addr, $mail_dest_name, $mail_dest_addr, $num_periode)<br>\n";}
	
		/*********************************************/
		// init du mail
		$mail = new PHPMailer();
		if($_SESSION['config']['serveur_smtp']=="")
		{
			if(file_exists("/usr/sbin/sendmail"))
				$mail->IsSendmail();   // send message using the $Sendmail program
			elseif(file_exists("/var/qmail/bin/sendmail"))
				$mail->IsQmail();      // send message using the qmail MTA
			else
				$mail->IsMail();       // send message using PHP mail() function
		}
		else
		{
			$mail->IsSMTP();
			$mail->Host = $_SESSION['config']['serveur_smtp'];
		}

		// initialisation du langage utilisé par php_mailer
		$mail->SetLanguage("fr", LIBRARY_PATH ."phpmailer/language/");

		if( $DEBUG==TRUE )
			echo "FROM = $mail_sender_name : $mail_sender_addr<br>\n";

		$mail->FromName = $mail_sender_name;
		$mail->From = $mail_sender_addr;

		$mail->AddAddress($mail_dest_addr);

		/*********************************************/
		// recup des infos de l'absence
		$select_abs="SELECT conges_periode.p_date_deb,
						conges_periode.p_demi_jour_deb,
						conges_periode.p_date_fin,
						conges_periode.p_demi_jour_fin,
						conges_periode.p_nb_jours,
						conges_periode.p_commentaire,
						conges_type_absence.ta_libelle
					FROM   conges_periode, conges_type_absence
					WHERE  conges_periode.p_num=$num_periode
							AND    conges_periode.p_type = conges_type_absence.ta_id";
		$res_abs = SQL::query($select_abs);
		$rec_abs = $res_abs->fetch_array();

		$tab_date_deb= explode("-", $rec_abs["p_date_deb"]);
		// affiche : "23 / 01 / 2008 (am)"
		$sql_date_deb = $tab_date_deb[2]." / ".$tab_date_deb[1]." / ".$tab_date_deb[0]." (".$rec_abs["p_demi_jour_deb"].")" ;
		$tab_date_fin= explode("-", $rec_abs["p_date_fin"]);
		// affiche : "23 / 01 / 2008 (am)"
		$sql_date_fin = $tab_date_fin[2]." / ".$tab_date_fin[1]." / ".$tab_date_fin[0]." (".$rec_abs["p_demi_jour_fin"].")" ;
		$sql_nb_jours = $rec_abs["p_nb_jours"];
		$sql_commentaire = $rec_abs["p_commentaire"];
		$sql_type_absence = $rec_abs["ta_libelle"];


		/*********************************************/
		// construction des sujets et corps des messages
		if($objet=="valid_conges")
		{
			$key1="mail_prem_valid_conges_sujet" ;
			$key2="mail_prem_valid_conges_contenu" ;
		}
		elseif($objet=="accept_conges")
		{
			$key1="mail_valid_conges_sujet" ;
			$key2="mail_valid_conges_contenu" ;
		}
		elseif($objet=="new_demande_resp_absent")
		{
			$key1="mail_new_demande_resp_absent_sujet" ;
			$key2="mail_new_demande_resp_absent_contenu" ;
		}
		else  // $objet== "refus_conges" ou "new_demande" ou "annul_conges"
		{
			$key1="mail_".$objet."_sujet" ;
			$key2="mail_".$objet."_contenu" ;
		}
		$sujet = $_SESSION['config'][$key1];
		$contenu = $_SESSION['config'][$key2];
		$contenu = str_replace("__URL_ACCUEIL_CONGES__", $_SESSION['config']['URL_ACCUEIL_CONGES'], $contenu);
		$contenu = str_replace("__SENDER_NAME__", $mail_sender_name, $contenu);
		$contenu = str_replace("__DESTINATION_NAME__", $mail_dest_name, $contenu);
		$contenu = str_replace("__NB_OF_DAY__", $sql_nb_jours, $contenu);
		$contenu = str_replace("__DATE_DEBUT__", $sql_date_deb, $contenu);
		$contenu = str_replace("__DATE_FIN__", $sql_date_fin, $contenu);
		$contenu = str_replace("__RETOUR_LIGNE__", "\r\n", $contenu);
		$contenu = str_replace("__COMMENT__", $sql_commentaire, $contenu);
		$contenu = str_replace("__TYPE_ABSENCE__", $sql_type_absence, $contenu);

		// construction du corps du mail
		$mail->Subject  =  utf8_decode($sujet );
		$mail->Body     =  utf8_decode($contenu );


		/*********************************************/
		// ENVOI du mail
		if( $DEBUG==TRUE )
		{
			echo "SUBJECT = ".$sujet."<br>\n";
			echo "CONTENU = ".$mail->FromName." ".$contenu."<br>\n";
		}
		else
		{
			if(count($mail->to)==0)
			{
				echo "<b>ERROR : No recipient address for the message!</b><br>\n";
				echo "<b>Message was not sent </b><br>";
			}
			else
			{
				if(!$mail->Send())
				{
					echo "<b>Message was not sent </b><br>";
					echo "<b>Mailer Error: " . $mail->ErrorInfo."</b><br>";
				}
			}
		}
}



// recuperation du mail d'un user
// renvoit un tableau a 2 valeurs : prenom+nom et email
function find_email_adress_for_user($login, $DEBUG=FALSE)
{

	$found_mail=array();

	if($_SESSION['config']['where_to_find_user_email']=="ldap") // recherche du mail du user dans un annuaire LDAP
	{
		// cnx à l'annuaire ldap :
		$ds = ldap_connect($_SESSION['config']['ldap_server']);
		if($_SESSION['config']['ldap_protocol_version'] != 0)
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $_SESSION['config']['ldap_protocol_version']) ;
		if ($_SESSION['config']['ldap_user'] == "")
		     $bound = ldap_bind($ds);
		else $bound = ldap_bind($ds, $_SESSION['config']['ldap_user'], $_SESSION['config']['ldap_pass']);

		// recherche des entrées correspondantes au "login" passé en paramètre :
		$filter = "(".$_SESSION['config']['ldap_login']."=".$login.")";

		$sr   = ldap_search($ds, $_SESSION['config']['searchdn'], $filter);
		$data = ldap_get_entries($ds,$sr);

		foreach ($data as $info)
		{
			$found_mail=array();
			// On récupère le nom et le mail de la personne.
			// Utilisation de la fonction utf8_decode pour corriger les caractères accentués
			// (qnd les noms ou prénoms ont des accents, "ç", ...

			// Les champs LDAP utilisés, bien que censés être uniformes, sont ceux d'un AD 2003.
			$ldap_prenom = $_SESSION['config']['ldap_prenom'];
			$ldap_nom    = $_SESSION['config']['ldap_nom'];
			$ldap_mail   = $_SESSION['config']['ldap_mail'];
			$nom = utf8_decode($info[$ldap_prenom][0])." ".strtoupper(utf8_decode($info[$ldap_nom][0])) ;
			$addr = $info[$ldap_mail][0] ;
			array_push($found_mail, $nom) ;
			array_push($found_mail, $addr) ;
		}
	}
	elseif($_SESSION['config']['where_to_find_user_email']=="dbconges") // recherche du mail du user dans la base db_conges
	{
		$req = 'SELECT u_nom, u_prenom, u_email FROM conges_users WHERE u_login=\''.SQL::quote($login).'\' ';
		$res = SQL::query($req);
		$rec = $res->fetch_array();

		$sql_nom = $rec["u_nom"];
		$sql_prenom = $rec["u_prenom"];
		$sql_email = $rec["u_email"];

		array_push($found_mail, $sql_prenom." ".strtoupper($sql_nom)) ;
		array_push($found_mail, $sql_email) ;

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
function recup_tableau_rtt_echange($mois, $first_jour, $year,  $DEBUG=FALSE)
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

		$sql_echange_rtt='SELECT e_login, e_absence FROM conges_echange_rtt WHERE e_date_jour=\''.SQL::quote($date_j).'\' ';
		$res_echange_rtt = SQL::query($sql_echange_rtt);

		$num_echange_rtt = $res_echange_rtt->num_rows;
		// si le jour est l'objet d'un echange, on tient compte de l'échange
		if($num_echange_rtt!=0)
		{
			while($result_echange_rtt = $res_echange_rtt->fetch_array())
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
				$tab_jour_rtt_echange[$login]=$tab_echange;
			}
		}
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

			$sql_echange_rtt='SELECT e_login, e_absence FROM conges_echange_rtt WHERE e_date_jour=\''.SQL::quote($date_j).'\' ';
			$res_echange_rtt = SQL::query($sql_echange_rtt);
			$num_echange_rtt = $res_echange_rtt->num_rows;
			// si le jour est l'objet d'un echange, on tient compte de l'échange
			if($num_echange_rtt!=0)
			{
				while($result_echange_rtt = $res_echange_rtt->fetch_array())
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
					$tab_jour_rtt_echange[$login]=$tab_echange;
				}
			}
			$tab_rtt_echange[$date_j]=$tab_jour_rtt_echange;
		}
	}
	return $tab_rtt_echange;
}



/**************************************************/
/* recup dans un tableau des rtt planifiées  pour tous les users */
/**************************************************/
function recup_tableau_rtt_planifiees($mois, $first_jour, $year,  $DEBUG=FALSE)
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
	$res_artt_login = SQL::query($req_artt_login);

	//$num_artt_login = $res_artt_login->num_rows;
	while($result_artt_login = $res_artt_login->fetch_array()) // pour chaque login trouvé
	{
		$sql_artt_login=$result_artt_login["a_login"];
		$tab_user_grille=array();

		$req_artt = 'SELECT * FROM conges_artt WHERE a_login=\''.SQL::quote($sql_artt_login).'\' ';
		$res_artt = SQL::query($req_artt);

		$num_artt = $res_artt->num_rows;
		while($result_artt = $res_artt->fetch_array())
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

			$tab_user_grille[$key_grille]=$tab_user_rtt;
		}
		$tab_rtt_planifiees[$sql_artt_login]=$tab_user_grille;
	}
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
function eng_date_to_fr($une_date, $DEBUG=FALSE)
{
 return substr($une_date, 8)."-".substr($une_date, 5, 2)."-".substr($une_date, 0, 4);

}


// affichage de la cellule correspondant au jour dans les calendrier de saisie (demande de conges, etc ...)
function affiche_cellule_jour_cal_saisie($login, $j_timestamp, $td_second_class, $result,  $DEBUG=FALSE)
{
	$session=session_id();

	//echo "$j_timestamp, $year, $mois, $j, $td_second_class<br>\n";
	$date_j=date("Y-m-d", $j_timestamp);
	$j=date("d", $j_timestamp);

	$class_am="travail_am";
	$class_pm="travail_pm";

	$val_matin="";
	$val_aprem="";
	// recup des infos ARTT ou Temps Partiel :
	// la fonction suivante change les valeurs de $val_matin $val_aprem ....
	recup_infos_artt_du_jour($login, $j_timestamp, $val_matin, $val_aprem,  $DEBUG);

	//## AFICHAGE ##
	if($val_matin=="Y")
	{
		$class_am="rtt_am";
	}
	if($val_aprem=="Y")
	{
		$class_pm = "rtt_pm";
	}


	$jour_today=date("j");
	$mois_today=date("m");
	$year_today=date("Y");
	$timestamp_today = mktime (0,0,0,$mois_today,$jour_today,$year_today);
	// si la saisie de conges pour une periode passée est interdite : pas de case à cocher dans les dates avant aujourd'hui
	if( ($_SESSION['config']['interdit_saisie_periode_date_passee']==TRUE) && ($j_timestamp<$timestamp_today) )
		echo "<td  class=\"cal-saisie $td_second_class $class_am $class_pm\">$j</td>";
	else
	{
		// Si le client est sous IE, on affiche pas les jours de rtt (car IE ne gère pas les appels standart de classe de feuille e style)
		if( (isset($_SERVER["HTTP_USER_AGENT"])) && (stristr($_SERVER["HTTP_USER_AGENT"], "MSIE")!=FALSE) )
		{
			echo "<td  class=\"cal-saisie\">$j<input type=\"radio\" name=\"$result\" ";
			if($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE)
			{
				// attention : IE6 : bug avec les "OnChange" sur les boutons radio!!! (on remplace par OnClick)
				if( (isset($_SERVER["HTTP_USER_AGENT"])) && (stristr($_SERVER["HTTP_USER_AGENT"], "MSIE")!=FALSE) )
					echo "onClick=\"compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;\"" ;
				else
					echo "onChange=\"compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;\"" ;
			}
			echo " value=\"$date_j\"></td>";
		}
		else
		{
			echo "<td  class=\"cal-saisie $td_second_class $class_am $class_pm\">$j<input type=\"radio\" name=\"$result\" ";
			if($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE)
			{
				echo "onChange=\"compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;\"" ;
			}
			echo " value=\"$date_j\"></td>";
		}
	}

}



// recup du nom d'un groupe grace à son group_id
function get_group_name_from_id($groupe_id,  $DEBUG=FALSE)
{

		$req_name='SELECT g_groupename FROM conges_groupe WHERE g_gid='.SQL::quote($groupe_id);
		$ReqLog_name = SQL::query($req_name);

		$resultat_name = $ReqLog_name->fetch_array();
		return $resultat_name["g_groupename"];

}


// recup de la liste de TOUS les users dont $resp_login est responsable
// (prend en compte le resp direct, les groupes, le reps virtuel, etc ...)
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_all_users_du_resp($resp_login,  $DEBUG=FALSE)
{


	$list_users="";

// 	Modification ML : Remonte toutes les demandes de conges pour un responsable SAUF celles du responsable lui même et des autres responsables du groupe
// 	$sql="SELECT DISTINCT(u_login) FROM conges_users WHERE u_login!='conges' AND u_login!='admin'";
	$sql1="SELECT DISTINCT(u_login) FROM conges_users WHERE u_login!='conges' AND u_login!='admin' AND u_login!='$resp_login'";

	// si resp virtuel, on renvoit tout le monde, sinon, seulement ceux dont on est responsables
	if($_SESSION['config']['responsable_virtuel']==FALSE)
	{
		$sql1 = $sql1." AND  ( u_resp_login='$resp_login' " ;
		if($_SESSION['config']['gestion_groupes'] == TRUE)
		{
			$list_users_group=get_list_users_des_groupes_du_resp_sauf_resp($resp_login, $DEBUG);
			if($list_users_group!="")
				$sql1=$sql1." OR u_login IN ($list_users_group) ";
		}

		$sql1=$sql1." ) " ;
	}
	$sql1 = $sql1." ORDER BY u_nom " ;

		$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$current_login=$resultat1["u_login"];
		if($list_users=="")
			$list_users="'$current_login'";
		else
			$list_users=$list_users.", '$current_login'";
	}
	
	/************************************/
	// gestion des absence des responsables :
	// on recup la liste des users des resp absents, dont $resp_login est responsable
	if($_SESSION['config']['gestion_cas_absence_responsable']==TRUE)
	{
		// recup liste des resp absents, dont $resp_login est responsable
		$sql_2='SELECT DISTINCT(u_login) FROM conges_users WHERE u_is_resp=\'Y\' AND u_login!=\''.SQL::quote($resp_login).'\' AND u_login!=\'conges\' AND u_login!=\'admin\'';
		// si resp virtuel, on renvoit tout le monde, sinon, seulement ceux dont on est responsables
		if($_SESSION['config']['responsable_virtuel']==FALSE)
		{
			$sql_2 = $sql_2." AND  ( u_resp_login='$resp_login' " ;
			if($_SESSION['config']['gestion_groupes'] == TRUE)
			{
				$list_users_group=get_list_users_des_groupes_du_resp_sauf_resp($resp_login, $DEBUG);
				if($list_users_group!="")
					$sql_2=$sql_2." OR u_login IN ($list_users_group) ";
			}
			$sql_2=$sql_2." ) " ;
		}
		$sql_2 = $sql_2." ORDER BY u_nom " ;
	
		$ReqLog_2 = SQL::query($sql_2);
	
		// on va verifier si les resp récupérés sont absents (si oui, c'est $resp_login qui traite leurs users
		while ($resultat_2 = $ReqLog_2->fetch_array())
		{
			$current_resp=$resultat_2["u_login"];
			// verif dans la base si le current_resp est absent :
			$req = 'SELECT p_num 
                                     FROM conges_periode 
                                     WHERE p_login = \''.SQL::quote($current_resp).'\'
                                     AND p_etat = \'ok\'
                                     AND TO_DAYS(conges_periode.p_date_deb) <= TO_DAYS(NOW()) 
                                     AND TO_DAYS(conges_periode.p_date_fin) >= TO_DAYS(NOW())';
			$ReqLog_3 = SQL::query($req);
			
			// si le current resp est absent : on recup la liste de ses users pour les traiter .....
			if ($ReqLog_3->num_rows!=0)
			{
				if($list_users=="")
					$list_users=get_list_all_users_du_resp($current_resp,  $DEBUG);
				else
					$list_users=$list_users.", ".get_list_all_users_du_resp($current_resp,  $DEBUG);
			}
		}
		
	}
	// FIN gestion des absence des responsables :
	/************************************/

	if($DEBUG==TRUE) { echo "list_users = $list_users<br>\n" ;}

	return $list_users;
}


// recup de la liste des users d'un groupe donné
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_users_du_groupe($group_id,  $DEBUG=FALSE)
{


	$list_users=array();

	$sql1='SELECT DISTINCT(gu_login) FROM conges_groupe_users WHERE gu_gid = '.intval($group_id).' ORDER BY gu_login ';
	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
		$list_users[] = '\''.SQL::quote($resultat1["gu_login"]).'\'';
	
	$list_users = implode(' , ', $list_users);

	if($DEBUG==TRUE) { echo "list_users = $list_users<br>\n" ;}

	return $list_users;

}

// recup le nombre de users d'un groupe donné
function get_nb_users_du_groupe($group_id,  $DEBUG=FALSE)
{

   $sql1='SELECT DISTINCT(gu_login) FROM conges_groupe_users WHERE gu_gid = '.SQL::quote($group_id).' ORDER BY gu_login ';
   $ReqLog1 = SQL::query($sql1);

   $nb_users = $ReqLog1->num_rows;

   return $nb_users;

}


// recup de la liste des groupes dont $resp_login est responsable
// renvoit une liste de group_id séparés par des virgules
function get_list_groupes_du_resp($resp_login,  $DEBUG=FALSE)
{

	$list_group="";

	$sql1='SELECT gr_gid FROM conges_groupe_resp WHERE gr_login=\''.SQL::quote($resp_login).'\' ORDER BY gr_gid';
	$ReqLog1 = SQL::query($sql1);

	if($ReqLog1->num_rows !=0)
	{
		while ($resultat1 = $ReqLog1->fetch_array())
		{
			$current_group=$resultat1["gr_gid"];
			if($list_group=="")
				$list_group="$current_group";
			else
				$list_group=$list_group.", $current_group";
		}
	}
	if($DEBUG==TRUE) { echo "list_group = $list_group<br>\n" ;}

	return $list_group;
}

// recup de la liste des groupes dont $resp_login est grandresponsable
// renvoit une liste de group_id séparés par des virgules
function get_list_groupes_du_grand_resp($resp_login,  $DEBUG=FALSE)
{
	$list_group="";

	$sql1='SELECT ggr_gid FROM conges_groupe_grd_resp WHERE ggr_login=\''.SQL::quote($resp_login).'\' ORDER BY ggr_gid';
	$ReqLog1 = SQL::query($sql1);

	if($ReqLog1->num_rows!=0)
	{
		while ($resultat1 = $ReqLog1->fetch_array())
		{
			$current_group=$resultat1["ggr_gid"];
			if($list_group=="")
				$list_group="$current_group";
			else
				$list_group=$list_group.", $current_group";
		}
	}
	if($DEBUG==TRUE) { echo "list_group = $list_group<br>\n" ;}

	return $list_group;
}

// recup de la liste des groupes à double validation
// renvoit une liste de gid séparés par des virgules
function get_list_groupes_double_valid( $DEBUG=FALSE)
{
	$list_groupes_double_valid="";

	$sql1="SELECT g_gid FROM conges_groupe WHERE g_double_valid='Y' ORDER BY g_gid ";
	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$current_gid=$resultat1["g_gid"];
		if($list_groupes_double_valid=="")
			$list_groupes_double_valid="$current_gid";
		else
			$list_groupes_double_valid=$list_groupes_double_valid.", $current_gid";
	}

	if($DEBUG==TRUE) { echo "list_groupes_double_valid = $list_groupes_double_valid<br>\n" ;}

	return $list_groupes_double_valid;

}

// recup de la liste des groupes à double validation, dont $resp_login est responsable
// renvoit une liste de gid séparés par des virgules
function get_list_groupes_double_valid_du_resp($resp_login,  $DEBUG=FALSE)
{

	$list_groupes_double_valid_du_resp="";

	$list_groups=get_list_groupes_du_resp($resp_login,  $DEBUG);
	if($list_groups!="") // si $resp_login est responsable d'au moins un groupe
	{
		$sql1='SELECT DISTINCT(g_gid) FROM conges_groupe WHERE g_double_valid=\'Y\' AND g_gid IN ('.SQL::quote($list_groups).') ORDER BY g_gid ';
		$ReqLog1 = SQL::query($sql1);

		while ($resultat1 = $ReqLog1->fetch_array())
		{
			$current_gid=$resultat1["g_gid"];
			if($list_groupes_double_valid_du_resp=="")
				$list_groupes_double_valid_du_resp="$current_gid";
			else
				$list_groupes_double_valid_du_resp=$list_groupes_double_valid_du_resp.", $current_gid";
		}
	}
	if($DEBUG==TRUE) { echo "list_groupes_double_valid_du_resp = $list_groupes_double_valid_du_resp<br>\n" ;}

	return $list_groupes_double_valid_du_resp;

}

// recup de la liste des groupes à double validation, dont $resp_login est GRAND responsable
// renvoit une liste de gid séparés par des virgules
function get_list_groupes_double_valid_du_grand_resp($resp_login,  $DEBUG=FALSE)
{

	$list_groupes_double_valid_du_grand_resp="";

	$sql1='SELECT DISTINCT(ggr_gid) FROM conges_groupe_grd_resp WHERE ggr_login=\''.SQL::quote($resp_login).'\' ORDER BY ggr_gid ';
	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$current_gid=$resultat1["ggr_gid"];
		if($list_groupes_double_valid_du_grand_resp=="")
			$list_groupes_double_valid_du_grand_resp="$current_gid";
		else
			$list_groupes_double_valid_du_grand_resp=$list_groupes_double_valid_du_grand_resp.", $current_gid";
	}
	if($DEBUG==TRUE) { echo "list_groupes_double_valid_du_grand_resp = $list_groupes_double_valid_du_grand_resp<br>\n" ;}

	return $list_groupes_double_valid_du_grand_resp;

}

// recup de la liste des users des groupes dont $user_login est membre
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_users_des_groupes_du_user($user_login,  $DEBUG=FALSE)
{

	$list_users=array();

	$list_groups=get_list_groupes_du_user($user_login,  $DEBUG);
	if($list_groups!="") // si $user_login est membre d'au moins un groupe
	{
		$sql1='SELECT DISTINCT(gu_login) FROM conges_groupe_users WHERE gu_gid IN ('.$list_groups.') ORDER BY gu_login ';
		$ReqLog1 = SQL::query($sql1);

		while ($resultat1 = $ReqLog1->fetch_array())
			$list_users[] = '\''.SQL::quote($resultat1["gu_login"]).'\'';
	}
	$list_users = implode(' , ', $list_users);
	return $list_users;

}

// recup de la liste des groupes dont $resp_login est membre
// renvoit une liste de group_id séparés par des virgules
function get_list_groupes_du_user($user_login,  $DEBUG=FALSE)
{

	$list_group=array();

	$sql1='SELECT gu_gid FROM conges_groupe_users WHERE gu_login=\''.SQL::quote($user_login).'\' ORDER BY gu_gid';
	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
		$list_group[] = $resultat1["gu_gid"];
	$list_group = implode(' , ', $list_group);
	return $list_group;
}


// recup de la liste de TOUS les users (sauf "conges" et "admin"
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_all_users($DEBUG=FALSE)
{

	$list_users="";

	$sql1="SELECT DISTINCT(u_login) FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_login " ;

	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$current_login=$resultat1["u_login"];
		if($list_users=="")
			$list_users="'$current_login'";
		else
			$list_users=$list_users.", '$current_login'";
	}

	if($DEBUG==TRUE) { echo "list_users = $list_users<br>\n" ;}

	return $list_users;
}


// recup de la liste des groupes (tous)
// renvoit une liste de group_id séparés par des virgules
function get_list_all_groupes($DEBUG=FALSE)
{
	$list_group="";

	// on select dans conges_groupe_users pour ne récupérer QUE les groupes qui ont des users !!
	$sql1="SELECT DISTINCT(gu_gid) FROM conges_groupe_users ORDER BY gu_gid";
	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$current_group=$resultat1["gu_gid"];
		if($list_group=="")
			$list_group="$current_group";
		else
			$list_group=$list_group.", $current_group";
	}
	return $list_group;
}


// construit le tableau des responsables d'un user
// le login du user est passé en paramêtre ainsi que le tableau (vide) des resp
//renvoit un tableau indexé de resp_login => "absent" ou "present" 
function get_tab_resp_du_user($user_login,  $DEBUG=FALSE)
{

	$tab_resp=array();
	if($_SESSION['config']['responsable_virtuel']==TRUE)
	{
		$tab_resp["conges"]="present";
	}
	else
	{
		if($DEBUG==TRUE) {echo ">> RECHERCHE des RESPONSABLES de : $user_login<br>\n";}
		// recup du resp indiqué dans la table users (sauf s'il est resp de lui meme)
		$req = 'SELECT u_resp_login FROM conges_users WHERE u_login=\''.SQL::quote($user_login).'\'';
		$res = SQL::query($req);

		$rec = $res->fetch_array();
		if ($rec['u_resp_login'] !== NULL)
			$tab_resp[$rec['u_resp_login']]="present";
		
		
		// recup des resp des groupes du user 
		if($_SESSION['config']['gestion_groupes']==TRUE)
		{
			$list_groups=get_list_groupes_du_user($user_login,  $DEBUG);
			if($list_groups!="")
			{
				$tab_gid=explode(",", $list_groups);
				foreach($tab_gid as $gid)
				{
					$gid=trim($gid);
					$sql2='SELECT gr_login FROM conges_groupe_resp WHERE gr_gid='.SQL::quote($gid).' AND gr_login!=\''.SQL::quote($user_login).'\'';
					$ReqLog1 = SQL::query($sql2);

					while ($resultat1 = $ReqLog1->fetch_array())
					{
						//attention à ne pas mettre 2 fois le meme resp dans le tableau
						if (in_array($resultat1["gr_login"], $tab_resp)==FALSE)
//							$tab_resp[]=$resultat1["gr_login"];
							$tab_resp[$resultat1["gr_login"]]="present";
					}
				}
			}
		}
		if($DEBUG==TRUE) {echo "tab_resp intermediaire =\n"; print_r($tab_resp); echo "<br>\n";}

		/************************************/
		// gestion des absence des responsables :
		// on verifie que les resp sont présents, si tous absent, on cherhe les resp des resp, et ainsi de suite ....
		if($_SESSION['config']['gestion_cas_absence_responsable']==TRUE)
		{
			if($DEBUG==TRUE) {echo "gestion des absence des responsables<br>\n"; }
			
			// on va verifier si les resp récupérés sont absents 
			$nb_present=count($tab_resp);
			foreach ($tab_resp as $current_resp => $presence )
			{
				// verif dans la base si le current_resp est absent :
				$req = 'SELECT p_num 
	                                     FROM conges_periode 
	                                     WHERE p_login =\''.SQL::quote($current_resp).'\'
	                                     AND p_etat = \'ok\'
	                                     AND TO_DAYS(conges_periode.p_date_deb) <= TO_DAYS(NOW()) 
	                                     AND TO_DAYS(conges_periode.p_date_fin) >= TO_DAYS(NOW())';
				$ReqLog_3 = SQL::query($req);
				if($ReqLog_3->num_rows!=0)
				{
					$nb_present=$nb_present-1;
					$tab_resp[$current_resp]="absent";
				}
			}
			
			//si aucun resp present on recupere les resp du resp
			if($nb_present==0)
			{
				$new_tab_resp=array();
				if($DEBUG==TRUE) { echo "zero resp présent<br>\n"; }
				foreach ($tab_resp as $current_resp => $presence)
				{
					// attention ,on evite le cas ou le user est son propre resp (sinon on boucle infiniment)
					if($current_resp != $user_login)
						$new_tab_resp = array_merge  ( $new_tab_resp , get_tab_resp_du_user($current_resp,  $DEBUG));
				}
				$tab_resp = array_merge  ( $tab_resp, $new_tab_resp);
			}
			
		}
		// FIN gestion des absence des responsables :
		/************************************/
	}
	
	if($DEBUG==TRUE) {echo "return tab_resp =\n"; print_r($tab_resp); echo "<br>\n";}
	return $tab_resp;
}


// construit le tableau des grands responsables d'un user
// (tab des grd resp des groupes à double_valid dont le user fait partie
// le login du user est passé en paramêtre ainsi que le tableau (vide) des resp
function get_tab_grd_resp_du_user($user_login, &$tab_grd_resp,  $DEBUG=FALSE)
{

	// recup des resp des groupes du user
	if($_SESSION['config']['gestion_groupes']==TRUE)
	{
		$list_groups=get_list_groupes_du_user($user_login,  $DEBUG);
		if($DEBUG==TRUE) { echo "list_groups : <br>$list_groups<br>\n"; }

		if($list_groups!="")
		{
			$tab_gid=explode(",", $list_groups);
			foreach($tab_gid as $gid)
			{
				$gid=trim($gid);
				$sql1='SELECT ggr_login FROM conges_groupe_grd_resp WHERE ggr_gid='.SQL::quote($gid);
				$ReqLog1 = SQL::query($sql1);

				while ($resultat1 = $ReqLog1->fetch_array())
				{
					//attention à ne pas mettre 2 fois le meme resp dans le tableau
					if (in_array($resultat1["ggr_login"], $tab_grd_resp)==FALSE)
						$tab_grd_resp[]=$resultat1["ggr_login"];
				}
			}
		}
	}
}


function valid_ldap_user($username, $DEBUG=FALSE)
{
/* fonction utilisée avec le mode d'authentification ldap.
   En effet, si un utilisateur (enregistré dans le ldap) tente de se
connecter alors qu'il n'a pas de compte dans
   la base, il n'y a aucun message qui lui indique !

   Retourne TRUE, si tout est ok... ($username dans la table conges_users)
   False, sinon

*/
	// connexion MySQL + selection de la database sur le serveur

	$req = 'SELECT COUNT(*) FROM conges_users WHERE u_login=\''.SQL::quote($username);
	$res = SQL::query($req);
	$cpt = $res->fetch_array();
	$cpt = $cpt[0];

	return ( $cpt != 0 );

}


// verifie si un user est responasble ou pas
// renvoit TRUE si le login est responsable dans la table conges_users, FALSE sinon.
function is_resp($login)
{
	static $sql_is_resp = array();
	if (!isset($sql_is_resp[$login])) {
		// recup de qq infos sur le user
		$select_info='SELECT u_is_resp FROM conges_users WHERE u_login=\''.SQL::quote($login).'\'';
		$ReqLog_info = SQL::query($select_info);

		$resultat_info = $ReqLog_info->fetch_array();
		$sql_is_resp[$login]=$resultat_info["u_is_resp"];
	}

	return ($sql_is_resp[$login]=='Y');
}

// verifie si un user est HR ou pas
// renvoit TRUE si le login est HR dans la table conges_users, FALSE sinon.
function is_hr($login,  $DEBUG=FALSE)
{
	static $sql_is_hr = array();
	if (!isset($sql_is_hr[$login])) {
		// recup de qq infos sur le user
		$select_info="SELECT u_is_hr FROM conges_users WHERE u_login='$login' ";
		$ReqLog_info = SQL::query($select_info);

		$resultat_info = $ReqLog_info->fetch_array();
		$sql_is_hr[$login]=$resultat_info["u_is_hr"];
	}

	return ($sql_is_hr[$login]=='Y');
}

// verifie si un user est responasble d'un secon user
// renvoit TRUE si le $resp_login est responsable du $user_login, FALSE sinon.
function is_resp_of_user($resp_login, $user_login,  $DEBUG=FALSE)
{
	// recup de qq infos sur le user
	$select_info='SELECT u_resp_login FROM conges_users WHERE u_login=\''.SQL::quote($user_login);
	$ReqLog_info = SQL::query($select_info);

	$resultat_info = $ReqLog_info->fetch_array();
	$sql_resp_login=$resultat_info["u_resp_login"];

	return ($resp_login==$sql_resp_login);
}



// verifie si un user est administrateur ou pas
// renvoit TRUE si le login est administrateur dans la table conges_users, FALSE sinon.
function is_admin($login, $DEBUG=FALSE)
{
	static $sql_is_admin = array();
	if (!isset($sql_is_admin[$login])) {
		// recup de qq infos sur le user
		$select_info='SELECT u_is_admin FROM conges_users WHERE u_login=\''.SQL::quote($login).'\'';
		$ReqLog_info = SQL::query($select_info);

		$resultat_info = $ReqLog_info->fetch_array();
		$sql_is_admin[$login]=$resultat_info["u_is_admin"];
	}

	return ($sql_is_admin[$login]=='Y');
}


// verifie si un administrateur est responsable de users ou pas
// renvoit TRUE si le login est responsable dans la table conges_users, FALSE sinon.
function admin_is_responsable($login, $DEBUG=FALSE)
{
	static $sql_is_resp = array();
	if (!isset($sql_is_resp[$login])) {
		// recup de qq infos sur le responsable
		$select_info='SELECT u_is_resp FROM conges_users WHERE u_login=\''.SQL::quote($login).'\'';
		$ReqLog_info = SQL::query($select_info);

		$resultat_info = $ReqLog_info->fetch_array();
		$sql_is_resp[$login]=$resultat_info["u_is_resp"];
	}

	return ($sql_is_resp[$login]=='Y');
}



// on insert une nouvelle periode dans la table periode
// retourne le num d'auto_incremente (p_num) ou 0 en cas l'erreur
function insert_dans_periode($login, $date_deb, $demi_jour_deb, $date_fin, $demi_jour_fin, $nb_jours, $commentaire, $id_type_abs, $etat, $id_fermeture, $DEBUG=FALSE)
{

	// Récupération du + grand p_num (+ grand numero identifiant de conges)
	$sql1 = "SELECT max(p_num) FROM conges_periode" ;
	$ReqLog1 = SQL::query($sql1);
	if ( $num_new_demande = $ReqLog1->fetch_row() )
		$num_new_demande = $num_new_demande[0] +1;
	else
		$num_new_demande = 1;
	
	$sql2 = "INSERT INTO conges_periode
			SET p_login='$login',
			p_date_deb='$date_deb', p_demi_jour_deb='$demi_jour_deb',
			p_date_fin='$date_fin', p_demi_jour_fin='$demi_jour_fin',
			p_nb_jours='$nb_jours', p_commentaire='$commentaire',
			p_type='$id_type_abs', p_etat='$etat', ";
	if($id_fermeture!=0)
		$sql2 = $sql2." p_fermeture_id='$id_fermeture' ," ;
	if($etat=="demande")
		$sql2 = $sql2." p_date_demande=NOW() ," ;
	else
		$sql2 = $sql2." p_date_traitement=NOW() ," ;

	$sql2 = $sql2." p_num='$num_new_demande' " ;

	$result = SQL::query($sql2);

	if($id_fermeture!=0)
		$comment_log = "saisie de fermeture num $num_new_demande (type $id_type_abs) pour $login ($nb_jours jours) (de $date_deb $demi_jour_deb à $date_fin $demi_jour_fin)";
	elseif($etat=="demande")
		$comment_log = "demande de conges num $num_new_demande (type $id_type_abs) pour $login ($nb_jours jours) (de $date_deb $demi_jour_deb à $date_fin $demi_jour_fin)";
	else
		$comment_log = "saisie de conges num $num_new_demande (type $id_type_abs) pour $login ($nb_jours jours) (de $date_deb $demi_jour_deb à $date_fin $demi_jour_fin)";
	
	log_action($num_new_demande, $etat, $login, $comment_log, $DEBUG);

	if($result==TRUE)
		return $num_new_demande;
	else
		return 0;
}


// remplit le tableau global des jours feries a partir de la database
function init_tab_jours_feries($DEBUG=FALSE)
{
	$_SESSION["tab_j_feries"]=array();

	$sql_select="SELECT jf_date FROM conges_jours_feries ";
	$res_select = SQL::query($sql_select);

	while( $row = $res_select->fetch_array())
	{
		$_SESSION["tab_j_feries"][]=$row["jf_date"];
	}
}

// renvoit TRUE si le jour est chomé (férié), sinon FALSE (verifie dans le tableau global $_SESSION["tab_j_feries"]
function est_chome($timestamp)
{
	$j_date=date("Y-m-d", $timestamp);
	if(isset($_SESSION["tab_j_feries"]))
		return in_array($j_date, $_SESSION["tab_j_feries"]);
	else
		return FALSE;
}


// initialise le tableau des variables de config (renvoit un tableau)
function init_config_tab()
{
	static $userlogin = null;
	static $result = null;
	if ($result === null || $user_login != $_SESSION['userlogin']) {
		
		include ROOT_PATH .'version.php';
		include CONFIG_PATH .'dbconnect.php';
		include CONFIG_PATH .'config_ldap.php';
		include CONFIG_PATH .'config_CAS.php';
		$tab =array();



		/******************************************/
		//  recup des variables de version.php
		if(isset($config_php_conges_version)) {$tab['php_conges_version']=$config_php_conges_version ;}
		if(isset($config_url_site_web_php_conges)) {$tab['url_site_web_php_conges']=$config_url_site_web_php_conges ;}

		
		/******************************************/
		//  recup des variables de la table conges_appli
		$sql_appli = "SELECT appli_variable, appli_valeur FROM conges_appli";

		$req_appli = SQL::query($sql_appli) ;

		while ($data_appli = $req_appli->fetch_array())
		{
			$key=$data_appli[0];
			$value=$data_appli[1];

			$tab[$key] = $value;
		}

		/******************************************/
		//  recup des variables de la table conges_config

		$sql_config = "SELECT conf_nom, conf_valeur, conf_type FROM conges_config";
		$req_config = SQL::query($sql_config) ;

		while ($data = $req_config->fetch_array())
		{
			$key=$data[0];
			$value=$data[1];
			$type=$data[2];

			if($value == "FALSE") {
				$value = FALSE;
			}
			elseif($value == "TRUE") {
				$value = TRUE;
			}
			elseif($type == "path") {
				$value =  ROOT_PATH ."/".$value ;
			}

			$tab[$key] = $value;
		}


		/******************************************/
		//  recup des mails dans  la table conges_mail
		$sql_mail = "SELECT mail_nom, mail_subject, mail_body FROM conges_mail";
		$req_mail = SQL::query($sql_mail) ;

		while ($data_mail = $req_mail->fetch_array())
		{
			$mail_nom=$data_mail[0];
			$key1=$mail_nom."_sujet";
			$key2=$mail_nom."_contenu";
			$sujet=$data_mail[1];
			$corps=$data_mail[2];

			$tab[$key1]=$sujet ;
			$tab[$key2]=$corps ;
		}

		/******************************************/
		//  config_ldap.php
		if(isset($config_ldap_server)) {$tab['ldap_server']=$config_ldap_server ;}
		if(isset($config_ldap_protocol_version)) {$tab['ldap_protocol_version']=$config_ldap_protocol_version ;}
			else {$tab['ldap_protocol_version']=0 ;}
		if(isset($config_ldap_bupsvr)) {$tab['ldap_bupsvr']=$config_ldap_bupsvr ;}
		if(isset($config_basedn)) {$tab['basedn']=$config_basedn ;}
		if(isset($config_ldap_user)) {$tab['ldap_user']=$config_ldap_user ;}
		if(isset($config_ldap_pass)) {$tab['ldap_pass']=$config_ldap_pass ;}
		if(isset($config_searchdn)) {$tab['searchdn']=$config_searchdn ;}

		if(isset($config_ldap_prenom)) {$tab['ldap_prenom']=$config_ldap_prenom ;}
		if(isset($config_ldap_nom)) {$tab['ldap_nom']=$config_ldap_nom ;}
		if(isset($config_ldap_mail)) {$tab['ldap_mail']=$config_ldap_mail ;}
		if(isset($config_ldap_login)) {$tab['ldap_login']=$config_ldap_login ;}
		if(isset($config_ldap_nomaff)) {$tab['ldap_nomaff']=$config_ldap_nomaff ;}
		if(isset($config_ldap_filtre)) {$tab['ldap_filtre']=$config_ldap_filtre ;}
		if(isset($config_ldap_filrech)) {$tab['ldap_filrech']=$config_ldap_filrech ;}

		if(isset($config_ldap_filtre_complet)) {$tab['ldap_filtre_complet']=$config_ldap_filtre_complet ;}

		/******************************************/
		//  config_CAS.php
		if(isset($config_CAS_host)) {$tab['CAS_host']=$config_CAS_host ;}
		if(isset($config_CAS_portNumber)) {$tab['CAS_portNumber']=$config_CAS_portNumber ;}
		if(isset($config_CAS_URI)) {$tab['CAS_URI']=$config_CAS_URI ;}


		/******************************************/
		//  recup de qq infos sur le user
		if(isset($_SESSION['userlogin']))
		{
			$sql_user = "SELECT u_is_resp, u_is_admin , u_is_hr FROM conges_users WHERE u_login='".$_SESSION['userlogin']."' ";
			$req_user = SQL::query($sql_user) ;

			if($data_user = $req_user->fetch_array())
			{
				$_SESSION['is_resp']=$data_user[0] ;
				$_SESSION['is_admin']=$data_user[1] ;
				$_SESSION['is_hr']=$data_user[2] ;
			}
		}

		/******************************************/
		$result = $tab;
		if (isset($_SESSION['userlogin']))
			$user_login = $_SESSION['userlogin'];
	}
	return $result;
}




// Récupère le contenu d une variable $_GET / $_POST
function getpost_variable($variable, $default="")
{
   $valeur = (isset($_POST[$variable]) ? $_POST[$variable]  : (isset($_GET[$variable]) ? $_GET[$variable]   : $default));

   return   $valeur;
}


// recup TRUE si le user a "u_see_all" à 'Y' dans la table users, FALSE sinon
function get_user_see_all($login,  $DEBUG=FALSE)
{

	$sql1='SELECT u_see_all FROM conges_users WHERE u_login=\''.SQL::quote($login).'\'';
	$ReqLog1 = SQL::query($sql1);

	if($resultat1 = $ReqLog1->fetch_array())
	{
		$see_all=$resultat1["u_see_all"];
		return ($see_all=="Y");
	}
	else
		return FALSE;
}


// recup dans un tableau des types de conges
function recup_tableau_types_conges($DEBUG=FALSE)
{
	$tab=array();
	$sql_cong="SELECT ta_id, ta_libelle, ta_type FROM conges_type_absence WHERE ta_type='conges' ";
	$ReqLog_cong = SQL::query($sql_cong);

	while ($resultat_cong = $ReqLog_cong->fetch_array())
	{
		$id=(int)$resultat_cong['ta_id'];
		$tab[$id]= $resultat_cong['ta_libelle'];
	}
	return $tab;
}

// recup dans un tableau des types d'absence
function recup_tableau_types_absence($DEBUG=FALSE)
{
	$tab=array();
	$sql_abs="SELECT ta_id, ta_libelle FROM conges_type_absence WHERE ta_type='absences' ";
	$ReqLog_abs = SQL::query($sql_abs);

	while ($resultat_abs = $ReqLog_abs->fetch_array())
	{
		$id=$resultat_abs['ta_id'];
		$tab[$id]= $resultat_abs['ta_libelle'];
	}
	return $tab;
}

// recup dans un tableau des types de conges exceptionnels
function recup_tableau_types_conges_exceptionnels( $DEBUG=FALSE)
{
	$tab=array();
	$sql_abs="SELECT ta_id, ta_libelle FROM conges_type_absence WHERE ta_type='conges_exceptionnels' ";
	$ReqLog_abs = SQL::query($sql_abs);

	while ($resultat_abs = $ReqLog_abs->fetch_array())
	{
		$id=$resultat_abs['ta_id'];
		$tab[$id]= $resultat_abs['ta_libelle'];
	}
	return $tab;
}

// recup dans un tableau de tableau les infos des types de conges et absences
function recup_tableau_tout_types_abs( $DEBUG=FALSE)
{
	$tab=array();
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) // on prend tout les types de conges
		$sql_cong="SELECT ta_id, ta_type, ta_libelle, ta_short_libelle FROM conges_type_absence ";
	else // on prend tout les types de conges SAUF les conges exceptionnels
		$sql_cong="SELECT ta_id, ta_type, ta_libelle, ta_short_libelle FROM conges_type_absence WHERE conges_type_absence.ta_type != 'conges_exceptionnels' ";

	$ReqLog_cong = SQL::query($sql_cong);

	while ($resultat_cong = $ReqLog_cong->fetch_array())
	{
		$tab_2=array();
		$id=$resultat_cong['ta_id'];
		$tab_2['type']=$resultat_cong['ta_type'];
		$tab_2['libelle']= $resultat_cong['ta_libelle'];
		$tab_2['short_libelle']= $resultat_cong['ta_short_libelle'];
		$tab[$id]=$tab_2;
	}
	return $tab;
}


// renvoit le type d'absence (conges ou absence) d'une absence
function get_type_abs($_type_abs_id,  $DEBUG=FALSE)
{

	$sql_abs='SELECT ta_type FROM conges_type_absence WHERE ta_id=\''.SQL::quote($_type_abs_id).'\'';
	$ReqLog_abs = SQL::query($sql_abs);

	if($resultat_abs = $ReqLog_abs->fetch_array())
		return $resultat_abs["ta_type"];
	else
		return "" ;
}

// renvoit le libelle d une absence (conges ou absence) d une absence
function get_libelle_abs($_type_abs_id,  $DEBUG=FALSE)
{

	$sql_abs='SELECT ta_libelle FROM conges_type_absence WHERE ta_id=\''.SQL::quote($_type_abs_id).'\'';
	$ReqLog_abs = SQL::query($sql_abs);
	if($resultat_abs = $ReqLog_abs->fetch_array())
		return $resultat_abs['ta_libelle'];
	else
		return "" ;
}


// recup dans un tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
function recup_tableau_conges_for_user($login, $hide_conges_exceptionnels, $DEBUG=FALSE)
{


	// on pourrait tout faire en un seule select, mais cela bug si on change la prise en charge des conges exceptionnels en cours d'utilisation ...

	

	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE && ! $hide_conges_exceptionnels) // on prend tout les types de conges
		$sql_bilan = 'SELECT ta_libelle, su_nb_an, su_solde, su_reliquat FROM conges_solde_user, conges_type_absence WHERE conges_type_absence.ta_id = conges_solde_user.su_abs_id AND su_login = \''.SQL::quote($login).'\' ORDER BY su_abs_id ASC';
	else // on prend tout les types de conges SAUF les conges exceptionnels
		$sql_bilan = 'SELECT ta_libelle, su_nb_an, su_solde, su_reliquat FROM conges_solde_user, conges_type_absence WHERE conges_type_absence.ta_type != \'conges_exceptionnels\' AND conges_type_absence.ta_id = conges_solde_user.su_abs_id AND su_login = \''.SQL::quote($login).'\' ORDER BY su_abs_id ASC';

	$ReqLog_bilan = SQL::query($sql_bilan);

	$count_num_bilan = $ReqLog_bilan->num_rows;
	$tab_cong_user=array();
	
	while ($resultat_bilan = $ReqLog_bilan->fetch_array())
	{
		$tab=array();
		$sql_id=$resultat_bilan["ta_libelle"];
		$tab['nb_an']=affiche_decimal($resultat_bilan["su_nb_an"]);
		$tab['solde']=affiche_decimal($resultat_bilan["su_solde"]);
		$tab['reliquat']=affiche_decimal($resultat_bilan["su_reliquat"]);
		$tab_cong_user[$sql_id]=$tab;
	}

	return $tab_cong_user;
}


// affichage du tableau récapitulatif des solde de congés d'un user
function affiche_tableau_bilan_conges_user($login, $DEBUG=FALSE)
{
	

	$sql_1 = 'SELECT u_quotite FROM conges_users where u_login = \''.SQL::quote($login).'\'';
	$ReqLog_1 = SQL::query($sql_1) ;

	$resultat_1 = $ReqLog_1->fetch_array();
	$sql_quotite=$resultat_1["u_quotite"];

	// recup dans un tableau de tableaux les nb et soldes de conges d'un user
	$tab_cong_user = recup_tableau_conges_for_user($login, true ,$DEBUG);
	if($DEBUG==TRUE) { echo"tab_cong_user =<br>\n"; print_r($tab_cong_user); echo "<br>\n"; }

	// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE)
		$tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels($DEBUG);
		
	if($DEBUG==TRUE)
	{
		echo"tab_type_conges_exceptionnels =<br>\n";
		print_r($tab_type_conges_exceptionnels);
		echo "<br>\n";
	}
	
	// $tab_type_conges_exceptionnels=array();

	// taille du tableau à afficher
	$taille_tableau_bilan=100 + (150 * count($tab_cong_user));

	echo "<table cellpadding=\"2\" width=\"$taille_tableau_bilan\" class=\"tablo\">\n";
	echo "<tr align=\"center\"><td class=\"titre\">". _('divers_quotite') ."</td>" ;

	foreach($tab_cong_user as $id => $val)
	{
		if (($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) && ((in_array($id,$tab_type_conges_exceptionnels))))
			echo "<td class=\"titre\">". _('divers_solde_maj') ." ".$id."</td>" ;
		else
			echo "<td class=\"titre\">".$id."/ ". _('divers_an_maj') ."</td><td class=\"titre\">". _('divers_solde_maj') ." ".$id."</td>" ;
	}
	echo "</tr>\n";

	echo "<tr align=\"center\">\n";
	echo "<td>$sql_quotite%</td>\n";

	foreach($tab_cong_user as $id => $val)
	{
		if (($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) && ((in_array($id,$tab_type_conges_exceptionnels))))
			echo "<td bgcolor=\"#FF9191\"><b>".$val['solde']."</b></td>\n";
		else
			echo "<td><b>".$val['nb_an']."</b></td><td bgcolor=\"#FF9191\"><b>".$val['solde']."</b></td>\n";
	}

	echo "</tr>\n";
	echo "</table>\n";
}


// renvoit un tableau de tableau contenant les informations du user
// renvoit FALSE si erreur
function recup_infos_du_user($login, $list_groups_double_valid, $DEBUG=FALSE)
{
    $tab=array();

    $sql1 = 'SELECT u_login, u_nom, u_prenom, u_is_resp, u_resp_login, u_is_admin, u_is_hr, u_see_all, u_passwd, u_quotite, u_email, u_num_exercice FROM conges_users ' .
            'WHERE u_login=\''.SQL::quote($login).'\';';

    $ReqLog = SQL::query($sql1) ;

    if($resultat = $ReqLog->fetch_array())
    {
        $tab_user=array();
        $tab_user['login']=$resultat["u_login"];;
        $tab_user['nom']=$resultat["u_nom"];
        $tab_user['prenom']=$resultat["u_prenom"];
        $tab_user['is_resp']=$resultat["u_is_resp"];
        $tab_user['resp_login']=$resultat["u_resp_login"];
        $tab_user['is_admin']=$resultat["u_is_admin"];
        $tab_user['is_hr']=$resultat["u_is_hr"];
        $tab_user['see_all']=$resultat["u_see_all"];
        $tab_user['passwd']=$resultat["u_passwd"];
        $tab_user['quotite']=$resultat["u_quotite"];
        $tab_user['email']=$resultat["u_email"];
        $tab_user['num_exercice']=$resultat["u_num_exercice"];
        $tab_user['conges']=recup_tableau_conges_for_user($login, false, $DEBUG);

        $tab_user['double_valid'] = "N";

        // on regarde ici si le user est dans un groupe qui fait l'objet d'une double validation
        if($_SESSION['config']['double_validation_conges']==TRUE)
        {
            if($list_groups_double_valid!="") // si $resp_login est responsable d'au moins un groupe a double validation
            {
                $sql1='SELECT gu_login FROM conges_groupe_users WHERE gu_login=\''.SQL::quote($login).'\' AND gu_gid IN ('.$list_groups_double_valid.') ORDER BY gu_gid, gu_login ';
                $ReqLog1 = SQL::query($sql1);

                if($ReqLog1->num_rows  !=0)
                    $tab_user['double_valid'] = "Y";
            }
        }
        return $tab_user ;
    }
    else
        return FALSE;
}


// renvoit un tableau de tableau contenant les informations de tous les users
function recup_infos_all_users($DEBUG=FALSE)
{
	$tab=array();

	$list_groupes_double_validation=get_list_groupes_double_valid($DEBUG);
	if($DEBUG==TRUE) { echo "list_groupes_double_validation :<br>\n"; print_r($list_groupes_double_validation); echo "<br><br>\n";}

	//$sql = "SELECT u_login FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_login";
	$sql1 = "SELECT u_login FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom";

	$ReqLog = SQL::query($sql1);

	while ($resultat =$ReqLog->fetch_array())
	{
		$tab_user=array();
		$sql_login=$resultat["u_login"];

		$tab[$sql_login] = recup_infos_du_user($sql_login, $list_groupes_double_validation, $DEBUG);
	}

	return $tab ;
}


// renvoit un tableau de tableau contenant les informations de tous les users d'un groupe donné
function recup_infos_all_users_du_groupe($group_id, $DEBUG=FALSE)
{
	$tab=array();

	// recup de la liste de tous les users du groupe ...
	$list_all_users_du_groupe = get_list_users_du_groupe($group_id, $DEBUG);
	if($DEBUG==TRUE) { echo "list_all_users_du_groupe :<br>\n"; print_r($list_all_users_du_groupe); echo "<br><br>\n";}

	$list_groupes_double_validation=get_list_groupes_double_valid($DEBUG);
	if($DEBUG==TRUE) { echo "list_groupes_double_validation :<br>\n"; print_r($list_groupes_double_validation); echo "<br><br>\n";}

	if(strlen($list_all_users_du_groupe)!=0)
	{
		$tab_users_du_groupe=explode(",", $list_all_users_du_groupe);
		foreach($tab_users_du_groupe as $current_login)
		{
			$current_login = trim($current_login);
			$current_login = trim($current_login, "\'");  // on enleve les quotes qui ont été ajouté lors de la creation de la liste

			$tab[$current_login] = recup_infos_du_user($current_login, $list_groupes_double_validation, $DEBUG);
		}
	}

	return $tab ;
}



// renvoit un tableau de tableau contenant les informations de tous les users dont $login est responsable
function recup_infos_all_users_du_resp($login, $DEBUG=FALSE)
{
	$tab=array();

	// recup de la liste de tous les users du resp ...
	$list_all_users_du_resp = get_list_all_users_du_resp($login, $DEBUG);
	if($DEBUG==TRUE) { echo "list_all_users_du_resp :<br>\n"; print_r($list_all_users_du_resp); echo "<br><br>\n";}

	// recup de la liste des groupes à double validation, dont $login est responsable
	// (servira à dire pour chaque user s'il est dans un de ces groupe ou non , donc s'il fait l'objet d'une double valid ou non )
	$list_groups_double_valid_du_resp=get_list_groupes_double_valid_du_resp($login, $DEBUG);
	if($DEBUG==TRUE) { echo "list_groups_double_valid :<br>\n"; print_r($list_groups_double_valid_du_resp); echo "<br><br>\n";}

	if(strlen($list_all_users_du_resp)!=0)
	{
		$tab_users_du_resp=explode(",", $list_all_users_du_resp);
		foreach($tab_users_du_resp as $current_login)
		{
			$current_login = trim($current_login);
			$current_login = trim($current_login, "\'");  // on enleve les quotes qui ont été ajouté lors de la creation de la liste

			$tab[$current_login] = recup_infos_du_user($current_login, $list_groups_double_valid_du_resp, $DEBUG);
		}
	}

	return $tab ;
}


// renvoit un tableau de tableau contenant les informations de tous les users dont $login est GRAND responsable
function recup_infos_all_users_du_grand_resp($login, $DEBUG=FALSE)
{

	$tab=array();

	$list_groups_double_valid=get_list_groupes_double_valid_du_grand_resp($login, $DEBUG);
	if($DEBUG==TRUE) { echo "list_groups_double_valid :<br>\n"; print_r($list_groups_double_valid); echo "<br><br>\n";}

	if($list_groups_double_valid!="")
	{
		// recup de la liste des users des groupes de la liste $list_groups_double_valid
		$sql_users = 'SELECT DISTINCT(gu_login) FROM conges_groupe_users, conges_users WHERE gu_gid IN ('.SQL::quote($list_groups_double_valid).') AND gu_login=u_login ORDER BY u_nom; ';
		$ReqLog_users = SQL::query($sql_users) ;

		$list_all_users_dbl_valid="";
		while ($resultat_users =$ReqLog_users->fetch_array())
		{
			$current_login=$resultat_users["gu_login"];
			if($list_all_users_dbl_valid=="")
				$list_all_users_dbl_valid="'$current_login'";
			else
				$list_all_users_dbl_valid=$list_all_users_dbl_valid.", '$current_login'";
		}

		if($list_all_users_dbl_valid!="")
		{
			$tab_users_du_resp=explode(",", $list_all_users_dbl_valid);
			foreach($tab_users_du_resp as $current_login)
			{
				$current_login = trim($current_login);
				$current_login = trim($current_login, "\'");  // on enleve les qote qui on été ajouté lors de la creation de la liste

				$tab[$current_login] = recup_infos_du_user($current_login, $list_groups_double_valid, $DEBUG);
			}
		} //if($list_all_users_dbl_valid!="")
	} //if($list_all_users_dbl_valid!="")

	return $tab ;
}


//
// cree un tableau à partir des valeurs du enum(...) d'un champ mysql (cf structure des tables)
//    $table         = nom de la table sql
//    $column        = nom du champ sql
function get_tab_from_mysql_enum_field($table, $column, $DEBUG=FALSE)
{

   $tab=array();
   $req_enum = "DESCRIBE $table $column";
   $res_enum = SQL::query($req_enum);

   while ($row_enum = $res_enum->fetch_array())
   {
      $sql_type=$row_enum['Type'];
      // exemple : enum('autre','labo','fonction','personne','web', ....
      //echo "$sql_type<br>\n";
      $liste_enum = strstr($sql_type, '(');
      $liste_enum = substr($liste_enum, 1);    // on vire le premier caractere
      $liste_enum = substr($liste_enum, 0, strlen($liste_enum)-1);    // on vire le dernier caractere
      //echo "$liste_enum<br>\n";
      $option = strtok($liste_enum,"','");
      while ($option)
      {
         $tab[]=$option;
         $option = strtok("','");
      }
   }

   return $tab;
}

// recup l'id de la derniere absence (le max puisque c'est un auto incrément)
function get_last_absence_id($DEBUG=FALSE)
{
   $req_1="SELECT MAX(ta_id) FROM conges_type_absence ";
   $res_1 = SQL::query($req_1);
   $row_1 = $res_1->fetch_row();
   if(!$row_1)
      return 0;     // si la table est vide, on renvoit 0
   else
      return $row_1[0];
}



// execute sequentiellement les requètes d'un fichier .sql
function execute_sql_file($file, $DEBUG=FALSE)
{
	// lecture du fichier SQL
	// et execution de chaque ligne ....
	$lines = file ($file);
	$sql_requete="";
	foreach ($lines as $line_num => $line)
	{
		$line=trim($line);
	    if( (substr($line, 0, 1)!="#") && ($line!="") )  //on ne prend pas les lignes de commentaire
	    {
			$sql_requete = $sql_requete.$line ;
			if(substr($sql_requete, -1, 1)==";") // alors la requete est finie !
			{
				if($DEBUG==TRUE)
					echo "$sql_requete<br>\n";
				$result = SQL::query($sql_requete);
				$sql_requete="";
			}
	    }
	}
	return TRUE;
}


function affiche_bouton_retour($session, $DEBUG=FALSE)
{
	// Bouton de retour : différent suivant si on vient des pages d'install ou de l'appli
	// $_SESSION['from_config'] est initialisée dans install/index
	if(isset($_SESSION['from_config']) && ($_SESSION['from_config']==TRUE))
		echo "<center><a href=\"".$_SESSION['config']['URL_ACCUEIL_CONGES']."/config/?session=$session\">". _('form_retour') ."</a></center>\n";
	else
	{
		echo "<form action=\"\" method=\"POST\">\n";
		echo "<center><input type=\"button\" value=\"". _('form_close_window') ."\" onClick=\"javascript:window.close();\"></center>\n";
		echo "</form>\n";
	}
}





// verif des droits du user à afficher la page qu'il demande (pour éviter les hacks par bricolage d'URL)
// verif_droits_user($session, "is_admin", $DEBUG);
function verif_droits_user($session, $niveau_droits, $DEBUG=FALSE)
{
    if($DEBUG==TRUE) { print_r($_SESSION); echo "<br><br>\n"; }
	
	$niveau_droits = strtolower($niveau_droits);

    // verif si $_SESSION['is_admin'] ou $_SESSION['is_resp'] ou $_SESSION['is_hr'] =="N"
    if($_SESSION[$niveau_droits]=="N")
    {
        // on recupere les variable utiles pour le suite :
        $url_accueil_conges = $_SESSION['config']['URL_ACCUEIL_CONGES'] ;

        $lang_divers_acces_page_interdit =  _('divers_acces_page_interdit') ;
        $lang_divers_user_disconnected   =  _('divers_user_disconnected') ;
        $lang_divers_veuillez            =  _('divers_veuillez') ;
        $lang_divers_vous_authentifier   =  _('divers_vous_authentifier') ;

        // on delete la session et on renvoit sur l'authentification (page d'accueil)
        session_delete($session);

        // message d'erreur !
        echo "<center>\n";
        echo "<font color=\"red\">$lang_divers_acces_page_interdit</font><br>$lang_divers_user_disconnected<br>\n";
        echo "$lang_divers_veuillez <a href='$url_accueil_conges/index.php' target='_top'> $lang_divers_vous_authentifier .</a>\n";
        echo "</center>\n";

        exit;
    }

}


// on lit le contenu du répertoire lang et on parse les nom de ficher (ex lang_fr_francais.php)
function affiche_select_from_lang_directory($select_name="lang")
{
	echo 'TODO';
}

// on insert les logs des periodes de conges
// retourne TRUE ou FALSE
function log_action($num_periode, $etat_periode, $login_pour, $comment, $DEBUG=FALSE)
{

	if(isset($_SESSION['userlogin']))
		$user = $_SESSION['userlogin'] ;
	else
		$user = "inconnu";

	$sql1 = 'INSERT INTO conges_logs
		SET log_p_num=\''.SQL::quote($num_periode).'\',
			log_user_login_par=\''.SQL::quote($user).'\',
			log_user_login_pour=\''.SQL::quote($login_pour).'\',
			log_etat=\''.SQL::quote($etat_periode).'\',
			log_comment=\''.SQL::quote($comment).'\',
			log_date=NOW()';
	$result = SQL::query($sql1);

	return $result;
}


// remplit le tableau global des jours feries a partir de la database
function init_tab_jours_fermeture($user,  $DEBUG=FALSE)
{

	$_SESSION["tab_j_fermeture"]=array();

	$sql_select='SELECT DISTINCT jf_date FROM conges_jours_fermeture, conges_groupe_users WHERE gu_login=\''.SQL::quote($user).'\' AND gu_gid=jf_gid';
	$res_select = SQL::query($sql_select);

	while( $row = $res_select->fetch_array())
		$_SESSION["tab_j_fermeture"][]=$row["jf_date"];
}

// renvoit TRUE si le jour est fermé (fermeture), sinon FALSE (verifie dans le tableau global $_SESSION["tab_j_fermeture"]
function est_ferme($timestamp)
{
	$j_date=date("Y-m-d", $timestamp);
	if(isset($_SESSION["tab_j_fermeture"]))
		return in_array($j_date, $_SESSION["tab_j_fermeture"]);
	else
		return FALSE;
}


// renvoit le "su_reliquat" pour un user et un type de conges donné
function get_reliquat_user_conges($login, $type_abs,  $DEBUG=FALSE)
{

	$select_info='SELECT su_reliquat FROM conges_solde_user WHERE su_login=\''.SQL::quote($login).'\' AND su_abs_id=\''.SQL::quote($type_abs).'\'';
	$ReqLog_info = SQL::query($select_info);

	$resultat_info = $ReqLog_info->fetch_array();
	$sql_reliquat=$resultat_info["su_reliquat"];

	return $sql_reliquat;
}


/*
 NE SERT PLUS ! utiliser la suivante à la place ....
function soustrait_solde_user($user_login, $user_nb_jours_pris, $type_abs,  $DEBUG=FALSE)
{
	if($_SESSION['config']['autorise_reliquats_exercice']==TRUE)
	{
		$reliquat=get_reliquat_user_conges($user_login, $type_abs,  $DEBUG);
		//echo "reliquat = $reliquat<br>\n";
		if($reliquat>$user_nb_jours_pris)
			$new_reliquat = $reliquat-$user_nb_jours_pris;
		else
			$new_reliquat = 0;
			
		$sql2 = "UPDATE conges_solde_user SET su_solde=su_solde-$user_nb_jours_pris, su_reliquat=$new_reliquat WHERE su_login='$user_login' AND su_abs_id=$type_abs " ;

	}
	else
	{
		$sql2 = "UPDATE conges_solde_user SET su_solde=su_solde-$user_nb_jours_pris WHERE su_login='$user_login' AND su_abs_id=$type_abs " ;
	}
	
	$ReqLog2 = SQL::query($sql2) ;				
}
*/




//  soustrait_solde_et_reliquat_user($user_login, $user_nb_jours_pris, $type_abs, $date_deb, $demi_jour_deb, $date_fin, $demi_jour_fin,  $DEBUG=FALSE)
/*	si date_fin_conges < date_limite_reliquat => alors on décompte dans reliquats 
	si date_debut_conges > date_limite_reliquat => alors on ne décompte pas dans reliquats
	si gonges demandé est à cheval sur la date_limite_reliquat => il faut decompter le nb_jours_pris du solde, puis il faut 
	calculer le nb_jours_avant pris avant la date limite, et on le decompte des reliquats, et calculer le nb_jours_apres 
	d'apres la data limite et ne pas le décompter des reliquats !!!
*/			
function soustrait_solde_et_reliquat_user($user_login, $user_nb_jours_pris, $type_abs, $date_deb, $demi_jour_deb, $date_fin, $demi_jour_fin,  $DEBUG=FALSE)
{

	//si on autorise les reliquats
	if($_SESSION['config']['autorise_reliquats_exercice']==TRUE)
	{
		//recup du reliquat du user pour ce type d'absence
		$reliquat=get_reliquat_user_conges($user_login, $type_abs,  $DEBUG);
		//echo "reliquat = $reliquat<br>\n";

		// s'il y a une date limite d'utilisationdes reliquats (au format jj-mm)
		if($_SESSION['config']['jour_mois_limite_reliquats']!=0)
		{
			//si date_fin_conges < date_limite_reliquat => alors on décompte dans reliquats
			if($date_fin < $_SESSION['config']['date_limite_reliquats'])
			{
				if($reliquat>$user_nb_jours_pris)
					$new_reliquat = $reliquat-$user_nb_jours_pris;
				else
					$new_reliquat = 0;
			}
			//si date_debut_conges > date_limite_reliquat => alors on ne décompte pas dans reliquats
			elseif($date_deb >= $_SESSION['config']['date_limite_reliquats'])
			{
				$new_reliquat = $reliquat;
			}
			//si gonges demandé est à cheval sur la date_limite_reliquat => il faut decompter le nb_jours_pris du solde, puis il faut 
			//calculer le nb_jours_avant pris avant la date limite, et on le decompte des reliquats, et calculer le nb_jours_apres 
			//d'apres la data limite et ne pas le décompter des reliquats !!!
			else
			{
				include 'fonctions_calcul.php' ;
				//include_once('fonctions_calcul.php'):
				//require_once('fonctions_calcul.php'):
				$nb_reliquats_a_deduire = compter($user_login, $date_deb, $_SESSION['config']['date_limite_reliquats'], $demi_jour_deb, "pm", null ,  $DEBUG);
				
				if($nb_reliquats_a_deduire>$user_nb_jours_pris)
					$new_reliquat = $nb_reliquats_a_deduire-$user_nb_jours_pris;
				else
					$new_reliquat = 0;
			}
		}
		// s'il n'y a pas de date limite d'utilisation des reliquats
		else
		{
			if($reliquat>$user_nb_jours_pris)
				$new_reliquat = $reliquat-$user_nb_jours_pris;
			else
				$new_reliquat = 0;
		}
			
	$sql2 = 'UPDATE conges_solde_user SET su_solde=su_solde-'.SQL::quote($user_nb_jours_pris).', su_reliquat='.SQL::quote($new_reliquat).' WHERE su_login=\''.SQL::quote($user_login).'\'  AND su_abs_id='.SQL::quote($type_abs).' ';
		
	}
	else
	{
		$sql2 = 'UPDATE conges_solde_user SET su_solde=su_solde-'.SQL::quote($user_nb_jours_pris).' WHERE su_login=\''.SQL::quote($user_login).'\'  AND su_abs_id=\''.$type_abs.'\' ';
	}
	
	$ReqLog2 = SQL::query($sql2) ;				
}







// renvoit un tableau de tableau contenant les informations de tous les users dont $login est HR responsable
function recup_infos_all_users_du_hr($login, $DEBUG=FALSE)
{
    $tab=array();

    $list_groupes_double_validation=get_list_groupes_double_valid($DEBUG);
    if($DEBUG==TRUE) { echo "list_groupes_double_validation :<br>\n"; print_r($list_groupes_double_validation); echo "<br><br>\n";}

    //$sql = "SELECT u_login FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_login";
    $sql1 = "SELECT u_login FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom";

    $ReqLog = SQL::query($sql1) ;

    while ($resultat = $ReqLog->fetch_array())
    {
        $tab_user=array();
        $sql_login=$resultat["u_login"];

        $tab[$sql_login] = recup_infos_du_user($sql_login, $list_groupes_double_validation, $DEBUG);
    }

    return $tab ;
}


// recup de la liste de TOUS les users pour le responsable RH
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_all_users_du_hr($resp_login, $DEBUG=FALSE)
{

	$list_users="";

	$sql1="SELECT DISTINCT(u_login) FROM conges_users WHERE u_login!='conges' AND u_login!='admin'  ORDER BY u_nom  ";

	$ReqLog1 = SQL::query($sql1);

	while ($resultat1 = $ReqLog1->fetch_array())
	{
		$current_login=$resultat1["u_login"];
		if($list_users=="")
			$list_users="'$current_login'";
		else
			$list_users=$list_users.", '$current_login'";
	}
	

	if($DEBUG==TRUE) { echo "list_users = $list_users<br>\n" ;}

	return $list_users;
}

// recup de la liste de tous les groupes pour le mode RH

function get_list_groupes_pour_rh($user_login, $DEBUG=FALSE)
{
	$list_group="";

	$sql1="SELECT g_gid FROM conges_groupe ORDER BY g_gid";
	$ReqLog1 = SQL::query($sql1);

	if($ReqLog1->num_rows != 0)
	{
		while ($resultat1 = $ReqLog1->fetch_array())
		{
			$current_group=$resultat1["g_gid"];
			if($list_group=="")
				$list_group="$current_group";
			else
				$list_group=$list_group.", $current_group";
		}
	}
	if($DEBUG==TRUE) { echo "list_group = $list_group<br>\n" ;}

	return $list_group;
}

// recup de la liste des users des groupes dont $resp_login est responsable mais ne remonte pas les autres responsables
// renvoit une liste de login entre quotes et séparés par des virgules
function get_list_users_des_groupes_du_resp_sauf_resp($resp_login, $DEBUG=FALSE)
{

	$list_users_des_groupes_du_resp_sauf_resp="";

	$list_groups=get_list_groupes_du_resp($resp_login, $DEBUG);
	if($list_groups!="") // si $resp_login est responsable d'au moins un groupe
	{
		$sql1="SELECT DISTINCT(gu_login) FROM conges_groupe_users WHERE gu_gid IN ($list_groups) AND gu_login NOT IN (SELECT gr_login FROM conges_groupe_resp WHERE gr_gid IN ($list_groups)) ORDER BY gu_login ";
		$ReqLog1 = SQL::query($sql1);

		while ($resultat1 = $ReqLog1->fetch_array())
		{
			$current_login=$resultat1["gu_login"];
			if($list_users_des_groupes_du_resp_sauf_resp=="")
				$list_users_des_groupes_du_resp_sauf_resp="'$current_login'";
			else
				$list_users_des_groupes_du_resp_sauf_resp=$list_users_des_groupes_du_resp_sauf_resp.", '$current_login'";
		}
	}
	if($DEBUG==TRUE) { echo "list_users_des_groupes_du_resp_sauf_resp= $list_users_des_groupes_du_resp_sauf_resp<br>\n" ;}

	return $list_users_des_groupes_du_resp_sauf_resp;

}

