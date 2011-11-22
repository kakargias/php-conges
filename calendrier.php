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
if($config_verif_droits==TRUE){ include("INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php
	/*** initialisation des variables ***/
	$printable=0;
	/************************************/

	echo "<TITLE> $config_titre_calendrier </TITLE>\n";
	echo "<link href=\"$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";

	echo "</head>\n";

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$HTTP_REFERER=$_SERVER['HTTP_REFERER'] ;
	// GET
	if(isset($_GET['selected'])) { $printable=$_GET['selected']; }
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
//	$nom_mois=date("F", $mois_timestamp);
	$nom_mois=date_fr("F", $mois_timestamp);
	
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
global $page_initiale, $selected ;

global $config_rtt_comme_conges, $config_gestion_groupes, $config_affiche_groupe_in_calendrier ;

		//connexion mysql
		$link = connexion_mysql() ;

		// Récupération des users :
		
		//construction de la requete sql
		$sql = "SELECT DISTINCT u_login, u_nom, u_prenom, u_solde_jours, u_solde_rtt, u_quotite FROM conges_users ";
		//si affichage par groupe
		if( ($config_gestion_groupes==TRUE) && ($config_affiche_groupe_in_calendrier==TRUE) ) 
		{
			// recup du groupe de l'utilisateur :
			$tab_group=array();
			$est_dans_groupe=get_groupe_from_login($session_username, $tab_group, $link);
			if($est_dans_groupe==TRUE)
			{
				$sql = $sql.", conges_groupe_users WHERE ";
				$i=0;
				foreach( $tab_group as $groupname)
				{
					if($i==0)
						$sql = $sql." (gu_groupename='$groupname' AND gu_login=u_login ) ";
					else
						$sql = $sql." OR (gu_groupename='$groupname' AND gu_login=u_login ) ";
					$i++;
				}
				$sql = $sql." AND ";
			}
			else
				$sql = $sql." WHERE ";
		}
		else
			$sql = $sql." WHERE ";
		
		$sql = $sql." u_login!='conges' ORDER BY u_nom, u_prenom";
		
		$ReqLog = mysql_query($sql, $link) or die("ERREUR : calendrier.php : ".mysql_error());

		
		
		// AFFICHAGE TABLEAU
		if($printable!=1)  // si version ecran :
			printf("<table cellpadding=\"1\" class=\"tablo-cal\" width=\"80%%\">\n");
			//printf("<table cellpadding=\"1\" cellspacing=\"1\" border=\"1\" width=\"80%%\">\n");
		else               // si version imprimable :
			printf("<table cellpadding=\"1\" cellspacing=\"0\" border=\"1\" width=\"80%%\">\n");

		// affichage premiere ligne (dates)
		echo "<tr align=\"center\">\n";
		echo "	<td class=\"cal-user\">NOM</td><td class=\"cal-user\">PRENOM</td><td class=\"cal-user\">%</td><td class=\"cal-user\">solde congés</td>";
		if($config_rtt_comme_conges==TRUE)
			echo "<td class=\"cal-user\">solde rtt</td>";
			
		for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois, $j, $year);
			$j_name=date_fr("D", $j_timestamp);
			$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

			// on affiche en gras le jour d'aujourd'hui
			if($j==$jour_today) 
//				printf("<td class=\"cal-day %s\"><b>%s %d/%s</b></td>", $td_second_class, $j_name, $j, $mois);
				printf("<td class=\"cal-day %s\"><b>%s <br>%d</b></td>", $td_second_class, $j_name, $j);
			else
//				printf("<td class=\"cal-day %s\">%s %d/%s</td>", $td_second_class, $j_name, $j, $mois);
				printf("<td class=\"cal-day %s\">%s <br>%d</td>", $td_second_class, $j_name, $j);
		}
		if($first_jour!=1) {
			for($j=1; $j<$first_jour; $j++) {
				if($mois==12) $mois_select=1;  else $mois_select=$mois+1 ;
				
				$j_timestamp=mktime (0,0,0,$mois_select, $j, $year);
				$j_name=date_fr("D", $j_timestamp);
				$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

				// on affiche en gras le jour d'aujourd'hui
				if($j==$jour_today) 
					printf("<td class=\"cal-day %s\"><b>%s %d/%s</b></td>", $td_second_class, $j_name, $j, $mois_select);
				else
					printf("<td class=\"cal-day %s\">%s %d/%s</td>", $td_second_class, $j_name, $j, $mois_select);
			}	
		}
		printf("</tr>\n");
		
		
		/**************************************************/
		/* recup des info de chaque jour pour tous les users et stockage dans 1 tableau de tableaux */
		
		$tab_calendrier=recup_tableau_periodes($mois, $first_jour, $year, $link);
		//array_walk ($tab_calendrier, 'test_print_array');
		

		
		/**************************************************/
		/* recup des rtt de chaque jour pour tous les users et stockage dans 2 tableaux de tableaux */
		/**************************************************/
		//$tab_rtt_echange  //tableau indexé dont la clé est la date sous forme yyyy-mm-dd
						//il contient pour chaque clé (chaque jour): un tableau indéxé ($tab_jour_rtt_echange) (clé= login)
						// qui contient lui même un tableau ($tab_echange) contenant les infos des echanges de rtt pour ce
						// jour et ce login (valeur du matin + valeur de l'apres midi ('Y' si rtt, 'N' sinon) )
		//$tab_rtt_planifiees=array();  //tableau indexé dont la clé est le login_user
					// il contient pour chaque clé login : un tableau ($tab_user_grille) indexé dont la 
					// clé est la date_fin_grille.
					// qui contient lui meme pour chaque clé : un tableau ($tab_user_rtt) qui contient enfin 
					// les infos pour le matin et l'après midi ('Y' si rtt, 'N' sinon) sur 2 semaines 
					// ( du sem_imp_lu_am au sem_p_ve_pm ) + la date de début et de fin de la grille
					
						
		$tab_rtt_echange= recup_tableau_rtt_echange($mois, $first_jour, $year, $link );
		//array_walk ($tab_rtt_echange, 'test_print_array');
		$tab_rtt_planifiees= recup_tableau_rtt_planifiees($mois, $first_jour, $year, $link );
		//array_walk ($tab_rtt_planifiees, 'test_print_array');
		

		
		/**************************************************/
		// affichage lignes suivantes (users)
		// pour chaque user :
		while ($resultat = mysql_fetch_array($ReqLog)) {
			$sql_login=$resultat["u_login"];
			$sql_nom=$resultat["u_nom"];
			$sql_prenom=$resultat["u_prenom"];
			$sql_solde_jours=affiche_decimal($resultat["u_solde_jours"]);
			$sql_solde_rtt=affiche_decimal($resultat["u_solde_rtt"]);
			$sql_quotite=$resultat["u_quotite"];

			if($selected==$sql_login)
				printf("<tr align=\"center\" class=\"cal-ligne-user-selected\">\n");
			else
				printf("<tr align=\"center\" class=\"cal-ligne-user\">\n");
				
			$text_nom="<a href=\"$PHP_SELF?session=$session&selected=$sql_login&year=$year&mois=$mois&first_jour=$first_jour&printable=$printable&page_initiale=$page_initiale\" method=\"GET\">$sql_nom</a>";
			
			//printf("<td>%s</td><td>%s</td><td>%d%%</td><td>%s</td>", $sql_nom, $sql_prenom, $sql_quotite, $sql_solde_jours);
			echo "<td class=\"cal-user\">$text_nom</td><td class=\"cal-user\">$sql_prenom</td><td class=\"cal-user\">$sql_quotite%</td><td class=\"cal-user\">$sql_solde_jours</td>";
			if($config_rtt_comme_conges==TRUE)
				echo "<td class=\"cal-user\">$sql_solde_rtt</td>";

			// pour chaque jour : (du premier jour demandé à la fin du mois ...)
			for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
			{
				$j_timestamp=mktime (0,0,0,$mois, $j, $year);
				$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

				$mois_select=$mois;
				$year_select=$year ;
				
				// affichage de la cellule correspondant au jour et au user considéré 
				affiche_cellule_jour_user($sql_login, $j_timestamp, $year, $mois_select, $j, $td_second_class, $printable, $tab_calendrier, $tab_rtt_echange, $tab_rtt_planifiees);
				
			}
			// si le premier jour demandé n'est pas le 1ier du mois , on va jusqu'à la meme date le mois suivant :
			if($first_jour!=1) {
				// pour chaque jour jusqu'a la date voulue : (meme num de jour le mois suivant)
				for($j=1; $j<$first_jour; $j++) 
				{
					$j_timestamp=mktime (0,0,0,$mois+1, $j, $year);
					$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

					if($mois==12) {
						$mois_select=1;
						$year_select=$year+1 ;
					}
					else {
						$mois_select=$mois+1 ;
						$year_select=$year ;
					}
					
					// affichage de la cellule correspondant au jour et au user considéré 
					affiche_cellule_jour_user($sql_login, $j_timestamp, $year, $mois_select, $j, $td_second_class, $printable, $tab_calendrier, $tab_rtt_echange, $tab_rtt_planifiees);
				
				}
			}
			printf("\n</tr>\n");
		}

		printf("</table>\n\n");

		mysql_close($link);
}


// affichage de la cellule correspondant au jour et au user considéré 
function affiche_cellule_jour_user($sql_login, $j_timestamp, $year_select, $mois_select, $j, $second_class, $printable, $tab_calendrier, $tab_rtt_echange, $tab_rtt_planifiees)
{
global $session, $session_username;
global $link;

	$date_j=date("Y-m-d", $j_timestamp);

	$class_am="travail_am";
	$class_pm="travail_pm";
	$text_am="-";
	$text_pm="-";

	// recup des infos ARTT ou Temps Partiel :
	//recup_infos_artt_du_jour($sql_login, $j_timestamp, $val_matin, $val_aprem);
	recup_infos_artt_du_jour_from_tab($sql_login, $j_timestamp, $val_matin, $val_aprem, $tab_rtt_echange, $tab_rtt_planifiees);
	
	//## AFICHAGE ##
	if($val_matin=="Y") 
	{
		$class_am="rtt_am";
		$text_am="a";
	}
	if($val_aprem=="Y")
	{
		$class_pm = "rtt_pm";
		$text_pm="a";
	}

	if( !(($val_matin=="Y")&&($val_aprem=="Y")) ) //si pas journée complète temps-partiel ou rtt, on regarde les conges)
	{
		// Récupération des conges du user
		if (array_key_exists($date_j, $tab_calendrier))   //verif la clé du jour exite dans $tab_calendrier
		{
			$tab_day=$tab_calendrier["$date_j"];  // on recup le tableau ($tab_jour) de la date que l'on affiche
			//array_walk ($tab_day, 'test_print_array');

			$nb_resultat_periode = count($tab_day);  //
			if($nb_resultat_periode>0) {    // si on est dans une periode de conges
				for ($i = 0; $i < $nb_resultat_periode; $i++) 
				{
					// on regarde chaque periode l'une après l'autre
					$tab_per=$tab_day[$i];  // on recup le tableau de la periode
					if(in_array($sql_login, $tab_per))   // si la periode corespond au user que l'on est en train de traiter
					{
						//array_walk ($tab_per, 'test_print_array');
						
						$sql_p_etat=$tab_per["p_etat"];
						$sql_p_date_deb=$tab_per["p_date_deb"];
						$sql_p_date_fin=$tab_per["p_date_fin"];
						$sql_p_demi_jour_deb=$tab_per["p_demi_jour_deb"];
						$sql_p_demi_jour_fin=$tab_per["p_demi_jour_fin"];

						//si on est le premier jour ET le dernier jour de conges
						if( ($sql_p_date_deb==$date_j) && ($sql_p_date_fin==$date_j) )
						{
							if($sql_p_demi_jour_deb=="am")
							{
								$class_am=get_class_titre($sql_p_etat)."_am";
								$text_am="a";
							}
							if($sql_p_demi_jour_fin=="pm")
							{
								$class_pm=get_class_titre($sql_p_etat)."_pm";
								$text_pm="a";
							}
						}
						else
						{
							//si on est le premier jour
							if($sql_p_date_deb==$date_j)
							{
								if($sql_p_demi_jour_deb=="am")
								{
									$class_am=get_class_titre($sql_p_etat)."_am";
									$text_am="a";
									$class_pm=get_class_titre($sql_p_etat)."_pm";
									$text_pm="a";
								}
								else
								{
									$class_pm=get_class_titre($sql_p_etat)."_pm";
									$text_pm="a";
								}
							}

							//si on est le dernier jour
							if($sql_p_date_fin==$date_j)
							{
								if($sql_p_demi_jour_fin=="pm")
								{
									$class_am=get_class_titre($sql_p_etat)."_am";
									$text_am="a";
									$class_pm=get_class_titre($sql_p_etat)."_pm";
									$text_pm="a";
								}
								else
								{
									$class_am=get_class_titre($sql_p_etat)."_am";
									$text_am="a";
								}
							}

							// si on est ni le premier ni le dernier jour
							if( ($sql_p_date_deb!=$date_j) && ($sql_p_date_fin!=$date_j) )
							{
								$class_am=get_class_titre($sql_p_etat)."_am";
								$text_am="a";
								$class_pm=get_class_titre($sql_p_etat)."_pm";
								$text_pm="a";
							}
						}
					}
				}
			}
		}
	}
	
	if(($text_am=="a")&&($text_pm=="a"))
	{
		$text_am="abs";
		$text_pm="";
	}
	// on affiche qu'un seule fois le text si c'est le même le matin et l'aprem :
	if($text_am==$text_pm)
		$text_pm="";
		
	if($second_class=="weekend") 
		printf("<td class=\"cal-day_%s\">%s %s</td>", $second_class, $text_am, $text_pm);
	else
		printf("<td class=\"cal-day_%s_%s_%s\">%s %s</td>", $second_class, $class_am, $class_pm, $text_am, $text_pm);

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
	echo "         <td class=\"cal-legende\"> abs pour temps partiel ou RTT</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"$config_absence_autre_bgcolor\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> absence autre (mission, formation, maladie, ...)</td>\n" ;
	echo "      </tr>\n" ;
	echo "      </table>\n" ;
}


function get_class_titre($sql_p_etat)
{
	if(($sql_p_etat=='pris') || ($sql_p_etat=="rtt_prise"))
		return "conges";
	elseif(($sql_p_etat=="demande") || ($sql_p_etat=="demande_rtt"))
		return "demande";
	elseif( ($sql_p_etat=="formation") || ($sql_p_etat=="mission") || ($sql_p_etat=="autre") )
		return "autre";
}



function test_print_array ($item, $key) {
	echo "$key. $item<br />\n";
}



/**************************************************/
/* recup des info de chaque jour pour tous les users et stockage dans 1 tableau de tableaux */
/**************************************************/
function recup_tableau_periodes($mois, $first_jour, $year, $mysql_link)
{
	$tab_calendrier=array();  //tableau indexé dont la clé est la date sous forme yyyy-mm-dd
						//il contient pour chaque clé : un tableau ($tab_jour) qui contient lui même des 
						// tableaux indexés contenant les infos des periode de conges dont ce jour fait partie 
						// ($tab_periode)
						
	// pour chaque jour : (du premier jour demandé à la fin du mois ...)
	for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
	{
		$j_timestamp=mktime (0,0,0,$mois, $j, $year);

		$date_j=date("Y-m-d", $j_timestamp);
		$tab_jour=array();
		
		$user_periode_sql = "SELECT  p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat FROM conges_periode WHERE ( p_etat='pris' OR  p_etat='rtt_prise' OR  p_etat='demande' OR  p_etat='demande_rtt' OR  p_etat='formation' OR  p_etat='mission' OR  p_etat='autre' ) AND (p_date_deb<='".$date_j."' AND p_date_fin>='".$date_j."') ORDER BY p_date_deb ";
		//echo "user_periode_sql = $user_periode_sql<br>\n";
		$user_periode_request = mysql_query($user_periode_sql, $mysql_link);
		$nb_resultat_periode = mysql_num_rows($user_periode_request);
		while($resultat_periode=mysql_fetch_array($user_periode_request))
		{
			$tab_periode=array();
			$tab_periode["p_login"]=$resultat_periode["p_login"];
			$tab_periode["p_etat"]=$resultat_periode["p_etat"];
			$tab_periode["p_date_deb"]=$resultat_periode["p_date_deb"];
			$tab_periode["p_date_fin"]=$resultat_periode["p_date_fin"];
			$tab_periode["p_demi_jour_deb"]=$resultat_periode["p_demi_jour_deb"];
			$tab_periode["p_demi_jour_fin"]=$resultat_periode["p_demi_jour_fin"];
			$tab_jour[]=$tab_periode;
			
			//array_walk ($tab_periode, 'test_print_array');

		}
		//array_walk ($tab_jour, 'test_print_array');
		$tab_calendrier[$date_j]=$tab_jour;
	}
	// si le premier jour demandé n'est pas le 1ier du mois , on va jusqu'à la meme date le mois suivant :
	if($first_jour!=1) {
		// pour chaque jour jusqu'a la date voulue : (meme num de jour le mois suivant)
		for($j=1; $j<$first_jour; $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois+1, $j, $year);
		
			$date_j=date("Y-m-d", $j_timestamp);
			$tab_jour=array();

			$user_periode_sql = "SELECT  p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat FROM conges_periode WHERE ( p_etat='pris' OR  p_etat='rtt_prise' OR  p_etat='demande' OR  p_etat='demande_rtt' OR  p_etat='formation' OR  p_etat='mission' OR  p_etat='autre' ) AND (p_date_deb<='".$date_j."' AND p_date_fin>='".$date_j."') ";
			//echo "user_periode_sql = $user_periode_sql<br>\n";
			$user_periode_request = mysql_query($user_periode_sql, $mysql_link);
			$nb_resultat_periode = mysql_num_rows($user_periode_request);
			while($resultat_periode=mysql_fetch_array($user_periode_request))
			{
				$tab_periode=array();
				$tab_periode["p_login"]=$resultat_periode["p_login"];
				$tab_periode["p_etat"]=$resultat_periode["p_etat"];
				$tab_periode["p_date_deb"]=$resultat_periode["p_date_deb"];
				$tab_periode["p_date_fin"]=$resultat_periode["p_date_fin"];
				$tab_periode["p_demi_jour_deb"]=$resultat_periode["p_demi_jour_deb"];
				$tab_periode["p_demi_jour_fin"]=$resultat_periode["p_demi_jour_fin"];
				$tab_jour[]=$tab_periode;

				//array_walk ($tab_periode, 'test_print_array');

			}
			//array_walk ($tab_jour, 'test_print_array');
			$tab_calendrier[$date_j]=$tab_jour;
		}
	}
	
	return $tab_calendrier;
}

// rempli le tableau des groupes dont l'utilisateur fait partie
//  (attention : le $groupe_tab est passé par référence car on le modifie)
// retourne TRUE si le user appartient à un/des groupe/s , renvoit FALSE sinon.
function get_groupe_from_login($login, &$groupe_tab, $link)
{
	$sql = "SELECT gu_groupename FROM conges_groupe_users WHERE gu_login='$login' ORDER BY gu_groupename ";
		
	$ReqLog = mysql_query($sql, $link) or die("ERREUR : get_groupe_from_login() : ".mysql_error());
	if(mysql_num_rows($ReqLog)!=0)
	{
		while($result=mysql_fetch_array($ReqLog))
		{
			$groupe_tab[]=$result["gu_groupename"];
		}
		return TRUE ;
	}
	else
	{
		return FALSE ;
	}
}

?>

</CENTER>
</body>
</html>
