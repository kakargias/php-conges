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

//session_start();
include("config.php") ;
include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
include("INCLUDE.PHP/session.php");
if($config_verif_droits==1){ include("INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php
	/*** initialisation des variables ***/
	$printable=0;
	/************************************/

	echo "<TITLE> CONGES : Calendrier</TITLE>\n";
	echo "<link href=\"$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<script src=\"libraries/functions.js\" type=\"text/javascript\" language=\"javascript\"></script>\n";

	echo "</head>\n";

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$HTTP_REFERER=$_SERVER['HTTP_REFERER'] ;
	// GET
	if(isset($_GET['printable'])) { $printable=$_GET['printable']; }
	if(isset($_GET['page_initiale'])) { $page_initiale=$_GET['page_initiale']; }
	if(isset($_GET['year'])) { $year=$_GET['year']; }
	if(isset($_GET['mois'])) { $mois=$_GET['mois']; }
	if(isset($_GET['first_jour'])) { $first_jour=$_GET['first_jour']; }
	// POST
	/*************************************/
	
	if($printable!=1)  // si version écran :
		echo "<body text=\"#000000\" bgcolor=$config_bgcolor link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=$config_bgimage>\n";
	else               // si version imprimable :
		echo "<body text=\"#000000\" bgcolor=\"#FFFFFF\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" >\n";
	
	echo "<CENTER>\n";
	
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	
	// INIT des variables
	if(!isset($page_initiale))
		$page_initiale=$HTTP_REFERER ;
	if(!isset($year))
		$year=date("Y");
	if(!isset($mois))
		$mois=date("n");
	if(!isset($first_jour))
		$first_jour=1;
			
	$jour_today=date("j");
	
	$mois_timestamp = mktime (0,0,0,$mois,1,$year);
	$nom_mois=date("F", $mois_timestamp);
	
	
	// AFFICHAGE PAGE
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%%\">\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
	echo "   <H2>CALENDRIER des CONGES</H2>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "   <td align=\"center\">\n";
	echo "   <h3>$nom_mois  $year</h3>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
		
	// AFFICHAGE  TABLEAU (CALENDRIER)
	affichage_calendrier($year, $mois, $first_jour, $jour_today, $printable);
		
	echo "   </td>\n";
	echo "</tr>\n";
	
	if($printable!=1) { // si version ecran :
		echo "<tr>\n";
		echo "   <td align=\"center\">\n";

		/* Boutons de defilement */
		affichage_boutons_defilement($first_jour, $mois, $year) ;

		echo "   </td>\n";
		echo "</tr>\n";
	}
	
	echo "<tr>\n";
	echo "   <td><img src=\"img/shim.gif\" width=\"200\" height=\"10\" border=\"0\" vspace=\"0\" hspace=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
	
		/**********************/
		/* SOUS LE CALENDRIER */
		/**********************/
		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%%\">\n";
		echo "<tr>\n";
		echo "   <td width=\"25%%\" valign=\"top\" align=\"left\">\n";
		if($printable!=1) // si version ecran :
			echo "      <br><a href=\"$PHP_SELF?session=$session&printable=1&page_initiale=$page_initiale&year=$year&mois=$mois&first_jour=$first_jour\" target=\"_blank\" method=\"post\">version imprimable</a>\n";
		else  // si version imprimable
			echo "      <img src=\"img/shim.gif\" width=\"25\" height=\"25\" border=\"0\" vspace=\"0\" hspace=\"0\">\n";
		echo "   </td>\n";
		echo "   <td width=\"25%%\" valign=\"top\" align=\"left\">\n";
		echo "      <img src=\"img/shim.gif\" width=\"25\" height=\"25\" border=\"0\" vspace=\"0\" hspace=\"0\">\n";
		echo "   </td>\n";
		echo "   <td width=\"25%%\" valign=\"top\" align=\"right\">\n";
		echo "      <h4>légende :</h4>\n";
		echo "   </td>\n";
		echo "   <td width=\"25%%\" valign=\"top\" align=\"left\">\n";
				affiche_legende();
		echo "   </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	echo "   </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	
	if($printable!=1) { // si version ecran :
		echo "<center><hr align=\"center\" size=\"2\" width=\"90%%\"></center> \n" ;
	}
	
	/********************/
	/* bouton retour */
	/********************/
	if($printable!=1) { // si version ecran :
		printf("<form action=\"$page_initiale\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"session\" value=\"$session\">\n");
		printf("<input type=\"submit\" value=\"Retour\">\n");
		printf("</form>\n" ) ;
	}
	
	
	
/*******************************************************************************/

	
// retourne le timestamp calculé du jour precedent
function jour_precedent($jour, $mois, $year) {
	return mktime (0,0,0,$mois,$jour -1,$year);
}

// retourne le timestamp calculé du jour suivant
function jour_suivant($jour, $mois, $year) {
	return mktime (0,0,0,$mois,$jour +1,$year);
}

/******************************/
/* Boutons de defilement */
/******************************/
function affichage_boutons_defilement($first_jour, $mois, $year) 
{
global $PHP_SELF, $session, $session_username;
global $page_initiale ;

		if($mois==12) $next_mois=1;  else $next_mois=$mois+1 ;
		if($mois==1) $prev_mois=12;  else $prev_mois=$mois-1 ;
		
		if($prev_mois==12) $prev_year=$year-1; else $prev_year=$year;
		if($next_mois==1) $next_year=$year+1; else $next_year=$year;

		$prev_first_jour=date("j", jour_precedent($first_jour, $mois, $year))  ;
			$prev_first_jour_mois=date("n", jour_precedent($first_jour, $mois, $year))  ;
			$prev_first_jour_year=date("Y", jour_precedent($first_jour, $mois, $year))  ;
		$next_first_jour=date("j", jour_suivant($first_jour, $mois, $year)) ;
			$next_first_jour_mois=date("n", jour_suivant($first_jour, $mois, $year)) ;
			$next_first_jour_year=date("Y", jour_suivant($first_jour, $mois, $year)) ;

		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%%\" >\n";
		echo "<tr>\n";
		echo "<td align=\"left\">
				<a href=\"$PHP_SELF?session=$session&first_jour=1&mois=$prev_mois&year=$prev_year&page_initiale=$page_initiale\" method=\"POST\"> << Mois Precedent </a>
			</td>\n";
		echo "<td align=\"left\">
				<a href=\"$PHP_SELF?session=$session&first_jour=$prev_first_jour&mois=$prev_first_jour_mois&year=$prev_first_jour_year&page_initiale=$page_initiale\" method=\"POST\"> << Jour Precedent </a>
			</td>\n";
		echo "<td align=\"right\">
				<a href=\"$PHP_SELF?session=$session&first_jour=$next_first_jour&mois=$next_first_jour_mois&year=$next_first_jour_year&page_initiale=$page_initiale\" method=\"POST\"> Jour Suivant >> </a>
			</td>\n";
		echo "<td align=\"right\">
				<a href=\"$PHP_SELF?session=$session&first_jour=1&mois=$next_mois&year=$next_year&page_initiale=$page_initiale\" method=\"POST\"> Mois Suivant >> </a>
			</td>\n";
		echo "</tr></table>\n";
		echo "<br>\n";

}



// AFFICHAGE  TABLEAU (CALENDRIER)
function affichage_calendrier($year, $mois, $first_jour, $jour_today, $printable)
{
global $PHP_SELF, $session, $session_username;
global $link;
global $page_initiale ;
//global $first_jour, $mois, $year;
//global $first_jour_timestamp ;
//global $jour_today ;

// couleurs :
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_conges_bgcolor;
global $config_temps_partiel_bgcolor, $config_demande_conges_bgcolor, $config_absence_autre_bgcolor;
global $config_rtt_comme_conges ;

		//connexion mysql
		$link = connexion_mysql() ;

		// Récupération des users
		$sql = "SELECT u_login, u_nom, u_prenom, u_solde_jours, u_solde_rtt, u_quotite FROM conges_users WHERE u_login!='conges' ORDER BY u_nom, u_prenom";
		$ReqLog = mysql_query($sql, $link) or die("ERREUR : calendrier.php : ".mysql_error());

		// AFFICHAGE TABLEAU
		if($printable!=1)  // si version ecran :
			printf("<table cellpadding=\"1\" class=\"tablo-cal\" width=\"80%%\">\n");
			//printf("<table cellpadding=\"1\" cellspacing=\"1\" border=\"1\" width=\"80%%\">\n");
		else               // si version imprimable :
			printf("<table cellpadding=\"1\" cellspacing=\"0\" border=\"1\" width=\"80%%\">\n");

		// affichage premiere ligne (dates)
		echo "<tr align=\"center\">";
		echo "	<td class=\"cal-user\">NOM</td><td class=\"cal-user\">PRENOM</td><td class=\"cal-user\">%</td><td class=\"cal-user\">solde congés</td>";
		if($config_rtt_comme_conges==1)
			echo "<td class=\"cal-user\">solde rtt</td>";
			
		for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois, $j, $year);
			$j_name=date("D", $j_timestamp);
			$bgcolor=get_bgcolor_of_the_day_in_the_week($j_timestamp);

			if($j==$jour_today) 
				printf("<td bgcolor=\"%s\" class=\"cal-day\"><b>%s %d/%s</b></td>", $bgcolor, $j_name, $j, $mois);
			else
				printf("<td bgcolor=\"%s\" class=\"cal-day\">%s %d/%s</td>", $bgcolor, $j_name, $j, $mois);
		}
		if($first_jour!=1) {
			for($j=1; $j<$first_jour; $j++) {
				if($mois==12) $mois_select=1;  else $mois_select=$mois+1 ;
				
				$j_timestamp=mktime (0,0,0,$mois_select, $j, $year);
				$j_name=date("D", $j_timestamp);
				$bgcolor=get_bgcolor_of_the_day_in_the_week($j_timestamp);

				if($j==$jour_today) 
					printf("<td bgcolor=\"%s\" class=\"cal-day\"><b>%s %d/%s</b></td>", $bgcolor, $j_name, $j, $mois_select);
				else
					printf("<td bgcolor=\"%s\" class=\"cal-day\">%s %d/%s</td>", $bgcolor, $j_name, $j, $mois_select);
			}	
		}
		printf("</tr>\n", date("j") );

		
		// affichage lignes suivantes (users)
		// pour chaque user :
		while ($resultat = mysql_fetch_array($ReqLog)) {
			$sql_login=$resultat["u_login"];
			$sql_nom=$resultat["u_nom"];
			$sql_prenom=$resultat["u_prenom"];
			$sql_solde_jours=affiche_decimal($resultat["u_solde_jours"]);
			$sql_solde_rtt=affiche_decimal($resultat["u_solde_rtt"]);
			$sql_quotite=$resultat["u_quotite"];

			printf("<tr align=\"center\" class=\"cal-ligne-user\">\n");
			//printf("<td>%s</td><td>%s</td><td>%d%%</td><td>%s</td>", $sql_nom, $sql_prenom, $sql_quotite, $sql_solde_jours);
			echo "<td class=\"cal-user\">$sql_nom</td><td class=\"cal-user\">$sql_prenom</td><td class=\"cal-user\">$sql_quotite%</td><td class=\"cal-user\">$sql_solde_jours</td>";
			if($config_rtt_comme_conges==1)
				echo "<td class=\"cal-user\">$sql_solde_rtt</td>";

			// pour chaque jour : (du premier jour demandé à la fin du mois ...)
			for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
			{
				$j_timestamp=mktime (0,0,0,$mois, $j, $year);
				$bgcolor=get_bgcolor_of_the_day_in_the_week($j_timestamp);

				$mois_select=$mois;
				$year_select=$year ;
				
				// affichage de la cellule correspondant au jour et au user considéré 
				affiche_cellule_jour_user($sql_login, $j_timestamp, $year, $mois_select, $j, $bgcolor, $printable);
				
			}
			// si le premier jour demandeé n'est pas le 1ier du mois , on va jusqu'à la meme date le mois suivant :
			if($first_jour!=1) {
				// pour chaque jour jusqu'a la date voulue : (meme num de jour le mois suivant)
				for($j=1; $j<$first_jour; $j++) 
				{
					$j_timestamp=mktime (0,0,0,$mois+1, $j, $year);
					$bgcolor=get_bgcolor_of_the_day_in_the_week($j_timestamp);

					if($mois==12) {
						$mois_select=1;
						$year_select=$year+1 ;
					}
					else {
						$mois_select=$mois+1 ;
						$year_select=$year ;
					}
					
					// affichage de la cellule correspondant au jour et au user considéré 
					affiche_cellule_jour_user($sql_login, $j_timestamp, $year_select, $mois_select, $j, $bgcolor, $printable);
				
				}
			}
			printf("\n</tr>\n");
		}

		printf("</table>\n\n");

		mysql_close($link);
}


// affichage de la cellule correspondant au jour et au user considéré 
function affiche_cellule_jour_user($sql_login, $j_timestamp, $year_select, $mois_select, $j, $bgcolor, $printable)
{
global $session, $session_username;
global $link;
global $config_semaine_bgcolor, $config_temps_partiel_bgcolor, $config_conges_bgcolor ;
global $config_demande_conges_bgcolor, $config_absence_autre_bgcolor ;

	// recup des infos ARTT ou Temps Partiel :
	recup_infos_artt_du_jour($sql_login, $j_timestamp, $val_matin, $val_aprem);
	
	//## AFICHAGE ##
	if(($val_matin=="Y")&&($val_aprem=="Y"))
	{
		$bgcolor=$config_temps_partiel_bgcolor;
		printf("<td bgcolor=\"%s\" class=\"cal-day\">abs</td>", $bgcolor);
	}
	elseif($val_matin=="Y")
	{
		$bgcolor=$config_temps_partiel_bgcolor;
		printf("<td bgcolor=\"%s\" class=\"cal-day\">abs/-</td>", $bgcolor);
	}
	elseif($val_aprem=="Y")
	{
		$bgcolor=$config_temps_partiel_bgcolor;
		printf("<td bgcolor=\"%s\" class=\"cal-day\">-/abs</td>", $bgcolor);
	}
	else
	{
		// Récupération des conges du user
		if(($session_username==$sql_login)&&($printable!=1))  // on affichera les demandes du user dans le calendrier seulement pour la version non imprimable, et pas pour les autres users
			$user_periode_sql = "SELECT  p_date_deb, p_date_fin, p_etat FROM conges_periode WHERE p_login='$sql_login' AND ( p_etat='pris' OR  p_etat='rtt_prise' OR  p_etat='demande' OR  p_etat='demande_rtt' OR  p_etat='formation' OR  p_etat='mission' OR  p_etat='autre' ) AND (p_date_deb<='".$year_select."-".$mois_select."-".$j."' AND p_date_fin>='".$year_select."-".$mois_select."-".$j."') ";
		else
			$user_periode_sql = "SELECT  p_date_deb, p_date_fin, p_etat FROM conges_periode WHERE p_login='$sql_login' AND ( p_etat='pris' OR  p_etat='rtt_prise' OR  p_etat='formation' OR  p_etat='mission' OR  p_etat='autre' ) AND (p_date_deb<='".$year_select."-".$mois_select."-".$j."' AND p_date_fin>='".$year_select."-".$mois_select."-".$j."') ";
		//echo "user_periode_sql = $user_periode_sql<br>\n";
		$user_periode_request = mysql_query($user_periode_sql, $link);
		$nb_resultat_periode = mysql_num_rows($user_periode_request);
		if($nb_resultat_periode>0) {    // si on est dans une periode de conges
			if($bgcolor==$config_semaine_bgcolor)
				$bgcolor=$config_conges_bgcolor;
			$resultat_periode=mysql_fetch_array($user_periode_request);
			$sql_p_etat=$resultat_periode["p_etat"];
			if( ($sql_p_etat=="demande") || ($sql_p_etat=="demande_rtt") )
				$bgcolor=$config_demande_conges_bgcolor;
			elseif($sql_p_etat=="rtt_prise")
				$bgcolor=$config_temps_partiel_bgcolor;
			elseif($sql_p_etat=="formation")
				$bgcolor=$config_absence_autre_bgcolor;
			elseif($sql_p_etat=="mission")
				$bgcolor=$config_absence_autre_bgcolor;
			elseif($sql_p_etat=="autre")
				$bgcolor=$config_absence_autre_bgcolor;
			
			printf("<td bgcolor=\"%s\" class=\"cal-day\">abs</td>", $bgcolor);
		}
		else
			printf("<td bgcolor=\"%s\" class=\"cal-day\"> - </td>", $bgcolor);
		}
}



// affichage de la légende explicative
function affiche_legende()
{
global $session;
global $config_semaine_bgcolor, $config_week_end_bgcolor, $config_conges_bgcolor;
global $config_demande_conges_bgcolor, $config_temps_partiel_bgcolor;
global $config_absence_autre_bgcolor;

//	echo "      <table cellpadding=\"1\" cellspacing=\"1\" border=\"1\">\n" ;
	echo "      <table cellpadding=\"1\" class=\"tablo-cal\">\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_semaine_bgcolor\" class=\"cal-legende\"> - </td>\n" ;
	echo "         <td class=\"cal-legende\"> </td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_week_end_bgcolor\" class=\"cal-legende\"> - </td>\n" ;
	echo "         <td class=\"cal-legende\"> week end</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_conges_bgcolor\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> congès pris ou a prendre</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_demande_conges_bgcolor\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> congès demandé (non encore accordé)</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_temps_partiel_bgcolor\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> abs pour temps partiel ou ARTT</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_temps_partiel_bgcolor\" class=\"cal-legende\">abs/-</td>\n" ;
	echo "         <td class=\"cal-legende\"> abs matin pour temps partiel ou ARTT</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_temps_partiel_bgcolor\" class=\"cal-legende\">-/abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> abs apres midi pour temps partiel ou ARTT</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_absence_autre_bgcolor\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> absence autre (mission, formation, maladie, ...)</td>\n" ;
	echo "      </tr>\n" ;
	echo "      </table>\n" ;
}


?>

</CENTER>
</body>
</html>
