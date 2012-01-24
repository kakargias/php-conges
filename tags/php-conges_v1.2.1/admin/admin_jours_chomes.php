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

include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$DEBUG=FALSE;
//$DEBUG=TRUE ;

// verif des droits du user � afficher la page
verif_droits_user($session, "is_admin", $DEBUG);


$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}


	/*** initialisation des variables ***/
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$choix_action 				= getpost_variable("choix_action");
	$year_calendrier_saisie		= getpost_variable("year_calendrier_saisie", 0);
	$tab_checkbox_j_chome		= getpost_variable("tab_checkbox_j_chome");
	/*************************************/
	
	if($DEBUG==TRUE) { echo "choix_action = $choix_action # year_calendrier_saisie = $year_calendrier_saisie<br>\n"; print_r($year_calendrier_saisie) ; echo "<br>\n"; }

	//connexion mysql
	$mysql_link = connexion_mysql() ;

	if($choix_action=="")
		saisie($year_calendrier_saisie, $mysql_link, $DEBUG);
	elseif($choix_action=="confirm")
		confirm_saisie($tab_checkbox_j_chome, $DEBUG);
	elseif($choix_action=="commit")
		commit_saisie($tab_checkbox_j_chome, $mysql_link, $DEBUG);

	mysql_close($mysql_link);
	


/***************************************************************/
/**********  FONCTIONS  ****************************************/

function saisie($year_calendrier_saisie, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	// si l'ann�e n'est pas renseign�e, on prend celle du jour
	if($year_calendrier_saisie==0)
		$year_calendrier_saisie=date("Y");
		
	// on construit le tableau de l'ann�e consid�r�e
	$tab_year=array();
	get_tableau_jour_feries($year_calendrier_saisie, $tab_year, $mysql_link, $DEBUG);
	if($DEBUG==TRUE) { echo "tab_year = "; print_r($tab_year); echo "<br>\n"; }
	
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_jours_chomes_titre']."</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ;

	// table contenant le fieldset
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr align=\"center\">\n";
		echo "<td>\n";
		echo "<fieldset class=\"cal_saisie\">\n";
			// tableau contenant les mois
			echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			// ligne des boutons de d�filement
			echo "<tr align=\"center\">\n";
				$year_calendrier_saisie_prec=$year_calendrier_saisie-1;
				$year_calendrier_saisie_suiv=$year_calendrier_saisie+1;
					// recul d'un an
					echo "<td align=\"center\" class=\"big\">\n";
					echo "<a href=\"$PHP_SELF?session=$session&year_calendrier_saisie=$year_calendrier_saisie_prec\"> \n";
					echo "<img src=\"../img/simfirs.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"".$_SESSION['lang']['admin_jours_chomes_annee_precedente']."\" title=\"".$_SESSION['lang']['admin_jours_chomes_annee_precedente']."\"> \n";
					echo "</a>\n";
					echo "</td>\n";
					echo "<td colspan=\"2\" align=\"center\" class=\"big\">$year_calendrier_saisie</td>\n";
					//echo "<td align=\"center\" class=\"big\">&nbsp;</td>\n";
					// avance d'un an
					echo "<td align=\"center\" class=\"big\">\n";
					echo "<a href=\"$PHP_SELF?session=$session&year_calendrier_saisie=$year_calendrier_saisie_suiv\"> \n";
					echo "<img src=\"../img/simlast.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"".$_SESSION['lang']['admin_jours_chomes_annee_suivante']."\" title=\"".$_SESSION['lang']['admin_jours_chomes_annee_suivante']."\"> \n";
					echo "</a>\n";
					echo "</td>\n";

			echo "</tr>\n";
			// ligne janvier / fevrier / mars / avril
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // janvier
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "01", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // fevrier
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "02", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // mars
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "03", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // avril
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "04", $tab_year);  
				echo "</td>\n";
			echo "</tr>\n";
			// ligne mai / juin / juillet / aout
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // mai
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "05", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // juin
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "06", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // juillet
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "07", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // aout
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "08", $tab_year);  
				echo "</td>\n";
			echo "</tr>\n";
			// ligne septembre / octobre / novembre / decembre
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // septembre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "09", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // octobre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "10", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // novembre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "11", $tab_year);  
				echo "</td>\n";
				echo "<td>\n"; // d�cembre
					affiche_calendrier_saisie_jours_chomes($year_calendrier_saisie, "12", $tab_year);  
				echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		echo "</fieldset>\n";
		echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	//table contenant les bountons
	echo "<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n";
	echo "<tr align=\"center\">\n";
	echo "<td>\n";
		echo "<input type=\"hidden\" name=\"choix_action\" value=\"confirm\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">  \n";
		echo "<input type=\"button\" value=\"".$_SESSION['lang']['form_cancel']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
			

	echo "</form>\n" ;

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}



// affichage du calendrier du mois avec les case � cocher
// on lui passe en parametre le tableau des jour chom� de l'ann�e (pour pr�-cocher certaines cases)
function  affiche_calendrier_saisie_jours_chomes($year, $mois, $tab_year, $DEBUG=FALSE) 
{
	$jour_today=date("j");
	$jour_today_name=date("D");
	
	$first_jour_mois_timestamp=mktime (0,0,0,$mois,1,$year);
	$mois_name=date_fr("F", $first_jour_mois_timestamp);
	$first_jour_mois_rang=date("w", $first_jour_mois_timestamp);      // jour de la semaine en chiffre (0=dim , 6=sam)
	if($first_jour_mois_rang==0)
		$first_jour_mois_rang=7 ;    // jour de la semaine en chiffre (1=lun , 7=dim)
		
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"250\" bgcolor=".$_SESSION['config']['semaine_bgcolor'].">\n";
	/* affichage  2 premieres lignes */
	echo "	<tr align=\"center\" bgcolor=\"".$_SESSION['config']['light_grey_bgcolor']."\"><td colspan=7 class=\"titre\"> $mois_name $year </td></tr>\n" ;
	echo "	<tr bgcolor=\"".$_SESSION['config']['light_grey_bgcolor']."\">\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['lundi_1c']."</td>\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['mardi_1c']."</td>\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['mercredi_1c']."</td>\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['jeudi_1c']."</td>\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['vendredi_1c']."</td>\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['samedi_1c']."</td>\n";
	echo "		<td class=\"cal-saisie2\">".$_SESSION['lang']['dimanche_1c']."</td>\n";
	echo "	</tr>\n" ;
	
	/* affichage ligne 1 du mois*/
	echo "<tr>\n";
	// affichage des cellules vides jusqu'au 1 du mois ...
	for($i=1; $i<$first_jour_mois_rang; $i++) 
	{
		if( (($i==6)&&($_SESSION['config']['samedi_travail']==FALSE)) || (($i==7)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";
	}
	// affichage des cellules cochables du 1 du mois � la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++) 
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 2 du mois*/
	echo "<tr>\n";
	for($i=8-$first_jour_mois_rang+1; $i<15-$first_jour_mois_rang+1; $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 3 du mois*/
	echo "<tr>\n";
	for($i=15-$first_jour_mois_rang+1; $i<22-$first_jour_mois_rang+1; $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 4 du mois*/
	echo "<tr>\n";
	for($i=22-$first_jour_mois_rang+1; $i<29-$first_jour_mois_rang+1; $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	echo "</tr>\n";
	
	/* affichage ligne 5 du mois (peut etre la derniere ligne) */
	echo "<tr>\n";
	for($i=29-$first_jour_mois_rang+1; $i<36-$first_jour_mois_rang+1 && checkdate($mois, $i, $year); $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	for($i; $i<36-$first_jour_mois_rang+1; $i++) 
	{
		if( (($i==35-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE)) || (($i==36-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)) )
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	/* affichage ligne 6 du mois (derniere ligne)*/
	echo "<tr>\n";
	for($i=36-$first_jour_mois_rang+1; checkdate($mois, $i, $year); $i++) 
	{
		$j_timestamp=mktime (0,0,0,$mois,$i,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);
		
		if(in_array ("$j_date", $tab_year))
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\" checked></td>";
		else
			echo "<td  class=\"cal-saisie $td_second_class\">$j_day<input type=\"checkbox\" name=\"tab_checkbox_j_chome[$j_date]\" value=\"Y\"></td>";
	}
	for($i; $i<43-$first_jour_mois_rang+1; $i++) 
	{
		if( (($i==42-$first_jour_mois_rang)&&($_SESSION['config']['samedi_travail']==FALSE)) || (($i==43-$first_jour_mois_rang)&&($_SESSION['config']['dimanche_travail']==FALSE)))
			$bgcolor=$_SESSION['config']['week_end_bgcolor'];
		else
			$bgcolor=$_SESSION['config']['semaine_bgcolor'];
		echo "<td bgcolor=$bgcolor class=\"cal-saisie2\">-</td>";	
	}
	echo "</tr>\n";
	
	echo "</table>\n";
}


function confirm_saisie($tab_checkbox_j_chome, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
		
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_jours_chomes_titre']."</h1>\n";

	//echo "tab_checkbox_j_chome : <br>\n";
	//print_r($tab_checkbox_j_chome);

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
		foreach($tab_checkbox_j_chome as $key => $value)
		{
			$date_affiche=eng_date_to_fr($key);
			echo "$date_affiche<br>\n";
			echo "<input type=\"hidden\" name=\"tab_checkbox_j_chome[$key]\" value=\"$value\">\n";
		}
		echo "<input type=\"hidden\" name=\"choix_action\" value=\"commit\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['admin_jours_chomes_confirm']."\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_cancel']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}

function commit_saisie($tab_checkbox_j_chome, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
		
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_jours_chomes_titre']."</h1>\n";

	if($DEBUG==TRUE) { echo "tab_checkbox_j_chome : <br>\n"; print_r($tab_checkbox_j_chome); echo "<br>\n"; }
	
	// si l'ann�e est d�ja renseign�e dans la database, on efface ttes les dates de l'ann�e
	if(verif_year_deja_saisie($tab_checkbox_j_chome, $mysql_link, $DEBUG)==TRUE)
		$result=delete_year($tab_checkbox_j_chome, $mysql_link, $DEBUG);
		
	// on insert les nouvelles dates saisies
	$result=insert_year($tab_checkbox_j_chome, $mysql_link, $DEBUG);
	
	if($result==TRUE)
		echo "<br>".$_SESSION['lang']['form_modif_ok'].".<br><br>\n";
	else
		echo "<br>".$_SESSION['lang']['form_modif_not_ok']." !<br><br>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_close_window']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


function insert_year($tab_checkbox_j_chome, $mysql_link, $DEBUG=FALSE)
{
	$sql_insert="";
	foreach($tab_checkbox_j_chome as $key => $value)
	{
		$sql_insert="INSERT INTO conges_jours_feries SET jf_date='$key' ;";
		$result = requete_mysql($sql_insert, $mysql_link, "insert_year", $DEBUG);
	}

	return TRUE;
}

function delete_year($tab_checkbox_j_chome, $mysql_link, $DEBUG=FALSE)
{
	$date_1=key($tab_checkbox_j_chome);
	$year=substr($date_1, 0, 4);
	//echo "year= $year<br>\n";
	$sql_delete="DELETE FROM conges_jours_feries WHERE jf_date LIKE '$year%' ;";
	$result = requete_mysql($sql_delete, $mysql_link, "delete_year", $DEBUG);

	return TRUE;
}

function verif_year_deja_saisie($tab_checkbox_j_chome, $mysql_link, $DEBUG=FALSE)
{
	$date_1=key($tab_checkbox_j_chome);
	$year=substr($date_1, 0, 4);
	//echo "year= $year<br>\n";
	$sql_select="SELECT jf_date FROM conges_jours_feries WHERE jf_date LIKE '$year%' ;";
	$relog = mysql_query($sql_select, $mysql_link);
//	attention ne fonctionne pas avec requete_mysql
//	$relog = requete_mysql($sql_select, $mysql_link, "verif_year_deja_saisie", $DEBUG);
	
	$count=mysql_num_rows($relog);
	if($count==0)
		return FALSE;
	else
		return TRUE;
}


// retourne un tableau des jours feri�s de l'ann�e dans un tables pass� par r�f�rence
function get_tableau_jour_feries($year, &$tab_year, $mysql_link, $DEBUG=FALSE)
{
	$sql_select=" SELECT jf_date FROM conges_jours_feries WHERE jf_date LIKE '$year-%' ;" ;
	$res_select = mysql_query($sql_select, $mysql_link);
//	attention ne fonctionne pas avec requete_mysql
//	$res_select = requete_mysql($sql_select, $mysql_link, "get_tableau_jour_feries", $DEBUG);
	$num_select = mysql_num_rows($res_select);
	
	if($num_select!=0)
	{
		while($result_select = mysql_fetch_array($res_select))
		{
			$tab_year[]=$result_select["jf_date"];
		}
	}
	

}

?>