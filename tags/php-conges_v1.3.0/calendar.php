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
$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
include("INCLUDE.PHP/session.php");

$DEBUG=FALSE;
//$DEBUG=TRUE ;


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";


	echo "<TITLE>calendar</TITLE>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
	echo "<link href=\"".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";

	echo "</head>\n";

	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$HTTP_REFERER=$_SERVER['HTTP_REFERER'] ;
	// GET / POST
	$year          = getpost_variable("year", date("Y")) ;
	$mois          = getpost_variable("mois", date("n")) ;
	$champ_date    = getpost_variable("champ_date") ;
	

	/*************************************/
	
// ATTENTION ne pas mettre cet appel avant les include car plantage sous windows !!!

echo "<script language=\"javascript\">\n";
echo "function envoi_date(valeur)\n";
echo "{window.opener.document.forms[0].$champ_date.value=valeur; window.close()}\n";
echo "</Script>\n";

		
	echo "<body>\n";
	echo "<CENTER>\n";
					
	$jour_today=date("j");
	
	$mois_timestamp = mktime (0,0,0,$mois,1,$year);
	$nom_mois=date_fr("F", $mois_timestamp);
	
	// AFFICHAGE PAGE
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
	echo "   <h3>$nom_mois  $year</h3>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
		
	// AFFICHAGE  TABLEAU (CALENDRIER)
	affiche_calendar($year, $mois, $DEBUG);
		
	echo "   </td>\n";
	echo "</tr>\n";
	
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
	/**********************/
	/* Boutons de defilement */
	affichage_boutons_defilement_calendar($mois, $year, $champ_date, $DEBUG) ;

	echo "   </td>\n";
	echo "</tr>\n";
	
	echo "</table>\n";
	
	
	
	
/*******************************************************************************/
/**********  FONCTIONS  ********************************************************/

	
/******************************/
/* Boutons de defilement */
/******************************/
function affichage_boutons_defilement_calendar($mois, $year, $champ_date, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

		if($mois==12) $next_mois=1;  else $next_mois=$mois+1 ;
		if($mois==1) $prev_mois=12;  else $prev_mois=$mois-1 ;
		
		if($prev_mois==12) $prev_year=$year-1; else $prev_year=$year;
		if($next_mois==1) $next_year=$year+1; else $next_year=$year;

		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%\" >\n";
		echo "<tr>\n";
		echo "<td align=\"left\">
				<a href=\"$PHP_SELF?session=$session&mois=$prev_mois&year=$prev_year&champ_date=$champ_date\" method=\"POST\"> << ".$_SESSION['lang']['divers_mois_precedent_maj_1']." </a>
			</td>\n";
		echo "<td align=\"right\">
				<a href=\"$PHP_SELF?session=$session&mois=$next_mois&year=$next_year&champ_date=$champ_date\" method=\"POST\"> ".$_SESSION['lang']['divers_mois_suivant_maj_1']." >> </a>
			</td>\n";
		echo "</tr></table>\n";

}



// AFFICHAGE  TABLEAU (CALENDRIER)
function affiche_calendar($year, $mois, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$jour_today      =date("j");
	$jour_today_name =date("D");
	$today_timestamp =mktime (0,0,0,date("m"),date("j"),date("Y"));
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
//	$mois_name=date("F", $first_jour_mois_timestamp);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
	//$first_jour_mois_name=date("D", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)
		
	if($mois<10)
		$mois_value="0$mois";
	else
		$mois_value="$mois";
		
	// mise en gras du jour d'aujourd'hui
	
	

	// TABLEAU
	
	echo "<table cellpadding=\"1\" class=\"tablo-cal\" width=\"90%\">\n";
	/* affichage ligne des jours de la semaine*/
	echo "<tr>\n";
//	echo "<td bgcolor=$bgcolor class=\"cal-saisie2\"><a href=\"\" onClick=\"javascript:envoi_date('$jour-$mois_value-$year');\">$j</a></td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['lundi_2c']."</td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['mardi_2c']."</td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['mercredi_2c']."</td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['jeudi_2c']."</td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['vendredi_2c']."</td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['samedi_2c']."</td>";
	echo "<td class=\"calendar-header\">".$_SESSION['lang']['dimanche_2c']."</td>";
	echo "</tr>\n";

	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) 
	{
		if( ($i==6) || ($i==7) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo "<td bgcolor=$bgcolor class=\"calendar\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois � la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) 
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$jour="0$j";
		if( ($i==6) || ($i==7) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			
		// affichage du jour d'aujourd'hui en gras
		$current_day_timestamp =mktime (0,0,0,$mois,$jour,$year);
		if($today_timestamp==$current_day_timestamp)
			$text="<b>$j</b>";
		else
			$text=$j;

		echo "<td bgcolor=$bgcolor class=\"calendar\"><a href=\"\" onClick=\"javascript:envoi_date('$jour-$mois_value-$year');\" class=\"calendar\">$text</a></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8; $i<15; $i++) 
	{
		$j=$i-$first_jour_mois_rang+1;
		if($j<10)
			$jour="0$j";
		else
			$jour=$j;
		if( ($i==13) || ($i==14) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			
		// affichage du jour d'aujourd'hui en gras
		$current_day_timestamp =mktime (0,0,0,$mois,$jour,$year);
		if($today_timestamp==$current_day_timestamp)
			$text="<b>$j</b>";
		else
			$text=$j;

		echo "<td bgcolor=$bgcolor class=\"calendar\"><a href=\"\" onClick=\"javascript:envoi_date('$jour-$mois_value-$year');\" class=\"calendar\">$text</a></td>";			
		
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15; $i<22; $i++) 
	{
		$j=$i-$first_jour_mois_rang+1;
		if( ($i==20) || ($i==21) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			
		// affichage du jour d'aujourd'hui en gras
		$current_day_timestamp =mktime (0,0,0,$mois,$j,$year);
		if($today_timestamp==$current_day_timestamp)
			$text="<b>$j</b>";
		else
			$text=$j;

		echo "<td bgcolor=$bgcolor class=\"calendar\"><a href=\"\" onClick=\"javascript:envoi_date('$j-$mois_value-$year');\" class=\"calendar\">$text</a></td>";
		
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22; $i<29; $i++) {
		$j=$i-$first_jour_mois_rang+1;
		if( ($i==27) || ($i==28) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			
		// affichage du jour d'aujourd'hui en gras
		$current_day_timestamp =mktime (0,0,0,$mois,$j,$year);
		if($today_timestamp==$current_day_timestamp)
			$text="<b>$j</b>";
		else
			$text=$j;

		echo "<td bgcolor=$bgcolor class=\"calendar\"><a href=\"\" onClick=\"javascript:envoi_date('$j-$mois_value-$year');\" class=\"calendar\">$text</a></td>";

	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29; ($i<36 && checkdate($mois, $i-$first_jour_mois_rang+1, $year)); $i++) 
	{
		$j=$i-$first_jour_mois_rang+1;
		if( ($i==34) || ($i==35) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			
		// affichage du jour d'aujourd'hui en gras
		$current_day_timestamp =mktime (0,0,0,$mois,$j,$year);
		if($today_timestamp==$current_day_timestamp)
			$text="<b>$j</b>";
		else
			$text=$j;

		echo "<td bgcolor=$bgcolor class=\"calendar\"><a href=\"\" onClick=\"javascript:envoi_date('$j-$mois_value-$year');\" class=\"calendar\">$text</a></td>";
	}
	for($i=$j+$first_jour_mois_rang; $i<36; $i++) {
		if( ($i==34) || ($i==35) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo "<td bgcolor=$bgcolor class=\"calendar\">-</td>";	
	}
	echo "</tr>\n";
	
	if(checkdate($mois, 36-$first_jour_mois_rang+1, $year))
	{
		/* affichage ligne 6 du mois (derniere ligne)*/
		echo "<tr>\n";
		for($i=36; checkdate($mois, $i-$first_jour_mois_rang+1, $year); $i++) 
		{
			$j=$i-$first_jour_mois_rang+1;
			if( ($i==41) || ($i==42) )
				$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			else
				$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			
		// affichage du jour d'aujourd'hui en gras
		$current_day_timestamp =mktime (0,0,0,$mois,$j,$year);
		if($today_timestamp==$current_day_timestamp)
			$text="<b>$j</b>";
		else
			$text=$j;

			echo "<td bgcolor=$bgcolor class=\"calendar\"><a href=\"\" onClick=\"javascript:envoi_date('$j-$mois_value-$year');\" class=\"calendar\">$text</a></td>";
		}
		for($i=$j+$first_jour_mois_rang; $i<43; $i++) 
		{
			if( ($i==41) || ($i==42) )
				$bgcolor=$_SESSION['config']['week_end_bgcolor'];
			else
				$bgcolor=$_SESSION['config']['semaine_bgcolor'];
			echo "<td bgcolor=$bgcolor class=\"calendar\">-</td>";	
		}
		echo "</tr>\n";
	}

	echo "</table>\n";
}

?>

</CENTER>
</body>
</html>