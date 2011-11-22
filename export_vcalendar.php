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

//appel de PHP-IDS que si version de php > 5.1.2
if(phpversion() > "5.1.2") { include("controle_ids.php") ;}
$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
include("INCLUDE.PHP/session.php");

$DEBUG=FALSE;
//$DEBUG=TRUE


	/*** initialisation des variables ***/
	$session=session_id();
	/************************************/

	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET	/ POST
	$action     = getpost_variable("action") ;
	$user_login = getpost_variable("user_login") ;
	$date_debut = getpost_variable("date_debut") ;
	$date_fin   = getpost_variable("date_fin") ;
	$choix_format  = getpost_variable("choix_format") ;
	/*************************************/


	//connexion mysql
	$mysql_link = connexion_mysql() ;

	if($action=="export")
	{
		if($choix_format=="ical")
			export_ical($user_login, $date_debut, $date_fin, $mysql_link, $DEBUG);
		else
			export_vcal($user_login, $date_debut, $date_fin, $mysql_link, $DEBUG);

		$comment_log = "export ical/vcal ($date_debut -> $date_fin) ";
		log_action(0, "", $user_login, $comment_log, $mysql_link, $DEBUG);
	}
	else
		form_saisie($user_login, $date_debut, $date_fin, $DEBUG);


	mysql_close($mysql_link);




/*******************************************************************************/
/**********  FONCTIONS  ********************************************************/

function form_saisie($user, $date_debut, $date_fin, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	include("fonctions_javascript.php") ;

	$date_today=date("d-m-Y");
	if($date_debut=="")
		$date_debut=$date_today;
	if($date_fin=="")
		$date_fin=$date_today;

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : </title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['export_cal_titre']."</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	// saisie des dates
	echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "<b>".$_SESSION['lang']['export_cal_from_date']."</b> <input type=\"text\" name=\"date_debut\" size=\"10\" maxlength=\"10\" value=\"$date_debut\" style=\"background-color: #D4D4D4; \" readonly=\"readonly\"> \n";
		echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('calendar.php?session=$session&champ_date=date_debut','calendardebut',250,220);\">\n";
		echo "<img src=\"img/1day.png\" border=\"0\" title=\"".$_SESSION['lang']['export_cal_saisir_debut']."\" alt=\"".$_SESSION['lang']['export_cal_saisir_debut']."\"></a>\n";
		echo "</td>\n";
		echo "<td align=\"center\">\n";
		echo "<b>".$_SESSION['lang']['export_cal_to_date']."</b> <input type=\"text\" name=\"date_fin\" size=\"10\" maxlength=\"10\" value=\"$date_fin\" style=\"background-color: #D4D4D4; \" readonly=\"readonly\"> \n";
		echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('calendar.php?session=$session&champ_date=date_fin','calendarfin',250,220);\">\n";
		echo "<img src=\"img/1day.png\" border=\"0\" title=\"".$_SESSION['lang']['export_cal_saisir_fin']."\" alt=\"".$_SESSION['lang']['export_cal_saisir_fin']."\"></a>\n";
		echo "</td>\n";
	echo "</tr>\n";
	// ligne vide
	echo "<tr>\n";
		echo "<td colspan=\"2\">&nbsp;\n";
		echo "</td>\n";
	echo "</tr>\n";
	// saisie du format
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
		echo "<table align=\"center\"><tr>\n";
		echo "<td><b>".$_SESSION['lang']['export_cal_format']."</b> : </td>\n";
		echo "<td align=\"left\"><b>ical</b><input type=\"radio\" name=\"choix_format\" value=\"ical\" checked> </td>\n";
		echo "<td align=\"right\"> <b>vcal</b><input type=\"radio\" name=\"choix_format\" value=\"vcal\"></td>\n";
		echo "</tr></table>\n";
	echo "</td>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"export\">\n";
	echo "	<input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_close_window']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


// export des p�riodes des conges et d'absences comprise entre les 2 dates , dans un fichier texte au format ICAL
function export_ical($user_login, $date_debut, $date_fin, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	//inverse l'ordre de la date jj-mm-yyyy --> yyy-mm-jj
	$good_date_debut=inverse_date($date_debut, $DEBUG);
	$good_date_fin=inverse_date($date_fin, $DEBUG);

	if($good_date_debut > $good_date_fin)  // si $date_debut posterieure a $date_fin
		// redirige vers page de saisie
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?session=$session&date_debut=$date_debut&date_fin=$date_fin&choix_format=ical\">";
	else
	{
		/********************************/
		// initialisation de variables communes a ttes les periodes

		// recup des infos du user
		$tab_infos_user=recup_infos_du_user($_SESSION['userlogin'], "", $mysql_link, $DEBUG);

		$tab_types_abs=recup_tableau_tout_types_abs($mysql_link, $DEBUG) ;

		if(function_exists("date_default_timezone_get"))   // car date_default_timezone_get() n'existe que depuis PHP 5.1
			$DTSTAMP=date("Ymd").date_default_timezone_get();
		else
			$DTSTAMP=date("Ymd")."T142816Z";    // copier depuis un fichier ical

		/********************************/
		// affichage dans un fichier non html !

		header("content-type: application/ics");
		header("Content-disposition: filename=php_conges.ics");


		echo "BEGIN:VCALENDAR\r\n" .
				"PRODID:-//php_conges ".$_SESSION['config']['installed_version']."\r\n" .
				"VERSION:2.0\r\n\r\n";

		// SELECT des periodes � exporter .....
		// on prend toutes les periodes de conges qui chevauchent la periode donn�e par les dates demand�es
		$sql_periodes="SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_commentaire, p_type  " .
				"FROM conges_periode WHERE p_login='".$_SESSION['userlogin']."' AND p_etat='ok' AND ((p_date_deb>='$good_date_debut' AND  p_date_deb<='$good_date_fin') OR (p_date_fin>='$good_date_debut' AND p_date_fin<='$good_date_fin'))";
		$res_periodes = requete_mysql($sql_periodes, $mysql_link, "export_ical", $DEBUG);

		if($num_periodes=mysql_num_rows($res_periodes)!=0)
		{
			while ($result_periodes = mysql_fetch_array($res_periodes))
			{
				$sql_date_debut=$result_periodes['p_date_deb'];
				$sql_demi_jour_deb=$result_periodes['p_demi_jour_deb'];
				$sql_date_fin=$result_periodes['p_date_fin'];
				$sql_demi_jour_fin=$result_periodes['p_demi_jour_fin'];
				$sql_type=$result_periodes['p_type'];

				// PB : les fichiers ical et vcal doivent �tre encod�s en UTF-8, or php ne g�re pas l'utf-8
				// on remplace donc les caract�res sp�ciaux de la chaine de caract�res
				$sql_comment=remplace_accents($result_periodes['p_commentaire']);

				// m�me probl�me
				$type_abs=remplace_accents($tab_types_abs[$sql_type]['libelle']) ;

				$tab_date_deb=explode("-", $sql_date_debut);
				$tab_date_fin=explode("-", $sql_date_fin);
				if($sql_demi_jour_deb=="am")
					$DTSTART=$tab_date_deb[0].$tab_date_deb[1].$tab_date_deb[2]."T000000Z";   // .....
				else
					$DTSTART=$tab_date_deb[0].$tab_date_deb[1].$tab_date_deb[2]."T120000Z";   // .....

				if($sql_demi_jour_fin=="am")
					$DTEND=$tab_date_fin[0].$tab_date_fin[1].$tab_date_fin[2]."T120000Z";   // .....
				else
					$DTEND=$tab_date_fin[0].$tab_date_fin[1].$tab_date_fin[2]."T235900Z";   // .....

					echo "BEGIN:VEVENT\r\n" .
						"DTSTAMP:$DTSTAMP\r\n" .
						"ORGANIZER;CN=".$_SESSION['userlogin'].":MAILTO:".$tab_infos_user['email']."\r\n" .
						"CREATED:$DTSTAMP\r\n" .
						"UID:php_conges\r\n" .
						"SEQUENCE:0\r\n" .
						"LAST-MODIFIED:$DTSTAMP\r\n";
				if($sql_comment!="")
					echo "DESCRIPTION:$sql_comment\r\n";
				echo "SUMMARY:$type_abs\r\n" .
						"CLASS:PUBLIC\r\n" .
						"PRIORITY:1\r\n" .
						"DTSTART:$DTSTART\r\n" .
						"DTEND:$DTEND\r\n" .
						"TRANSP:OPAQUE\r\n" .
						"END:VEVENT\r\n\r\n" ;
			}
		}

		echo "END:VCALENDAR\r\n";

	}
}


// export des p�riodes des conges et d'absences comprise entre les 2 dates , dans un fichier texte au format VCAL
function export_vcal($user_login, $date_debut, $date_fin, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	//inverse l'ordre de la date jj-mm-yyyy --> yyy-mm-jj
	$good_date_debut=inverse_date($date_debut, $DEBUG);
	$good_date_fin=inverse_date($date_fin, $DEBUG);

	if($good_date_debut > $good_date_fin)  // si $date_debut posterieure a $date_fin
		// redirige vers page de saisie
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=$PHP_SELF?session=$session&date_debut=$date_debut&date_fin=$date_fin&choix_format=ical\">";
	else
	{
		/********************************/
		// initialisation de variables communes a ttes les periodes

		// recup des infos du user
		$tab_infos_user=recup_infos_du_user($_SESSION['userlogin'], "", $mysql_link, $DEBUG);

		$tab_types_abs=recup_tableau_tout_types_abs($mysql_link, $DEBUG) ;

		if(function_exists("date_default_timezone_get"))   // car date_default_timezone_get() n'existe que depuis PHP 5.1
			$DTSTAMP=date("Ymd").date_default_timezone_get();
		else
			$DTSTAMP=date("Ymd")."T142816Z";    // copier depuis un fichier ical

		/********************************/
		// affichage dans un fichier non html !

		header("content-type: application/ics");
		header("Content-disposition: filename=php_conges.ics");


		echo "BEGIN:VCALENDAR\r\n" .
				"PRODID:-//php_conges ".$_SESSION['config']['installed_version']."\r\n" .
				"VERSION:1.0\r\n\r\n";

		// SELECT des periodes � exporter .....
		// on prend toutes les periodes de conges qui chevauchent la periode donn�e par les dates demand�es
		$sql_periodes="SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_commentaire, p_type  " .
				"FROM conges_periode WHERE p_login='".$_SESSION['userlogin']."' AND p_etat='ok' AND (p_date_deb>='$good_date_debut' AND  p_date_deb<='$good_date_fin') OR (p_date_fin>='$good_date_debut' AND p_date_fin<='$good_date_fin')";
		$res_periodes = requete_mysql($sql_periodes, $mysql_link, "export_ical", $DEBUG);

		if($num_periodes=mysql_num_rows($res_periodes)!=0)
		{
			while ($result_periodes = mysql_fetch_array($res_periodes))
			{
				$sql_date_debut=$result_periodes['p_date_deb'];
				$sql_demi_jour_deb=$result_periodes['p_demi_jour_deb'];
				$sql_date_fin=$result_periodes['p_date_fin'];
				$sql_demi_jour_fin=$result_periodes['p_demi_jour_fin'];
				$sql_type=$result_periodes['p_type'];

				// PB : les fichiers ical et vcal doivent �tre encod�s en UTF-8, or php ne g�re pas l'utf-8
				// on remplace donc les caract�res sp�ciaux de la chaine de caract�res
				$sql_comment=remplace_accents($result_periodes['p_commentaire']);

				// m�me probl�me
				$type_abs=remplace_accents($tab_types_abs[$sql_type]['libelle']) ;

				$tab_date_deb=explode("-", $sql_date_debut);
				$tab_date_fin=explode("-", $sql_date_fin);
				if($sql_demi_jour_deb=="am")
					$DTSTART=$tab_date_deb[0].$tab_date_deb[1].$tab_date_deb[2]."T000000Z";   // .....
				else
					$DTSTART=$tab_date_deb[0].$tab_date_deb[1].$tab_date_deb[2]."T120000Z";   // .....

				if($sql_demi_jour_fin=="am")
					$DTEND=$tab_date_fin[0].$tab_date_fin[1].$tab_date_fin[2]."T120000Z";   // .....
				else
					$DTEND=$tab_date_fin[0].$tab_date_fin[1].$tab_date_fin[2]."T235900Z";   // .....

				echo "BEGIN:VEVENT\r\n" .
						"DTSTART:$DTSTART\r\n" .
						"DTEND:$DTEND\r\n" .
						"CREATED:$DTSTAMP\r\n" .
						"UID:php_conges\r\n" .
						"SEQUENCE:1\r\n" .
						"LAST-MODIFIED:$DTSTAMP\r\n" .
						"X-ORGANIZER;MAILTO:".$tab_infos_user['email']."\r\n";
				if($sql_comment!="")
					echo "DESCRIPTION:$sql_comment\r\n";
				echo "SUMMARY:$type_abs\r\n" .
						"CLASS:PUBLIC\r\n" .
						"PRIORITY:1\r\n" .
						"TRANSP:0\r\n" .
						"END:VEVENT\r\n\r\n" ;
			}
		}

		echo "END:VCALENDAR\r\n";

	}
}


/*******************************************************************************/
/**********  FONCTIONS  DE GENERATION VCAL ET ICAL *****************************/


//inverse l'ordre de la date jj-mm-yyyy --> yyy-mm-jj
function inverse_date($date, $DEBUG=FALSE)
{
	$tab=explode("-", $date);
	$reverse_date=$tab[2]."-".$tab[1]."-".$tab[0] ;

	if($DEBUG==TRUE) { echo "reverse_date : $date -> $reverse_date<br>\n" ; }

	return $reverse_date;
}


// remplace le caractere accentu� ou transform�, par le caractere normal !
function remplace_accents($str)
{
	$accent        = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
	$sans_accent   = array("a", "a", "a", "e", "e", "e", "e", "i", "i", "o", "o", "u", "u", "u", "c");
	return str_replace($accent, $sans_accent, $str) ;

}


?>
