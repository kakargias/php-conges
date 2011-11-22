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
$verif_droits_file="INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG=FALSE;
//$DEBUG=TRUE ;


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
?>
<script language=javascript>
// Fonction permettant d'afficher ou de cacher le tableau correspondant � l'id pass� en param�tre
function afficher(id)
{	
	el = document.getElementById(id);
	el.style.display = "block";
}

function cacher(id)
{	
	el = document.getElementById(id);
	el.style.display = "none";
}
</script>

<?php


	echo "<TITLE> ".$_SESSION['config']['titre_calendrier']." </TITLE>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
	echo "<link href=\"".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";

	echo "</head>\n";

	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$selected      = getpost_variable("selected") ;
	$printable     = getpost_variable("printable", 0) ;
	$year          = getpost_variable("year", date("Y")) ;
	$mois          = getpost_variable("mois", date("n")) ;
	$first_jour    = getpost_variable("first_jour", 1) ;
	

	/*************************************/
	
	//connexion mysql
	$mysql_link = connexion_mysql();
	
	// on initialise le tableau global des jours f�ri�s s'il ne l'est pas d�j� :
	if(!isset($_SESSION["tab_j_feries"]))
	{
		init_tab_jours_feries($mysql_link);
		//print_r($_SESSION["tab_j_feries"]);   // verif DEBUG
	}
	
	// renvoit un tableau de tableau contenant les infos des types de conges et absences
	$tab_type_absence=recup_tableau_tout_types_abs($mysql_link, $DEBUG);
		
	mysql_close($mysql_link);
	
	
	if($printable!=1)  // si version �cran :
		echo "<body text=\"#000000\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=".$_SESSION['config']['bgimage'].">\n";
	else               // si version imprimable :
		echo "<body text=\"#000000\" link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" class=\"edit\">\n";
	
	echo "<CENTER>\n";
	
//	echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
				
	$jour_today=date("j");
	$mois_today=date("m");
	$year_today=date("Y");
	$timestamp_today = mktime (0,0,0,$mois_today,$jour_today,$year_today);
	
	$mois_timestamp = mktime (0,0,0,$mois,1,$year);
	$nom_mois=date_fr("F", $mois_timestamp);
	
	// AFFICHAGE PAGE
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
//	echo "   <H2>".$_SESSION['lang']['calendrier_titre']."</H2>\n";
	echo "   <H3>".$_SESSION['lang']['calendrier_titre']."</H3>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
//	echo "   <h3>$nom_mois  $year</h3>\n";
	echo "   <b>$nom_mois  $year</b><br><br>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"center\">\n";
		
	// AFFICHAGE  TABLEAU (CALENDRIER)
	affichage_calendrier($year, $mois, $first_jour, $timestamp_today, $printable, $selected, $tab_type_absence, $DEBUG);
		
	echo "   </td>\n";
	echo "</tr>\n";
	
	if($printable!=1)   // si version ecran :
	{
		echo "<tr>\n";
		echo "   <td align=\"center\">\n";

		/**********************/
		/* Boutons de defilement */
		affichage_boutons_defilement($first_jour, $mois, $year, $DEBUG) ;

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
		echo "<table cellpadding=\"0\" cellspacing=\"5\" border=\"0\" width=\"90%\">\n";
		echo "<tr>\n";
		echo "   <td width=\"25%\" valign=\"top\" align=\"left\">\n";
		if($printable!=1) // si version ecran :
			echo "      <br><a href=\"$PHP_SELF?session=$session&printable=1&year=$year&mois=$mois&first_jour=$first_jour\" target=\"_blank\" method=\"post\">".$_SESSION['lang']['calendrier_imprimable']."</a>\n";
		else  // si version imprimable
			echo "      <img src=\"img/shim.gif\" width=\"25\" height=\"25\" border=\"0\" vspace=\"0\" hspace=\"0\">\n";
		echo "   </td>\n";
		echo "   <td valign=\"top\" align=\"right\">\n";
		echo "      <h4>l�gende :</h4>\n";
		echo "   </td>\n";
		echo "   <td width=\"150\" valign=\"top\" align=\"left\">\n";
				affiche_legende_type_absence($tab_type_absence, $DEBUG);
		echo "   </td>\n";
		echo "   <td width=\"30%\" valign=\"top\" align=\"left\">\n";
				affiche_legende();
		echo "   </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	echo "   </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	
	if($printable!=1)   // si version ecran :
	{
		echo "<center><hr align=\"center\" size=\"2\" width=\"90%\"></center> \n" ;
	}
	
	/********************/
	/* bouton retour */
	/********************/
	if($printable!=1)   // si version ecran :
	{
		echo "<form action=\"\" method=\"POST\">\n";
		echo "<center><input type=\"button\" value=\"".$_SESSION['lang']['form_close_window']."\" onClick=\"javascript:window.close();\"></center>\n";
		echo "</form>\n";
	}
	
	if($printable==1)   // si version imprimable :
	{
	// appel de la fenetre d'impression directe
?>
<script type="text/javascript" language="javascript1.2">
<!--
// Do print the page
if (typeof(window.print) != 'undefined') {
    window.print();
}
//-->
</script>
<?php
	}
	
	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

	
/*******************************************************************************/
/**********  FONCTIONS  ********************************************************/

	
// retourne le timestamp calcul� du jour precedent
function jour_precedent($jour, $mois, $year) 
{
	return mktime (0,0,0,$mois,$jour -1,$year);
}

// retourne le timestamp calcul� du jour suivant
function jour_suivant($jour, $mois, $year) 
{
	return mktime (0,0,0,$mois,$jour +1,$year);
}

/******************************/
/* Boutons de defilement */
/******************************/
function affichage_boutons_defilement($first_jour, $mois, $year, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

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

		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"90%\" >\n";
		echo "<tr>\n";
		echo "<td align=\"left\">
				<a href=\"$PHP_SELF?session=$session&first_jour=1&mois=$prev_mois&year=$prev_year\" method=\"POST\"> << ".$_SESSION['lang']['divers_mois_precedent_maj_1']." </a>
			</td>\n";
		echo "<td align=\"left\">
				<a href=\"$PHP_SELF?session=$session&first_jour=$prev_first_jour&mois=$prev_first_jour_mois&year=$prev_first_jour_year\" method=\"POST\"> << ".$_SESSION['lang']['calendrier_jour_precedent']." </a>
			</td>\n";
		echo "<td align=\"right\">
				<a href=\"$PHP_SELF?session=$session&first_jour=$next_first_jour&mois=$next_first_jour_mois&year=$next_first_jour_year\" method=\"POST\"> ".$_SESSION['lang']['calendrier_jour_suivant']." >> </a>
			</td>\n";
		echo "<td align=\"right\">
				<a href=\"$PHP_SELF?session=$session&first_jour=1&mois=$next_mois&year=$next_year\" method=\"POST\"> ".$_SESSION['lang']['divers_mois_suivant_maj_1']." >> </a>
			</td>\n";
		echo "</tr></table>\n";
		echo "<br>\n";

}



// AFFICHAGE  TABLEAU (CALENDRIER)
function affichage_calendrier($year, $mois, $first_jour, $timestamp_today, $printable, $selected, $tab_type_absence, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

		//connexion mysql
		$mysql_link = connexion_mysql() ;

		// recup du tableau des types de conges (seulement les conges)
		$tab_type_cong=recup_tableau_types_conges($mysql_link, $DEBUG);
		if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
		{
			$tab_type_cong_excep=recup_tableau_types_conges_exceptionnels($mysql_link, $DEBUG);
		}
	
		// R�cup�ration des users :
		
		//construction de la requete sql pour recup�rer les users � afficher :
		$user_see_all_in_calendrier=get_user_see_all($_SESSION['userlogin'], $mysql_link);
		if($user_see_all_in_calendrier==TRUE) // si le user a "u_see_all" � "Y" dans la table users : affiche tous les users
		{
			$sql = "SELECT DISTINCT u_login, u_nom, u_prenom, u_quotite FROM conges_users ";
			//$sql = $sql." WHERE u_login!='conges' AND u_resp_login = 'conges' ORDER BY u_nom, u_prenom";
			$sql = $sql." WHERE u_login!='conges'  AND u_login!='admin' ORDER BY u_nom, u_prenom";
		}
		else
		{
			$sql = "SELECT DISTINCT u_login, u_nom, u_prenom, u_quotite FROM conges_users ";
			$sql = $sql." WHERE u_login!='conges' AND u_login!='admin' ";

			//si affichage par groupe  (on affiche les membres des groupes du $_SESSION['userlogin'])
			if( ($_SESSION['config']['gestion_groupes']==TRUE) && ($_SESSION['config']['affiche_groupe_in_calendrier']==TRUE) ) 
			{
				$sql = $sql." AND ( u_login = '".$_SESSION['userlogin']."' ";
				//recup de la liste des users des groupes dont le user est membre 
				$list_users=get_list_users_des_groupes_du_user($_SESSION['userlogin'], $mysql_link); 
				if($list_users!="")  //si la liste n'est pas vide ( serait le cas si n'est membre d'aucun groupe)
					$sql = $sql." OR u_login IN ($list_users) ";

				// si $_SESSION['userlogin'] est responsable (on affiche en +, les membres des groupes dont $_SESSION['userlogin'] est resp)
				if(is_resp($_SESSION['userlogin'], $mysql_link)==TRUE)
				{
				//recup de la liste des users des groupes dont le user est responsable
					$list_users_2=get_list_users_des_groupes_du_resp($_SESSION['userlogin'], $mysql_link); 
					if($list_users_2!="")  //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
						$sql = $sql." OR u_login IN ($list_users_2) ";
				}
				$sql = $sql." ) ";
			}
			else
			{
				// si $_SESSION['userlogin'] est responsable
				if(is_resp($_SESSION['userlogin'], $mysql_link)==TRUE)
					$sql = $sql." AND ( u_login = '".$_SESSION['userlogin']."' OR u_resp_login = '".$_SESSION['userlogin']."' ) ";
			}
			
			$sql = $sql." ORDER BY u_nom, u_prenom";
		}
		
		$ReqLog = requete_mysql($sql, $mysql_link, "affichage_calendrier", $DEBUG);

		
		/*************************/
		/**  AFFICHAGE TABLEAU  **/
		
		if($printable!=1)  // si version ecran :
			echo "<table cellpadding=\"1\" class=\"tablo-cal\" width=\"80%\">\n";
		else               // si version imprimable :
			echo "<table cellpadding=\"1\" cellspacing=\"0\" border=\"1\" width=\"80%\">\n";

		/*************************************/
		// affichage premiere ligne (semaines)
		echo "<tr align=\"center\">\n";

		// affichage nom prenom quotit�
		$nb_colonnes=3;
		echo "	<td class=\"cal-user\" rowspan=\"2\">".$_SESSION['lang']['divers_nom_maj']."</td>\n";
		echo "	<td class=\"cal-user\" rowspan=\"2\">".$_SESSION['lang']['divers_prenom_maj']."</td>\n";
		echo "	<td class=\"cal-user\" rowspan=\"2\">%</td>";
		
		if($_SESSION['config']['affiche_soldes_calendrier']==TRUE)
		{
			// affichage des libell� des conges
			foreach($tab_type_cong as $id => $libelle)
			{
					echo "<td class=\"cal-user\" rowspan=\"2\">".$_SESSION['lang']['divers_solde']." $libelle</td>";
					$nb_colonnes=$nb_colonnes+1;
			}
			if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
			{
				foreach($tab_type_cong_excep as $id => $libelle)
				{
					echo "<td class=\"cal-user\" rowspan=\"2\">".$_SESSION['lang']['divers_solde']." $libelle</td>";
					$nb_colonnes=$nb_colonnes+1;
				}
			}
		}
				
		// affichage des semaines
		// ... du premier jour voulu � la fin du mois
		for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois, $j, $year);
			$j_num_semaine=date_fr("W", $j_timestamp);
			// attention date_fr("w", $j_timestamp) renvoit 0 pour dimanche !
			if(date_fr("w", $j_timestamp)==0)
				$j_num_jour_semaine=7;
			else
				$j_num_jour_semaine=date_fr("w", $j_timestamp);  
			
			//DEBUG : echo "<td class=\"cal-day\" >$j_num_jour_semaine / $j_num_semaine</td>";
			if($j==$first_jour)
			{
				$colspan=8-$j_num_jour_semaine;
				echo "<td class=\"cal-day-first\" colspan=\"$colspan\" >".$_SESSION['lang']['divers_semaine']." $j_num_semaine</td>";
			}
			else
			{
				// on affiche que les lundi
				if($j_num_jour_semaine==1)
					echo "<td class=\"cal-day\" colspan=\"7\" >".$_SESSION['lang']['divers_semaine']." $j_num_semaine</td>";
			}

		}
		
		// ... si le premier jour voulu n'etait pas le premier du mois, on va jusqu'� la meme date du mois suivant.
		if($first_jour!=1) 
		{
			for($j=1; $j<$first_jour; $j++) 
			{
				if($mois==12) 
				{
					$mois_select=1;  
					$year_select=$year+1; 
				} 
				else 
				{
					$mois_select=$mois+1 ;
					$year_select=$year; 
				}
				
				$j_timestamp=mktime (0,0,0,$mois_select, $j, $year_select);
				$j_num_jour_semaine=date_fr("w", $j_timestamp);

				echo "<td class=\"cal-day\" title=\"$j_num_jour_semaine / ".$_SESSION['lang']['divers_semaine']." $j_num_semaine\">$j_num_jour_semaine</td>";
			}	
		}
		echo "</tr>\n";
		
		
		/*************************************/
		// affichage 2ieme ligne (dates)
		echo "<tr align=\"center\">\n";

		// on affiche pas car on a fait de "rowspan" � la ligne sup�rieure
		// affichage d'une cellule vide sous les titres
		//echo "	<td class=\"cal-user\" colspan=\"$nb_colonnes\">&nbsp;</td>\n";
		
		// affichage des dates
		// ... du premier jour voulu � la fin du mois
		for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois, $j, $year);
			$j_name=date_fr("D", $j_timestamp);
			$j_date_fr=date_fr("d-m-Y", $j_timestamp);
			$j_num_semaine=date_fr("W", $j_timestamp);
			$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

			// on affiche en gras le jour d'aujourd'hui
			if($j_timestamp==$timestamp_today) 
				$text_titre_date="<b>$j_name <br>$j</b>";
			else
				$text_titre_date="$j_name <br>$j";
				
			// on regarde si c'est la premiere cellule ou non
			if($j==$first_jour)
				$cal_day="cal-day-first";
			else
				$cal_day="cal-day";
				
			// on affiche le titre -date (la date du jour)
			echo "<td class=\"$cal_day $td_second_class\" title=\"$j_date_fr / ".$_SESSION['lang']['divers_semaine']." $j_num_semaine\">$text_titre_date</td>";
		}
		
		// ... si le premier jour voulu n'etait pas le premier du mois, on va jusqu'� la meme date du mois suivant.
		if($first_jour!=1) 
		{
			for($j=1; $j<$first_jour; $j++) 
			{
				if($mois==12) 
				{
					$mois_select=1;  
					$year_select=$year+1; 
				} 
				else 
				{
					$mois_select=$mois+1 ;
					$year_select=$year; 
				}
				
				$j_timestamp=mktime (0,0,0,$mois_select, $j, $year_select);
				$j_name=date_fr("D", $j_timestamp);
				$j_date_fr=date_fr("d-m-Y", $j_timestamp);
				$j_num_semaine=date_fr("W", $j_timestamp);
				$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

				// on affiche en gras le jour d'aujourd'hui
				if($j_timestamp==$timestamp_today)
					echo "<td class=\"cal-day $td_second_class\" title=\"$j_date_fr / ".$_SESSION['lang']['divers_semaine']." $j_num_semaine\"><b>$j_name $j/$mois_select</b></td>";
				else
					echo "<td class=\"cal-day $td_second_class\" title=\"$j_date_fr / ".$_SESSION['lang']['divers_semaine']." $j_num_semaine\">$j_name $j/$mois_select</td>";
			}	
		}
		echo "</tr>\n";
		
		
		/**************************************************/
		/**************************************************/
		/* recup des info de chaque jour pour tous les users et stockage dans 1 tableau de tableaux */
		
		$tab_calendrier=recup_tableau_periodes($mois, $first_jour, $year, $mysql_link, $DEBUG);
		if($DEBUG==TRUE) {	print_r($tab_calendrier); echo "<br>\n"; }
		
		
		
		/**************************************************/
		/* recup des rtt de chaque jour pour tous les users et stockage dans 2 tableaux de tableaux */
		/**************************************************/
		//$tab_rtt_echange  //tableau index� dont la cl� est la date sous forme yyyy-mm-dd
						//il contient pour chaque cl� (chaque jour): un tableau ind�x� ($tab_jour_rtt_echange) (cl�= login)
						// qui contient lui m�me un tableau ($tab_echange) contenant les infos des echanges de rtt pour ce
						// jour et ce login (valeur du matin + valeur de l'apres midi ('Y' si rtt, 'N' sinon) )
		//$tab_rtt_planifiees=array();  //tableau index� dont la cl� est le login_user
					// il contient pour chaque cl� login : un tableau ($tab_user_grille) index� dont la 
					// cl� est la date_fin_grille.
					// qui contient lui meme pour chaque cl� : un tableau ($tab_user_rtt) qui contient enfin 
					// les infos pour le matin et l'apr�s midi ('Y' si rtt, 'N' sinon) sur 2 semaines 
					// ( du sem_imp_lu_am au sem_p_ve_pm ) + la date de d�but et de fin de la grille
					
						
		$tab_rtt_echange= recup_tableau_rtt_echange($mois, $first_jour, $year, $mysql_link );
		//array_walk ($tab_rtt_echange, 'test_print_array');
		$tab_rtt_planifiees= recup_tableau_rtt_planifiees($mois, $first_jour, $year, $mysql_link );
		//array_walk ($tab_rtt_planifiees, 'test_print_array');
		

		/**************************************************/
		// affichage lignes suivantes (users)
		// pour chaque user :
		while ($resultat = mysql_fetch_array($ReqLog)) 
		{
			$sql_login=$resultat["u_login"];
			$sql_nom=$resultat["u_nom"];
			$sql_prenom=$resultat["u_prenom"];
			//$sql_solde_jours=affiche_decimal($resultat["u_solde_jours"]);
			//$sql_solde_rtt=affiche_decimal($resultat["u_solde_rtt"]);
			$sql_quotite=$resultat["u_quotite"];
			
			// recup dans un tableau de tableaux les nb et soldes de conges d'un user (indic� par id de conges)
			$tab_cong_user = recup_tableau_conges_for_user($sql_login, $mysql_link, $DEBUG);

			if($printable==1)
				echo "<tr align=\"center\" class=\"cal-ligne-user-edit\">\n";
			elseif($selected==$sql_login)
				echo "<tr align=\"center\" class=\"cal-ligne-user-selected\">\n";
			else
				echo "<tr align=\"center\" class=\"cal-ligne-user\">\n";
				
			if($printable==1)
				$text_nom="<b>$sql_nom</b>";
			else
				$text_nom="<a href=\"$PHP_SELF?session=$session&selected=$sql_login&year=$year&mois=$mois&first_jour=$first_jour&printable=$printable\" method=\"GET\">$sql_nom</a>";
			
			// affichage nom prenom quotit�
			echo "<td class=\"cal-user\">$text_nom</td><td class=\"cal-user\">$sql_prenom</td><td class=\"cal-user\">$sql_quotite%</td>";

			if($_SESSION['config']['affiche_soldes_calendrier']==TRUE)
			{
				// affichage des divers soldes
				foreach($tab_cong_user as $id => $tab_conges)
				{
					echo "<td class=\"cal-user\">".$tab_conges['solde']."</td>";
				}
			}

			// pour chaque jour : (du premier jour demand� � la fin du mois ...)
			for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
			{
				$j_timestamp=mktime (0,0,0,$mois, $j, $year);
				$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

				$mois_select=$mois;
				$year_select=$year ;
				
				// affichage de la cellule correspondant au jour et au user consid�r� 
				affiche_cellule_jour_user($sql_login, $j_timestamp, $year, $mois_select, $j, $td_second_class, $printable, $tab_calendrier, $tab_rtt_echange, $tab_rtt_planifiees, $tab_type_absence, $mysql_link);
				
			}
			// si le premier jour demand� n'est pas le 1ier du mois , on va jusqu'� la meme date le mois suivant :
			if($first_jour!=1) 
			{
				// pour chaque jour jusqu'a la date voulue : (meme num de jour le mois suivant)
				for($j=1; $j<$first_jour; $j++) 
				{
					$j_timestamp=mktime (0,0,0,$mois+1, $j, $year);
					$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

					if($mois==12) 
					{
						$mois_select=1;
						$year_select=$year+1 ;
					}
					else 
					{
						$mois_select=$mois+1 ;
						$year_select=$year ;
					}
					
					// affichage de la cellule correspondant au jour et au user consid�r� 
					affiche_cellule_jour_user($sql_login, $j_timestamp, $year, $mois_select, $j, $td_second_class, $printable, $tab_calendrier, $tab_rtt_echange, $tab_rtt_planifiees, $tab_type_absence, $mysql_link);
				
				}
			}
			echo "\n</tr>\n";
		}

		echo "</table>\n\n";

		mysql_close($mysql_link);
}


// affichage de la cellule correspondant au jour et au user consid�r� 
function affiche_cellule_jour_user($sql_login, $j_timestamp, $year_select, $mois_select, $j, $second_class, $printable, $tab_calendrier, $tab_rtt_echange, $tab_rtt_planifiees, $tab_type_absence, $mysql_link, $DEBUG=FALSE)
{
	$session=session_id();

	// info bulle
	$j_date_fr=date_fr("d/m/Y", $j_timestamp);
	$j_num_semaine=date_fr("W", $j_timestamp);
	$info_bulle=" title=\"$sql_login - $j_date_fr\" ";
	
	
	if($second_class=="weekend")
	{
		$class="cal-day_".$second_class ;
		if($printable!=1)  // si version �cran :
			echo "<td class=\"$class\" $info_bulle>-</td>";
		else
			echo "<td class=\"$class\">-</td>";
	}
	else
	{
		$date_j=date("Y-m-d", $j_timestamp);
	
		$class_am="travail_am";
		$class_pm="travail_pm";
		$text_am="-";
		$text_pm="-";
	
		$val_matin="";
		$val_aprem="";
		// recup des infos ARTT ou Temps Partiel :
		// la fonction suivante change les valeurs de $val_matin $val_aprem ....
		recup_infos_artt_du_jour_from_tab($sql_login, $j_timestamp, $val_matin, $val_aprem, $tab_rtt_echange, $tab_rtt_planifiees, $mysql_link, $DEBUG=FALSE);
		
		//## AFICHAGE ##
		if($val_matin=="Y") 
		{
			$class_am="rtt_am";
	//		$text_am="a";
		}
		if($val_aprem=="Y")
		{
			$class_pm = "rtt_pm";
	//		$text_pm="a";
		}
	
		$text_bulle_type_abs="";
		
		if( !(($val_matin=="Y")&&($val_aprem=="Y")) ) //si pas journ�e compl�te temps-partiel ou rtt, on regarde les conges)
		{
			// R�cup�ration des conges du user
			if (array_key_exists($date_j, $tab_calendrier))   //verif la cl� du jour exite dans $tab_calendrier
			{
				$tab_day=$tab_calendrier["$date_j"];  // on recup le tableau ($tab_jour) de la date que l'on affiche
				//print_r($tab_day);
	
				$nb_resultat_periode = count($tab_day);  //
				if($nb_resultat_periode>0)      // si on est dans une periode de conges
				{
					for ($i = 0; $i < $nb_resultat_periode; $i++) 
					{
						// on regarde chaque periode l'une apr�s l'autre
						$tab_per=$tab_day[$i];  // on recup le tableau de la periode
						if(in_array($sql_login, $tab_per))   // si la periode correspond au user que l'on est en train de traiter
						{
							//echo "tab_per =<br>\n"; print_r($tab_per); echo "<br>\n";
							
							$sql_p_type=$tab_per["p_type"];
							$sql_p_etat=$tab_per["p_etat"];
							$sql_p_date_deb=$tab_per["p_date_deb"];
							$sql_p_date_fin=$tab_per["p_date_fin"];
							$sql_p_demi_jour_deb=$tab_per["p_demi_jour_deb"];
							$sql_p_demi_jour_fin=$tab_per["p_demi_jour_fin"];
							
							$sql_p_date_deb_fr=substr($sql_p_date_deb,8,2)."/".substr($sql_p_date_deb,5,2)."/".substr($sql_p_date_deb,0,4);
							$sql_p_date_fin_fr=substr($sql_p_date_fin,8,2)."/".substr($sql_p_date_fin,5,2)."/".substr($sql_p_date_fin,0,4);
	
							//si on est le premier jour ET le dernier jour de conges
							if( ($sql_p_date_deb==$date_j) && ($sql_p_date_fin==$date_j) )
							{
								if($sql_p_demi_jour_deb=="am")
								{
									$class_am=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_am";
									$text_am=$tab_type_absence[$sql_p_type]['short_libelle'];
									$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
								}
								if($sql_p_demi_jour_fin=="pm")
								{
									$class_pm=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_pm";
									$text_pm=$tab_type_absence[$sql_p_type]['short_libelle'];
									$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
								}
							}
							elseif($sql_p_date_deb==$date_j) //si on est le premier jour
							{
								if($sql_p_demi_jour_deb=="am")
								{
									$class_am=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_am";
									$text_am=$tab_type_absence[$sql_p_type]['short_libelle'];
									$class_pm=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_pm";
									$text_pm=$tab_type_absence[$sql_p_type]['short_libelle'];
									$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
								}
								else
								{
									$class_pm=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_pm";
									$text_pm=$tab_type_absence[$sql_p_type]['short_libelle'];
									$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
								}
							}
							elseif($sql_p_date_fin==$date_j) //si on est le dernier jour
							{
								if($sql_p_demi_jour_fin=="pm")
								{
									$class_am=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_am";
									$text_am=$tab_type_absence[$sql_p_type]['short_libelle'];
									$class_pm=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_pm";
									$text_pm=$tab_type_absence[$sql_p_type]['short_libelle'];
									$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
								}
								else
								{
									$class_am=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_am";
									$text_am=$tab_type_absence[$sql_p_type]['short_libelle'];
									$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
								}
							}
							else // si on est ni le premier ni le dernier jour
							{
								$class_am=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_am";
								$text_am=$tab_type_absence[$sql_p_type]['short_libelle'];
								$class_pm=get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat)."_pm";
								$text_pm=$tab_type_absence[$sql_p_type]['short_libelle'];
								$text_bulle_type_abs=$tab_type_absence[$sql_p_type]['libelle']."<br>$sql_p_date_deb_fr - $sql_p_date_fin_fr";
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
		
		// on affiche qu'un seule fois le texte si c'est le m�me le matin et l'aprem :
		if($text_am==$text_pm)
			$text_pm="";
		elseif(($text_am=="-") &&($text_pm!="") ) //on a un "-" le matin et qq chose l'aprem :on affiche que le texte de l'aprem
			$text_am="";
		elseif(($text_am!="") && ($text_pm=="-"))  //on a un qq chose le matin et un "-" l'aprem :on affiche que le texte du matin
			$text_pm="";
		
		
		$class="cal-day_".$second_class."_".$class_am."_".$class_pm ;	

		if($printable!=1)  // si version �cran :
		{
			if( ($text_am=="-") && ($text_pm=="") )
			{
				echo "<td class=\"$class\"  $info_bulle>";
				echo "	$text_am $text_pm ";
			}
			else
			{
				//echo "<td class=\"$class\"  onmousedown=\"javascript:afficher('$sql_login-$j_timestamp');\" onmouseup=\"javascript:cacher('$sql_login-$j_timestamp');\">";
				echo "<td class=\"$class\"  onmouseover=\"javascript:afficher('$sql_login-$j_timestamp');\" onmouseout=\"javascript:cacher('$sql_login-$j_timestamp');\">";
				echo "	$text_am $text_pm ";
				
				// affiche l'info-bulle (affich�e grace au javascript)
				//$texte_info_bulle=" $j_date_fr / ".$_SESSION['lang']['divers_semaine']." $j_num_semaine <br>$text_bulle_type_abs<br>periode";
				$texte_info_bulle=" $j_date_fr <br>$text_bulle_type_abs";
				echo "	<div class=\"cal-bulles\" align=\"center\" id='$sql_login-$j_timestamp' name='$sql_login-$j_timestamp' >
						$sql_login<br>
						<hr align=\"center\" size=\"1\" width=\"100\" color=\"#6699CC\" />
						$texte_info_bulle
						<hr align=\"center\" size=\"1\" width=\"100\" color=\"#6699CC\" />
						</div> ";
			}
		}
		else
		{
			echo "<td class=\"$class\" >";
			echo "	$text_am $text_pm ";
		}
		echo "</td>";
	}

}



// affichage de la l�gende des couleurs
function affiche_legende($DEBUG=FALSE)
{
	$session=session_id();

//	echo "      <table cellpadding=\"1\" cellspacing=\"1\" border=\"1\">\n" ;
	echo "      <table cellpadding=\"1\" class=\"tablo-cal\">\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"".$_SESSION['config']['semaine_bgcolor']."\" class=\"cal-legende\"> - </td>\n" ;
	echo "         <td class=\"cal-legende\"> </td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"".$_SESSION['config']['week_end_bgcolor']."\" class=\"cal-legende\"> - </td>\n" ;
	echo "         <td class=\"cal-legende\"> ".$_SESSION['lang']['calendrier_legende_we']."</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"".$_SESSION['config']['conges_bgcolor']."\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> ".$_SESSION['lang']['calendrier_legende_conges']."</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"".$_SESSION['config']['demande_conges_bgcolor']."\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> ".$_SESSION['lang']['calendrier_legende_demande']."</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
//	echo "         <td bgcolor=\"".$_SESSION['config']['temps_partiel_bgcolor']."\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td bgcolor=\"".$_SESSION['config']['temps_partiel_bgcolor']."\" class=\"cal-legende\"> - </td>\n" ;
	echo "         <td class=\"cal-legende\"> ".$_SESSION['lang']['calendrier_legende_part_time']."</td>\n" ;
	echo "      </tr>\n" ;
	echo "      <tr align=\"center\">\n" ;
	echo "         <td bgcolor=\"".$_SESSION['config']['absence_autre_bgcolor']."\" class=\"cal-legende\">abs</td>\n" ;
	echo "         <td class=\"cal-legende\"> ".$_SESSION['lang']['calendrier_legende_abs']."</td>\n" ;
	echo "      </tr>\n" ;
	echo "      </table>\n" ;
}

// affichage de la l�gende explicative des abr�viations
function affiche_legende_type_absence($tab_type_absence, $DEBUG=FALSE)
{
	$session=session_id();

//	echo "      <table cellpadding=\"1\" cellspacing=\"1\" border=\"1\">\n" ;
	echo "      <table cellpadding=\"1\" class=\"tablo-cal\">\n" ;
	foreach($tab_type_absence as $id_abs => $tab)
	{
		echo "      <tr align=\"center\">\n" ;
		echo "         <td class=\"cal-legende\"> ".$tab['short_libelle']." : </td>\n" ;
		echo "         <td class=\"cal-legende\"> ".$tab['libelle']." </td>\n" ;
		echo "      </tr>\n" ;
	}
	echo "      </table>\n" ;
}

// renvoit conges , demande ou autre ....
function get_class_titre($sql_p_type, $tab_type_absence, $sql_p_etat, $DEBUG=FALSE)
{
//	if( ($sql_p_type=="formation") || ($sql_p_type=="mission") || ($sql_p_type=="autre") )
	if ($tab_type_absence[$sql_p_type]['type']=="absences")
		return "autre";
	elseif($sql_p_etat=='ok')
		return "conges";
	elseif( ($sql_p_etat=="demande") || ($sql_p_etat=="valid") )
		return "demande";
}



function test_print_array ($item, $key) 
{
	echo "$key. $item<br />\n";
}



/**************************************************/
/* recup des info de chaque jour pour tous les users et stockage dans 1 tableau de tableaux */
/**************************************************/
function recup_tableau_periodes($mois, $first_jour, $year, $mysql_link, $DEBUG=FALSE)
{
	$tab_calendrier=array();  //tableau index� dont la cl� est la date sous forme yyyy-mm-dd
						//il contient pour chaque cl� : un tableau ($tab_jour) qui contient lui m�me des 
						// tableaux index�s contenant les infos des periode de conges dont ce jour fait partie 
						// ($tab_periode)
						
	// pour chaque jour : (du premier jour demand� � la fin du mois ...)
	for($j=$first_jour; checkdate($mois, $j, $year); $j++) 
	{
		$j_timestamp=mktime (0,0,0,$mois, $j, $year);

		$date_j=date("Y-m-d", $j_timestamp);
		$tab_jour=array();
		
		//$user_periode_sql = "SELECT  p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_type, p_etat FROM conges_periode WHERE ( p_etat='ok' OR  p_etat='demande' OR  p_type='formation' OR  p_type='mission' OR  p_type='autre' ) AND (p_date_deb<='$date_j' AND p_date_fin>='$date_j') ORDER BY p_date_deb ";
		$user_periode_sql = "SELECT  p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_type, p_etat 
						FROM conges_periode 
						WHERE ( p_etat='ok' OR  p_etat='demande' OR  p_etat='valid') AND (p_date_deb<='$date_j' AND p_date_fin>='$date_j') 
						ORDER BY p_date_deb ";

		//echo "user_periode_sql = $user_periode_sql<br>\n";
		$user_periode_request = requete_mysql($user_periode_sql, $mysql_link, "recup_tableau_periodes", $DEBUG);
		
		$nb_resultat_periode = mysql_num_rows($user_periode_request);
		while($resultat_periode=mysql_fetch_array($user_periode_request))
		{
			$tab_periode=array();
			$tab_periode["p_login"]=$resultat_periode["p_login"];
			$tab_periode["p_type"]=$resultat_periode["p_type"];
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
	// si le premier jour demand� n'est pas le 1ier du mois , on va jusqu'� la meme date le mois suivant :
	if($first_jour!=1) 
	{
		// pour chaque jour jusqu'a la date voulue : (meme num de jour le mois suivant)
		for($j=1; $j<$first_jour; $j++) 
		{
			$j_timestamp=mktime (0,0,0,$mois+1, $j, $year);
		
			$date_j=date("Y-m-d", $j_timestamp);
			$tab_jour=array();

			$user_periode_sql = "SELECT  p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_type, p_etat FROM conges_periode WHERE ( p_etat='ok' OR  p_etat='demande' OR  p_type='formation' OR  p_type='mission' OR  p_type='autre' ) AND (p_date_deb<='$date_j' AND p_date_fin>='$date_j') ";
			//echo "user_periode_sql = $user_periode_sql<br>\n";
			$user_periode_request = requete_mysql($user_periode_sql, $mysql_link, "recup_tableau_periodes", $DEBUG);
			
			$nb_resultat_periode = mysql_num_rows($user_periode_request);
			while($resultat_periode=mysql_fetch_array($user_periode_request))
			{
				$tab_periode=array();
				$tab_periode["p_login"]=$resultat_periode["p_login"];
				$tab_periode["p_type"]=$resultat_periode["p_type"];
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


?>

