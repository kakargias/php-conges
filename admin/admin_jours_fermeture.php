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

define('_PHP_CONGES', 1);
define('ROOT_PATH', '../');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

if (file_exists(CONFIG_PATH .'config_ldap.php'))
	include CONFIG_PATH .'config_ldap.php';


include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';
include INCLUDE_PATH .'session.php';
include ROOT_PATH .'fonctions_calcul.php';


$DEBUG=FALSE;
//$DEBUG=TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_admin", $DEBUG);


	/*** initialisation des variables ***/
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$choix_action 				= getpost_variable('choix_action');
	$year						= getpost_variable('year', 0);
	$groupe_id					= getpost_variable('groupe_id');
	$id_type_conges				= getpost_variable('id_type_conges');
//	$new_date_debut				= getpost_variable('new_date_debut', date("d/m/Y")); // valeur par dédaut = aujourd'hui
//	$new_date_fin  				= getpost_variable('new_date_fin', date("d/m/Y"));   // valeur par dédaut = aujourd'hui
	$new_date_debut				= getpost_variable('new_date_debut'); // valeur par dédaut = aujourd'hui
	$new_date_fin  				= getpost_variable('new_date_fin');   // valeur par dédaut = aujourd'hui
	$fermeture_id  				= getpost_variable('fermeture_id', 0);
	$fermeture_date_debut		= getpost_variable('fermeture_date_debut');
	$fermeture_date_fin			= getpost_variable('fermeture_date_fin');
	$code_erreur				= getpost_variable('code_erreur', 0);
	/*************************************/
	if($new_date_debut=="")
	{
		if($year==0)
			$new_date_debut=date("d/m/Y") ;
		else
			$new_date_debut=date("d/m/Y", mktime(0,0,0, date("m"), date("d"), $year) ) ;
	}
	if($new_date_fin=="")
	{
		if($year==0)
			$new_date_fin=date("d/m/Y") ;
		else
			$new_date_fin=date("d/m/Y", mktime(0,0,0, date("m"), date("d"), $year) ) ;
	}

	if( $DEBUG ) { echo "choix_action = $choix_action // year = $year // groupe_id = $groupe_id<br>\n"; }
	if( $DEBUG ) { echo "new_date_debut = $new_date_debut // new_date_fin = $new_date_fin<br>\n"; }
	if( $DEBUG ) { echo "fermeture_id = $fermeture_id // fermeture_date_debut = $fermeture_date_debut // fermeture_date_fin = $fermeture_date_fin<br>\n"; }


	//connexion mysql

	// initialisation de l'action à effectuer
	if($choix_action=="")
	{
		// si pas de gestion par groupe
		if($_SESSION['config']['gestion_groupes']==FALSE)
			 $choix_action="saisie_dates";
		// si gestion par groupe et fermeture_par_groupe
		elseif(($_SESSION['config']['fermeture_par_groupe']) && ($groupe_id=="") )
			 $choix_action="saisie_groupe";
		else
			 $choix_action="saisie_dates";
	}

	// init de l'annee
	if($year ==0)
		$year= date("Y");


	/***********************************/
	/*  VERIF DES DATES RECUES   */
	//
	$tab_date_debut=explode("/",$new_date_debut);   // date au format d/m/Y
	$timestamp_date_debut = mktime(0,0,0, $tab_date_debut[1], $tab_date_debut[0], $tab_date_debut[2]) ;
	$date_debut_yyyy_mm_dd = $tab_date_debut[2]."-".$tab_date_debut[1]."-".$tab_date_debut[0] ;
	$tab_date_fin=explode("/",$new_date_fin);   // date au format d/m/Y
	$timestamp_date_fin = mktime(0,0,0, $tab_date_fin[1], $tab_date_fin[0], $tab_date_fin[2]) ;
	$date_fin_yyyy_mm_dd = $tab_date_fin[2]."-".$tab_date_fin[1]."-".$tab_date_fin[0] ;
	$timestamp_today = mktime(0,0,0, date("m"), date("d"), date("Y")) ;

	if( $DEBUG ) { echo "timestamp_date_debut = $timestamp_date_debut // timestamp_date_fin = $timestamp_date_fin // timestamp_today = $timestamp_today<br>\n"; }

	// on verifie si les jours fériés de l'annee de la periode saisie sont enregistrés : sinon BUG au calcul des soldes des users !
	if( (verif_jours_feries_saisis($date_debut_yyyy_mm_dd, $DEBUG)==FALSE)
	    && (verif_jours_feries_saisis($date_fin_yyyy_mm_dd, $DEBUG)==FALSE) )
		$code_erreur=1 ;  // code erreur : jour feriés non saisis

	if($choix_action=="commit_new_fermeture")
	{
		// on verifie que $new_date_debut est anterieure a $new_date_fin
		if($timestamp_date_debut > $timestamp_date_fin)
			$code_erreur=2 ;  // code erreur : $new_date_debut est posterieure a $new_date_fin
		// on verifie que ce ne sont pas des dates passées
		elseif($timestamp_date_debut < $timestamp_today)
			$code_erreur=3 ;  // code erreur : saisie de date passée

		// on ne verifie QUE si date_debut ou date_fin sont !=  d'aujourd'hui
		// (car aujourd'hui est la valeur par dédaut des dates, et on ne peut saisir aujourd'hui puisque c'est fermé !)
		elseif( ($timestamp_date_debut==$timestamp_today) || ($timestamp_date_fin==$timestamp_today) )
		{
			$code_erreur=4 ;  // code erreur : saisie de aujourd'hui
		}
		else
		// on verifie si la periode saisie ne chevauche pas une :
		// fabrication et initialisation du tableau des demi-jours de la date_debut à la date_fin
		{
			$tab_periode_calcul = make_tab_demi_jours_periode($date_debut_yyyy_mm_dd, $date_fin_yyyy_mm_dd, "am", "pm", $DEBUG);
			if(verif_periode_chevauche_periode_groupe($date_debut_yyyy_mm_dd, $date_fin_yyyy_mm_dd, '', $tab_periode_calcul, $groupe_id, $DEBUG) )
				$code_erreur=5 ;  // code erreur : fermeture chevauche une periode deja saisie
		}
	}
	if($code_erreur!=0)
		 $choix_action="saisie_dates";   // comme cela, on renvoit sur la saisie de dates



	/***********************************/
	// AFFICHAGE DE LA PAGE
	
	header_popup();	
	
	echo "<h1>". _('admin_jours_fermeture_titre') ."  $year</h1>\n";


	if($choix_action=="saisie_groupe")
         	saisie_groupe_fermeture($DEBUG);
	elseif($choix_action=="saisie_dates")
	{
			//include ROOT_PATH .'fonctions_javascript_calendrier.php';
			affiche_javascript_et_css_des_calendriers();
			if($groupe_id=="")     // choix du groupe n'a pas été fait ($_SESSION['config']['fermeture_par_groupe']==FALSE)
				$groupe_id=0;
	        saisie_dates_fermeture($year, $groupe_id, $new_date_debut, $new_date_fin, $code_erreur, $DEBUG);
	}
	elseif($choix_action=="commit_new_fermeture")
	        commit_new_fermeture($new_date_debut, $new_date_fin, $groupe_id, $id_type_conges, $DEBUG);
	elseif($choix_action=="annul_fermeture")
	        confirm_annul_fermeture($fermeture_id, $fermeture_date_debut, $fermeture_date_fin, $DEBUG);
	elseif($choix_action=="commit_annul_fermeture")
	        commit_annul_fermeture($fermeture_id, $groupe_id, $DEBUG);


	bottom();




/***************************************************************/
/**********  FONCTIONS  ****************************************/

function saisie_groupe_fermeture( $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	echo "<h2>fermeture pour tous ou pour un groupe ?</h2>\n";

	echo "<table cellpadding=\"2\" cellspacing=\"3\" border=\"1\" >\n";
	echo "<tr align=\"center\">\n";
	echo "<td valign=\"top\" class=\"histo\">\n";
		/********************/
		/* Choix Tous       */
		/********************/

		// AFFICHAGE TABLEAU
		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ;
		//table contenant les bountons
		echo "<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n";
			echo "<tr align=\"center\">\n";
			echo "<td valign=\"top\">\n";
			echo "<b>". _('admin_jours_fermeture_fermeture_pour_tous') ." !</b><br>&nbsp;\n";
			echo "</td>\n";
			echo "</tr>\n";

			echo "<tr align=\"center\">\n";
			echo "<td valign=\"top\">\n";
			echo "&nbsp;\n";
			echo "</td>\n";
			echo "</tr>\n";

			echo "<tr align=\"center\">\n";
			echo "<td>\n";
				echo "<input type=\"hidden\" name=\"groupe_id\" value=\"0\">\n";
				echo "<input type=\"hidden\" name=\"choix_action\" value=\"saisie_dates\">\n";
				echo "<input type=\"submit\" value=\"". _('form_submit') ."\">  \n";
			echo "</td>\n";
			echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n" ;
	echo "</td>\n";
	echo "<td valign=\"top\" class=\"histo\">\n";
		/********************/
		/* Choix Groupe     */
		/********************/
		// Récuperation des informations :
		$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

		// AFFICHAGE TABLEAU

		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ;
		//table contenant les bountons
		echo "<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n";
			echo "<tr align=\"center\">\n";
			echo "<td valign=\"top\">\n";
			echo "<b>". _('admin_jours_fermeture_fermeture_par_groupe') .".</b><br>". _('resp_ajout_conges_choix_groupe') ."\n";
			echo "</td>\n";
			echo "</tr>\n";

			echo "<tr align=\"center\">\n";
			echo "<td valign=\"top\">\n";
			$ReqLog_gr = SQL::query($sql_gr);
			echo "<select name=\"groupe_id\">";
			while ($resultat_gr = $ReqLog_gr->fetch_array())
			{
				$sql_gid=$resultat_gr["g_gid"] ;
				$sql_group=$resultat_gr["g_groupename"] ;
				$sql_comment=$resultat_gr["g_comment"] ;

				echo "<option value=\"$sql_gid\">$sql_group";
			}
			echo "</select>";
			echo "</td>\n";
			echo "</tr>\n";

			echo "<tr align=\"center\">\n";
			echo "<td>\n";
				echo "<input type=\"hidden\" name=\"choix_action\" value=\"saisie_dates\">\n";
				echo "<input type=\"submit\" value=\"". _('form_submit') ."\">  \n";
			echo "</td>\n";
			echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n" ;
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<br>\n" ;
	echo "<form action=\"\" method=\"POST\">\n" ;
	echo "<input type=\"button\" value=\"". _('form_cancel') ."\" onClick=\"javascript:window.close();\">\n";
	echo "</form>\n" ;

}


function saisie_dates_fermeture($year, $groupe_id, $new_date_debut, $new_date_fin, $code_erreur,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	$tab_date_debut=explode("/",$new_date_debut);   // date au format d/m/Y
	$timestamp_date_debut = mktime(0,0,0, $tab_date_debut[1], $tab_date_debut[0], $tab_date_debut[2]) ;
	$date_debut_yyyy_mm_dd = $tab_date_debut[2]."-".$tab_date_debut[1]."-".$tab_date_debut[0] ;
	$tab_date_fin=explode("/",$new_date_fin);   // date au format d/m/Y
	$timestamp_date_fin = mktime(0,0,0, $tab_date_fin[1], $tab_date_fin[0], $tab_date_fin[2]) ;
	$date_fin_yyyy_mm_dd = $tab_date_fin[2]."-".$tab_date_fin[1]."-".$tab_date_fin[0] ;
	$timestamp_today = mktime(0,0,0, date("m"), date("d"), date("Y")) ;
//	$year=$tab_date_debut[2];


	// on construit le tableau de l'année considérée
	$tab_year=array();
	get_tableau_jour_fermeture($year, $tab_year,  $groupe_id,  $DEBUG);
	if( $DEBUG ) { echo "tab_year = "; print_r($tab_year); echo "<br>\n"; }


	/************************************************/
	// GESTION DES ERREURS DE SAISIE :
	//
	// $code_erreur=1 ;  // code erreur : jour feriés non saisis
	// $code_erreur=2 ;  // code erreur : $new_date_debut est posterieure a $new_date_fin
	// $code_erreur=3 ;  // code erreur : saisie de date passée
	// $code_erreur=4 ;  // code erreur : saisie de aujourd'hui
	// $code_erreur=5 ;  // code erreur : fermeture chevauche une periode deja saisie

	// on verifie que $new_date_debut est anterieure a $new_date_fin
	if($code_erreur==2)
		echo "<br><center><h3><font color=\"red\">". _('admin_jours_fermeture_dates_incompatibles') .".</font></h3></center><br><br>\n";
	// on verifie que ce ne sont pas des dates passées
	if($code_erreur==3)
		echo "<br><center><h3><font color=\"red\">". _('admin_jours_fermeture_date_passee_error') .".</font></h3></center><br><br>\n";
	// on verifie si les jours fériés de l'annee de la periode saisie sont enregistrés : sinon BUG au calcul des soldes des users !
	if($code_erreur==1)
		echo "<br><center><h3><font color=\"red\">". _('admin_jours_fermeture_annee_non_saisie') .".</font></h3></center><br><br>\n";

	// on verifie si la periode saisie ne chevauche pas une :
	// fabrication et initialisation du tableau des demi-jours de la date_debut à la date_fin
	if($code_erreur==4)
		echo "<br><center><h3><font color=\"red\">". _('admin_jours_fermeture_fermeture_aujourd_hui') .".</font></h3></center><br><br>\n";
		
	if($code_erreur==5)
			echo "<br><center><h3><font color=\"red\">". _('admin_jours_fermeture_chevauche_periode') .".</font></h3></center><br><br>\n";


	/************************************************/
	// FORMULAIRE DE SAISIE D'UNE NOUVELLE FERMETURE  + liens de navigation d'une annee a l'autre

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"width=\"100%\">\n";
	echo "<tr align=\"center\">\n";
	// cellulle de gauche : bouton annee precedente
	echo "<td align=\"left\">\n";
		$annee_precedente=$year-1;
		echo '<a href="'.schars($PHP_SELF).'?session='.schars($session).'&year='.schars($annee_precedente).'&groupe_id='.schars($groupe_id).'"> << '.schars( _('admin_jours_chomes_annee_precedente') ).'</a>'."\n";
	echo "</td>\n";
	// cellulle centrale : saisie d'une fermeture
	echo "<td width=\"450\">\n";
	echo "<fieldset class=\"cal_saisie\">\n";
	echo "<legend class=\"boxlogin\">". _('admin_jours_fermeture_new_fermeture') ."</legend>\n";
	
	/************************************************/
	// FORMULAIRE
	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ;

	/************************************************/
	// table contenant le fieldset
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr align=\"center\">\n";
		echo "<td>\n";
		echo "<fieldset class=\"cal_saisie\">\n";
			// tableau contenant saisie de date (avec javascript pour afficher les calendriers)
			echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			echo "<tr align=\"center\">\n";
				echo "<td>\n";
				echo  _('divers_date_debut') ." : <input type=\"text\" name=\"new_date_debut\" class=\"calendrier DatePicker_trigger\" value=\"$new_date_debut\" />\n" ;
				echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		echo "</fieldset>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<fieldset class=\"cal_saisie\">\n";
			// tableau contenant les mois
			echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			// ligne des boutons de défilement
			echo "<tr align=\"center\">\n";
				echo "<td>\n";
				echo  _('divers_date_fin') ." : <input type=\"text\" name=\"new date_fin\" class=\"calendrier DatePicker_trigger\" value=\"$new_date_fin\"  />\n" ;
				echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		echo "</fieldset>\n";
		echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	/************************************************/
	// SELECTION DU TYPE DE CONGES AUQUEL AFFECTER CETTE FERMETURE
	echo "<br>\n";
	// Affichage d'un SELECT de formulaire pour choix d'un type d'absence
	echo  _('admin_jours_fermeture_affect_type_conges') ;
	affiche_select_conges_id( $DEBUG);

	
	/************************************************/
	//table contenant les boutons
	echo "<table cellpadding=\"2\" cellspacing=\"3\" border=\"0\" >\n";
	echo "<tr align=\"center\">\n";
	echo "<td>\n";
	echo "<input type=\"hidden\" name=\"groupe_id\" value=\"$groupe_id\">\n";
	echo "<input type=\"hidden\" name=\"choix_action\" value=\"commit_new_fermeture\">\n";
	echo "<input type=\"submit\" value=\"". _('form_submit') ."\">  \n";
	echo "<input type=\"button\" value=\"". _('form_cancel') ."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "</form>\n" ;
	// FIN FORMULAIRE
	
	echo "</fieldset>\n";
	echo "</td>\n";
	// cellulle de droite : bouton annee suivante
	echo "<td align=\"right\">\n";
		$annee_suivante=$year+1;
		echo "<a href=\"$PHP_SELF?session=$session&year=$annee_suivante&groupe_id=$groupe_id\">". _('admin_jours_chomes_annee_suivante') ." >> </a>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	
	/************************************************/
	// HISTORIQUE DES FERMETURES

	$tab_periodes_fermeture = array();
	get_tableau_periodes_fermeture($tab_periodes_fermeture, $groupe_id,  $DEBUG);
	if(count($tab_periodes_fermeture)!=0)
	{
		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		echo "<tr align=\"center\">\n";
		echo "<td>\n";
		echo "<fieldset class=\"cal_saisie\">\n";
		echo "<legend class=\"boxlogin\">". _('admin_jours_fermeture_enregistrees') ."</legend>\n";
		// tableau contenant saisie de date (avec javascript pour afficher les calendriers)
		echo "<table class=\"histo\">\n";
		foreach($tab_periodes_fermeture as $tab_periode)
		{
			$date_affiche_1=eng_date_to_fr($tab_periode['date_deb']);
			$date_affiche_2=eng_date_to_fr($tab_periode['date_fin']);
			$fermeture_id =($tab_periode['fermeture_id']);

			echo "<tr align=\"center\">\n";
			echo "<td>\n";
			echo  _('divers_du') ." <b>$date_affiche_1</b> ". _('divers_au') ." <b>$date_affiche_2</b>  (id $fermeture_id)\n";
			echo "</td>\n";
			echo "<td>\n";
			echo "<a href=\"$PHP_SELF?session=$session&choix_action=annul_fermeture&fermeture_id=$fermeture_id&fermeture_date_debut=$date_affiche_1&fermeture_date_fin=$date_affiche_2\">". _('admin_annuler_fermeture') ."</a>\n";
			echo "</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
		echo "</fieldset>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	/************************************************/
	// CALENDRIER DES FERMETURES

	echo "<br><br>\n";
	affiche_calendrier_fermeture($year, $tab_year, $DEBUG);

}


function affiche_calendrier_fermeture($year, $tab_year, $DEBUG=FALSE)
{
			// tableau contenant les mois
			echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";

			// ligne janvier / fevrier / mars / avril
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // janvier
					affiche_calendrier_fermeture_mois($year, "01", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // fevrier
					affiche_calendrier_fermeture_mois($year, "02", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // mars
					affiche_calendrier_fermeture_mois($year, "03", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // avril
					affiche_calendrier_fermeture_mois($year, "04", $tab_year);
				echo "</td>\n";
			echo "</tr>\n";
			// ligne mai / juin / juillet / aout
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // mai
					affiche_calendrier_fermeture_mois($year, "05", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // juin
					affiche_calendrier_fermeture_mois($year, "06", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // juillet
					affiche_calendrier_fermeture_mois($year, "07", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // aout
					affiche_calendrier_fermeture_mois($year, "08", $tab_year);
				echo "</td>\n";
			echo "</tr>\n";
			// ligne septembre / octobre / novembre / decembre
			echo "<tr align=\"center\" valign=\"top\">\n";
				echo "<td>\n"; // septembre
					affiche_calendrier_fermeture_mois($year, "09", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // octobre
					affiche_calendrier_fermeture_mois($year, "10", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // novembre
					affiche_calendrier_fermeture_mois($year, "11", $tab_year);
				echo "</td>\n";
				echo "<td>\n"; // décembre
					affiche_calendrier_fermeture_mois($year, "12", $tab_year);
				echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
}


function  affiche_calendrier_fermeture_mois($year, $mois, $tab_year, $DEBUG=FALSE)
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
	echo "		<td class=\"cal-saisie2\">". _('lundi_1c') ."</td>\n";
	echo "		<td class=\"cal-saisie2\">". _('mardi_1c') ."</td>\n";
	echo "		<td class=\"cal-saisie2\">". _('mercredi_1c') ."</td>\n";
	echo "		<td class=\"cal-saisie2\">". _('jeudi_1c') ."</td>\n";
	echo "		<td class=\"cal-saisie2\">". _('vendredi_1c') ."</td>\n";
	echo "		<td class=\"cal-saisie2\">". _('samedi_1c') ."</td>\n";
	echo "		<td class=\"cal-saisie2\">". _('dimanche_1c') ."</td>\n";
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
	// affichage des cellules du 1 du mois à la fin de la ligne ...
	for($i=$first_jour_mois_rang; $i<8; $i++)
	{
		$j=$i-$first_jour_mois_rang+1 ;
		$j_timestamp=mktime (0,0,0,$mois,$j,$year);
		$j_date=date("Y-m-d", $j_timestamp);
		$j_day=date("d", $j_timestamp);
		$td_second_class=get_td_class_of_the_day_in_the_week($j_timestamp);

		if(in_array ("$j_date", $tab_year))
			$td_second_class="fermeture";

		echo "<td  class=\"cal-saisie $td_second_class\">$j_day</td>";
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
			$td_second_class="fermeture";

		echo "<td  class=\"cal-saisie $td_second_class\">$j_day</td>";
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
			$td_second_class="fermeture";

		echo "<td  class=\"cal-saisie $td_second_class\">$j_day</td>";
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
			$td_second_class="fermeture";

		echo "<td  class=\"cal-saisie $td_second_class\">$j_day</td>";
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
			$td_second_class="fermeture";

		echo "<td  class=\"cal-saisie $td_second_class\">$j_day</td>";
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
			$td_second_class="fermeture";

		echo "<td  class=\"cal-saisie $td_second_class\">$j_day</td>";
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




function commit_new_fermeture($new_date_debut, $new_date_fin, $groupe_id, $id_type_conges,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	// on transforme les formats des dates
	$tab_date_debut=explode("/",$new_date_debut);   // date au format d/m/Y
	$date_debut=$tab_date_debut[2]."-".$tab_date_debut[1]."-".$tab_date_debut[0];
	$tab_date_fin=explode("/",$new_date_fin);   // date au format d/m/Y
	$date_fin=$tab_date_fin[2]."-".$tab_date_fin[1]."-".$tab_date_fin[0];
	if( $DEBUG ) { echo "date_debut = $date_debut  // date_fin = $date_fin<br>\n"; }


	/*****************************/
	// on construit le tableau des users affectés par les fermetures saisies :
	if($groupe_id==0)  // fermeture pour tous !
		$list_users = get_list_all_users( $DEBUG);
	else
		$list_users = get_list_users_du_groupe($groupe_id,  $DEBUG);

	$tab_users = explode(",", $list_users);
	if( $DEBUG ) { echo "tab_users =<br>\n"; print_r($tab_users) ; echo "<br>\n"; }

//******************************
// !!!!
	// type d'absence à modifier ....
//	$id_type_conges = 1 ; //"cp" : conges payes

	//calcul de l'ID de de la fermeture (en fait l'ID de la saisie de fermeture)
	$new_fermeture_id=get_last_fermeture_id( $DEBUG) + 1;

	/***********************************************/
	/** enregistrement des jours de fermetures   **/
	$tab_fermeture=array();
	for($current_date=$date_debut; $current_date <= $date_fin; $current_date=jour_suivant($current_date))
	{
		$tab_fermeture[] = $current_date;
	}
	if( $DEBUG ) { echo "tab_fermeture =<br>\n"; print_r($tab_fermeture) ; echo "<br>\n"; }
	// on insere les nouvelles dates saisies dans conges_jours_fermeture
	$result=insert_year_fermeture($new_fermeture_id, $tab_fermeture, $groupe_id,  $DEBUG);

	$opt_debut='am';
	$opt_fin='pm';

	/*********************************************************/
	/** insersion des jours de fermetures pour chaque user  **/
	foreach($tab_users as $current_login)
	{
	    $current_login = trim($current_login);
		// on enleve les quotes qui ont été ajoutées lors de la creation de la liste
		$current_login = trim($current_login, "\'");

		// on compte le nb de jour à enlever au user (par periode et au total)
		// on ne met à jour la table conges_periode
		$nb_jours = 0;
		$comment="" ;

		// $nb_jours = compter($current_login, $date_debut, $date_fin, $opt_debut, $opt_fin, $comment,  $DEBUG);
		$nb_jours = compter($current_login, "", $date_debut, $date_fin, $opt_debut, $opt_fin, $comment, $DEBUG);

		if ($DEBUG) echo "<br>user_login : " . $current_login . " nbjours : " . $nb_jours . "<br>\n";

		// on ne met à jour la table conges_periode .
		$commentaire =  _('divers_fermeture') ;
		$etat = "ok" ;
		$num_periode = insert_dans_periode($current_login, $date_debut, $opt_debut, $date_fin, $opt_fin, $nb_jours, $commentaire, $id_type_conges, $etat, $new_fermeture_id, $DEBUG) ;

		// mise à jour du solde de jours de conges pour l'utilisateur $current_login
		if ($nb_jours != 0) {
			soustrait_solde_et_reliquat_user($current_login, "", $nb_jours, $id_type_conges, $date_debut, $opt_debut, $date_fin, $opt_fin, $DEBUG);

		}
	}

	// on recharge les jours fermés dans les variables de session
	init_tab_jours_fermeture($_SESSION['userlogin'],  $DEBUG);
	
	if($result)
		echo "<br>". _('form_modif_ok') .".<br><br>\n";
	else
		echo "<br>". _('form_modif_not_ok') ." !<br><br>\n";

	$comment_log = "saisie des jours de fermeture de $date_debut a $date_fin" ;
	log_action(0, "", "", $comment_log,  $DEBUG);

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
//	echo "	<input type=\"button\" value=\"". _('form_close_window') ."\" onClick=\"javascript:window.close();\">\n";
	echo "<input type=\"submit\" value=\"". _('form_ok') ."\">\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}



//function confirm_saisie_fermeture($tab_checkbox_j_ferme, $year_calendrier_saisie, $groupe_id, $DEBUG=FALSE)
function confirm_annul_fermeture($fermeture_id, $fermeture_date_debut, $fermeture_date_fin, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<table>\n";
	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo  _('divers_fermeture_du') ."  <b>$fermeture_date_debut</b> ". _('divers_au') ." <b>$fermeture_date_fin</b>.<br>\n";
	echo "<b>". _('admin_annul_fermeture_confirm') .".</b><br>\n";
	echo "<input type=\"hidden\" name=\"fermeture_id\" value=\"$fermeture_id\">\n";
	echo "<input type=\"hidden\" name=\"fermeture_date_debut\" value=\"$fermeture_date_debut\">\n";
	echo "<input type=\"hidden\" name=\"fermeture_date_fin\" value=\"$fermeture_date_fin\">\n";
	echo "<input type=\"hidden\" name=\"choix_action\" value=\"commit_annul_fermeture\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "&nbsp;\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"100\" align=\"center\">\n";
	echo "<input type=\"submit\" value=\"". _('form_continuer') ."\">\n";
	echo "</form>\n";
	echo "</td>\n";

	echo "<td width=\"100\" align=\"center\">\n";
	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<input type=\"submit\" value=\"". _('form_cancel') ."\">\n";
	echo "</form>\n";
	echo "</td>\n";

	echo "</tr>\n";
	echo "</table>\n";

}

function commit_annul_fermeture($fermeture_id, $groupe_id,  $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	if( $DEBUG ) { echo "fermeture_id = $fermeture_id <br>\n"; }


	/*****************************/
	// on construit le tableau des users affectés par les fermetures saisies :
	if($groupe_id==0)  // fermeture pour tous !
		$list_users = get_list_all_users( $DEBUG);
	else
		$list_users = get_list_users_du_groupe($groupe_id,  $DEBUG);

	$tab_users = explode(",", $list_users);
	if( $DEBUG ) { echo "tab_users =<br>\n"; print_r($tab_users) ; echo "<br>\n"; }

	/***********************************************/
	/** suppression des jours de fermetures   **/
	// on suprimme les dates de cette fermeture dans conges_jours_fermeture
	$result=delete_year_fermeture($fermeture_id,  $DEBUG);


	// on va traiter user par user pour annuler sa periode de conges correspondant et lui re-crediter son solde
	foreach($tab_users as $current_login)
	{
	    $current_login = trim($current_login);
		// on enleve les quotes qui ont été ajoutées lors de la creation de la liste
		$current_login = trim($current_login, "\'");

		// on recupère les infos de la periode ....
		$sql_credit='SELECT p_num, p_nb_jours, p_type FROM conges_periode WHERE p_login=\''.SQL::quote($current_login).'\' AND p_fermeture_id=\''.SQL::quote($fermeture_id);
		$result_credit = SQL::query($sql_credit);
		$row_credit = $result_credit->fetch_array();
		$sql_num_periode=$row_credit['p_num'];
		$sql_nb_jours_a_crediter=$row_credit['p_nb_jours'];
		$sql_type_abs=$row_credit['p_type'];


		// on ne met à jour la table conges_periode .
		$etat = "annul" ;
	 	$sql1 = 'UPDATE conges_periode SET p_etat = \''.SQL::quote($etat).'\' WHERE p_num='.SQL::quote($sql_num_periode) ;
	    $ReqLog = SQL::query($sql1);

		// mise à jour du solde de jours de conges pour l'utilisateur $current_login
		if ($sql_nb_jours_a_crediter != 0)
		{
		        $sql1 = 'UPDATE conges_solde_user SET su_solde = su_solde + '.SQL::quote($sql_nb_jours_a_crediter).' WHERE su_login=\''.SQL::quote($current_login).'\' AND su_abs_id = '.SQL::quote($sql_type_abs) ;
		        $ReqLog = SQL::query($sql1);
		}
	}

	if($result)
		echo "<br>". _('form_modif_ok') .".<br><br>\n";
	else
		echo "<br>". _('form_modif_not_ok') ." !<br><br>\n";

	// on enregistre cette action dan les logs
	if($groupe_id==0)  // fermeture pour tous !
		$comment_log = "annulation fermeture $fermeture_id (pour tous) " ;
	else
		$comment_log = "annulation fermeture $fermeture_id (pour le groupe $groupe_id)" ;
	log_action(0, "", "", $comment_log,  $DEBUG);

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
	echo "	<input type=\"submit\" value=\"". _('form_ok') ."\">\n";
//	echo "	<input type=\"button\" value=\"". _('form_close_window') ."\" onClick=\"javascript:window.close();\">\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";

}


function insert_year_fermeture($fermeture_id, $tab_j_ferme, $groupe_id,  $DEBUG=FALSE)
{
	$sql_insert="";
	foreach($tab_j_ferme as $jf_date )
	{
		$sql_insert="INSERT INTO conges_jours_fermeture (jf_id, jf_gid, jf_date) VALUES ($fermeture_id, $groupe_id, '$jf_date') ;";
		$result_insert = SQL::query($sql_insert);
	}
	return TRUE;
}

function delete_year_fermeture($fermeture_id,  $DEBUG=FALSE)
{

	$sql_delete="DELETE FROM conges_jours_fermeture WHERE jf_id = '$fermeture_id' ;";
	$result = SQL::query($sql_delete);
	return TRUE;
}


// retourne un tableau des jours fermes de l'année dans un tables passé par référence
function get_tableau_jour_fermeture($year, &$tab_year,  $groupe_id,  $DEBUG=FALSE)
{
	$sql_select = " SELECT jf_date FROM conges_jours_fermeture WHERE DATE_FORMAT(jf_date, '%Y-%m-%d') LIKE '$year%'  ";
	// on recup les fermeture du groupe + les fermetures de tous !
	if($groupe_id==0)
		$sql_select = $sql_select."AND jf_gid = 0";
	else
		$sql_select = $sql_select."AND  (jf_gid = $groupe_id OR jf_gid =0 ) ";
	$res_select = SQL::query($sql_select);
//	$res_select = SQL::query($sql_select);
//	attention ne fonctionne pas avec requete_mysql
//	$res_select = SQL::query($sql_select);

	$num_select =$res_select->num_rows;

	if($num_select!=0)
	{
	        while($result_select = $res_select->fetch_array())
		{
		        $tab_year[]=$result_select["jf_date"];
		}
	}
}


// retourne un tableau des periodes de fermeture (pour un groupe donné (gid=0 pour tout le monde))
function get_tableau_periodes_fermeture(&$tab_periodes_fermeture, $groupe_id,  $DEBUG=FALSE)
{
   $req_1="SELECT DISTINCT conges_periode.p_date_deb, conges_periode.p_date_fin, conges_periode.p_fermeture_id FROM conges_periode, conges_jours_fermeture " .
   		" WHERE conges_periode.p_fermeture_id = conges_jours_fermeture.jf_id AND conges_periode.p_etat='ok' AND conges_jours_fermeture.jf_gid = '$groupe_id' " .
  		" ORDER BY conges_periode.p_date_deb DESC ";
   $res_1 = SQL::query($req_1);

	$num_select = $res_1->num_rows;

	if($num_select!=0)
	{
	    while($result_select = $res_1->fetch_array())
		{
			$tab_periode=array();
			$tab_periode['date_deb']=$result_select["p_date_deb"];
			$tab_periode['date_fin']=$result_select["p_date_fin"];
			$tab_periode['fermeture_id']=$result_select["p_fermeture_id"];
			$tab_periodes_fermeture[]=$tab_periode;
		}
	}

}


// recup l'id de la derniere fermeture (le max)
function get_last_fermeture_id( $DEBUG=FALSE)
{
   $req_1="SELECT MAX(jf_id) FROM conges_jours_fermeture ";
   $res_1 = SQL::query($req_1);
   $row_1 = $res_1->fetch_array();
   if(!$row_1)
      return 0;     // si la table est vide, on renvoit 0
   else
      return $row_1[0];

}


// Affichage d'un SELECT de formulaire pour choix d'un type d'absence
function affiche_select_conges_id( $DEBUG=FALSE)
{
	$tab_conges=recup_tableau_types_conges( $DEBUG);
	$tab_conges_except=recup_tableau_types_conges_exceptionnels( $DEBUG);
	
	echo "<select name=id_type_conges>\n";

	foreach($tab_conges as $id => $libelle)
	{
		if($libelle == 1)
			echo "<option value=\"$id\" selected>$libelle</option>\n";
		else
			echo "<option value=\"$id\">$libelle</option>\n";
	}
	if(count($tab_conges_except)!=0)
	{
		foreach($tab_conges_except as $id => $libelle)
		{
			if($libelle == 1)
				echo "<option value=\"$id\" selected>$libelle</option>\n";
			else
				echo "<option value=\"$id\">$libelle</option>\n";
		}
	}

	echo "</select>\n";
}


// verifie si la periode donnee chevauche une periode de conges d'un des user du groupe ..
// retourne TRUE si chevauchement et FALSE sinon !
function verif_periode_chevauche_periode_groupe($date_debut, $date_fin, $num_current_periode='', $tab_periode_calcul, $groupe_id,  $DEBUG=FALSE)
{
	/*****************************/
	// on construit le tableau des users affectés par les fermetures saisies :
	if($groupe_id==0)  // fermeture pour tous !
		$list_users = get_list_all_users( $DEBUG);
	else
		$list_users = get_list_users_du_groupe($groupe_id,  $DEBUG);

	$tab_users = explode(",", $list_users);
	if( $DEBUG ) { echo "tab_users =<br>\n"; print_r($tab_users) ; echo "<br>\n"; }

	foreach($tab_users as $current_login)
	{
	    $current_login = trim($current_login);
		// on enleve les quotes qui ont été ajoutées lors de la creation de la liste
		$current_login = trim($current_login, "\'");

		$comment="";
		if(verif_periode_chevauche_periode_user($date_debut, $date_fin, $current_login, $num_current_periode, $tab_periode_calcul, $comment, $DEBUG))
			return TRUE;
	}
}

function affiche_javascript_et_css_des_calendriers()
{
?>
<script type="text/javascript">

var timer = null;
var OldDiv = "";
var newFrame = null;
var TimerRunning = false;
// ## PARAMETRE D'AFFICHAGE du CALENDRIER ## //
//si enLigne est a true , le calendrier s'affiche sur une seule ligne,
//sinon il prend la taille spécifié par défaut;

var largeur = "150";
var separateur = "/";

/* ##################### CONFIGURATION ##################### */

/* ##- INITIALISATION DES VARIABLES -##*/
var calendrierSortie = '';
//Date actuelle
var today = '';
//Mois actuel
var current_month = '';
//Année actuelle
var current_year = '' ;
//Jours actuel
var current_day = '';
//Nombres de jours depuis le début de la semaine
var current_day_since_start_week = '';
//On initialise le nom des mois et le nom des jours en VF :)
var month_name = new Array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
var day_name = new Array('L','M','M','J','V','S','D');
//permet de récupèrer l'input sur lequel on a clické et de le remplir avec la date formatée
var myObjectClick = null;
//Classe qui sera détecté pour afficher le calendrier
var classMove = "calendrier";
//Variable permettant de savoir si on doit garder en mémoire le champs input clické
var lastInput = null;
//Div du calendrier
var div_calendar = "";
var year, month, day = "";
/* ##################### FIN DE LA CONFIGURATION ##################### */

//########################## Fonction permettant de remplacer "document.getElementById"  ########################## //
function $(element){
	return document.getElementById(element);
}


//Permet de faire glisser une div de la gauche vers la droite
function slideUp(bigMenu,smallMenu){
	//Si le timer n'est pas finit on détruit l'ancienne div
	if(parseInt($(bigMenu).style.left) < 0){
		$(bigMenu).style.left = parseInt($(bigMenu).style.left) + 10 + "px";
		$(smallMenu).style.left  =parseInt($(smallMenu).style.left) + 10 + "px";
		timer = setTimeout('slideUp("'+bigMenu+'","'+smallMenu+'")',10);
	}
	else{
		clearTimeout(timer);
		TimerRunning = false;
		$(smallMenu).parentNode.removeChild($(smallMenu));
		//alert("timer up bien kill");
	}
}

//Permet de faire glisser une div de la droite vers la gauche
function slideDown(bigMenu,smallMenu){
	if(parseInt($(bigMenu).style.left) > 0){
		$(bigMenu).style.left = parseInt($(bigMenu).style.left) - 10 + "px";
		$(smallMenu).style.left =parseInt($(smallMenu).style.left) - 10 + "px";
		timer = setTimeout('slideDown("'+bigMenu+'","'+smallMenu+'")',10);
	}
	else{
		clearTimeout(timer);
		TimerRunning = false;
		//delete de l'ancienne
		$(smallMenu).parentNode.removeChild($(smallMenu));
		//alert("timer down bien kill");
	}
}

//Création d'une nouvelle div contenant les jours du calendrier
function CreateDivTempo(From){
	if(!TimerRunning){
	var DateTemp = new Date();
	IdTemp = DateTemp.getMilliseconds();
	var  NewDiv = document.createElement('DIV');
		 NewDiv.style.position = "absolute";
		 NewDiv.style.top = "0px";
		 NewDiv.style.width = "100%";
		 NewDiv.className = "ListeDate";
		 NewDiv.id = IdTemp;
		 //remplissage
		 NewDiv.innerHTML = CreateDayCalandar(year, month, day);

	$("Contenant_Calendar").appendChild(NewDiv);

		if(From == "left"){
			TimerRunning = true;
			NewDiv.style.left = "-"+largeur+"px";
			slideUp(NewDiv.id,OldDiv);
		}
		else if(From == "right"){
			TimerRunning = true;
			NewDiv.style.left = largeur+"px";
			slideDown(NewDiv.id,OldDiv);
		}
		else{
			"";
			NewDiv.style.left = 0+"px";
		}
		$('Contenant_Calendar').style.height = NewDiv.offsetHeight+"px";
		$('Contenant_Calendar').style.zIndex = "200";
		OldDiv = NewDiv.id;
	}
}

//########################## FIN DES FONCTION LISTENER ########################## //
/*Ajout du listener pour détecter le click sur l'élément et afficher le calendrier
uniquement sur les textbox de class css date */

//Fonction permettant d'initialiser les listeners
function init_evenement(){
	//On commence par affecter une fonction à chaque évènement de la souris
	if(window.attachEvent){
		document.onmousedown = start;
		document.onmouseup = drop;
	}
	else{
		document.addEventListener("mousedown",start, false);
		document.addEventListener("mouseup",drop, false);
	}
}
//Fonction permettant de récupèrer l'objet sur lequel on a clické, et l'on récupère sa classe
function start(e){
	//On initialise l'évènement s'il n'a aps été créé ( sous ie )
	if(!e){
		e = window.event;
	}
	//Détection de l'élément sur lequel on a clické
	var monElement = null;
	monElement = (e.target)? e.target:e.srcElement;
	if(monElement != null && monElement)
	{
		//On appel la fonction permettant de récupèrer la classe de l'objet et assigner les variables
		getClassDrag(monElement);

		if(myObjectClick){
			initialiserCalendrier(monElement);
			lastInput = myObjectClick;
		}
	}
}
function drop(){
		 myObjectClick = null;
}
//########################## Fonction permettant de récupèrer la liste des classes d'un objet ##########################//
function getClassDrag(myObject){
	with(myObject){
		var x = className;
		listeClass = x.split(" ");
		//On parcours le tableau pour voir si l'objet est de type calendrier
		for(var i = 0 ; i < listeClass.length ; i++){
			if(listeClass[i] == classMove){
				myObjectClick = myObject;
				break;
			}
		}
	}
}

//########################## Pour combler un bug d'ie 6 on masque les select ########################## //
function masquerSelect(){
        var ua = navigator.userAgent.toLowerCase();
        var versionNav = parseFloat( ua.substring( ua.indexOf('msie ') + 5 ) );
        var isIE        = ( (ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && (ua.indexOf('webtv') == -1) );

        if(isIE && (versionNav < 7)){
	         svn=document.getElementsByTagName("SELECT");
             for (a=0;a<svn.length;a++){
                svn[a].style.visibility="hidden";
             }
        }
}

function montrerSelect(){
       var ua = navigator.userAgent.toLowerCase();
        var versionNav = parseFloat( ua.substring( ua.indexOf('msie ') + 5 ) );
        var isIE        = ( (ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && (ua.indexOf('webtv') == -1) );
        if(isIE && versionNav < 7){
	         svn=document.getElementsByTagName("SELECT");
             for (a=0;a<svn.length;a++){
                svn[a].style.visibility="visible";
             }
         }
}

function createFrame(){
	newFrame = document.createElement('iframe');
	newFrame.style.width = largeur+"px";
	newFrame.style.height = div_calendar.offsetHeight-10+"px";
	newFrame.style.zIndex = "0";
	newFrame.frameBorder="0";
	newFrame.style.position = "absolute";
	newFrame.style.display = "block";
	//newFrame.style.opacity = 0 ;
	//newFrame.filters.alpha.opacity = 0 ;
	newFrame.style.top = 0 +"px";
	newFrame.style.left = 0+"px";
	div_calendar.appendChild(newFrame);
}

//######################## FONCTIONS PROPRE AU CALENDRIER ########################## //
//Fonction permettant de passer a l'annee précédente
function annee_precedente(){

	//On récupère l'annee actuelle puis on vérifit que l'on est pas en l'an 1 :-)
	if(current_year == 1){
		current_year = current_year;
	}
	else{
		current_year = current_year - 1 ;
	}
	//et on appel la fonction de génération de calendrier
	CreateDivTempo('left');
	//calendrier(	current_year , current_month, current_day);
}

//Fonction permettant de passer à l'annee suivante
function annee_suivante(){
	//Pas de limite pour l'ajout d'année
	current_year = current_year +1 ;
	//et on appel la fonction de génération de calendrier
	//calendrier(	current_year , current_month, current_day);
	CreateDivTempo('right');
}

//Fonction permettant de passer au mois précédent
function mois_precedent(){

	//On récupère le mois actuel puis on vérifit que l'on est pas en janvier sinon on enlève une année
	if(current_month == 0){
		current_month = 11;
		current_year = current_year - 1;
	}
	else{
		current_month = current_month - 1 ;
	}
	//et on appel la fonction de génération de calendrier
	CreateDivTempo('left');
	//calendrier(	current_year , current_month, current_day);
}

//Fonction permettant de passer au mois suivant
function mois_suivant(){
	//On récupère le mois actuel puis on vérifit que l'on est pas en janvier sinon on ajoute une année
	if(current_month == 11){
		current_month = 0;
		current_year = current_year  + 1;
	}
	else{
		current_month = current_month + 1;
	}
	//et on appel la fonction de génération de calendrier
	//calendrier(	current_year , current_month, current_day);
	CreateDivTempo('right');
}

//Fonction principale qui génère le calendrier
//Elle prend en paramètre, l'année , le mois , et le jour
//Si l'année et le mois ne sont pas renseignés , la date courante est affecté par défaut
function calendrier(year, month, day){
 	//Aujourd'hui si month et year ne sont pas renseignés
	if(month == null || year == null){
		today = new Date();
	}
	else{
		//month = month - 1;
		//Création d'une date en fonction de celle passée en paramètre
		today = new Date(year, month , day);
	}


	//Mois actuel
	current_month = today.getMonth()

	//Année actuelle
	current_year = today.getFullYear();

	//Jours actuel
	current_day = today.getDate();


	//######################## ENTETE ########################//
	//Ligne permettant de changer l'année et de mois
	var month_bef = "<a href=\"javascript:mois_precedent()\" style=\"position:absolute;left:30px;z-index:200;\" > < </a>";
	var month_next = "<a href=\"javascript:mois_suivant()\" style=\"position:absolute;right:30px;z-index:200;\"> > </a>";
	var year_next = "<a href=\"javascript:annee_suivante()\" style=\"position:absolute;right:5px;z-index:200;\" >&nbsp;&nbsp; > > </a>";
	var year_bef = "<a href=\"javascript:annee_precedente()\" style=\"position:absolute;left:5px;z-index:200;\"  > < < &nbsp;&nbsp;</a>";
	calendrierSortie = "<p class=\"titleMonth\" style=\"position:relative;z-index:200;\"> <a href=\"javascript:alimenterChamps('')\" style=\"float:left;margin-left:3px;color:#cccccc;font-size:10px;z-index:200;\"> Effacer la date </a><a href=\"javascript:masquerCalendrier()\" style=\"float:right;margin-right:3px;color:red;font-weight:bold;font-size:12px;z-index:200;\">X</a>&nbsp;</p>";
	//On affiche le mois et l'année en titre
	calendrierSortie += "<p class=\"titleMonth\" style=\"float:left;position:relative;z-index:200;\">" + year_next + year_bef+  month_bef + "<span id=\"curentDateString\">" + month_name[current_month]+ " "+ current_year +"</span>"+ month_next+"</p><div id=\"Contenant_Calendar\">";
	//######################## FIN ENTETE ########################//

	//Si aucun calendrier n'a encore été crée :
	if(!document.getElementById("calendrier")){
		//On crée une div dynamiquement, en absolute, positionné sous le champs input
		div_calendar = document.createElement("div");

		//On lui attribut un id
		div_calendar.setAttribute("id","calendrier");

		//On définit les propriétés de cette div ( id et classe )
		div_calendar.className = "calendar";

		//Pour ajouter la div dans le document
		var mybody = document.getElementsByTagName("body")[0];

		//Pour finir on ajoute la div dans le document
		mybody.appendChild(div_calendar);
	}
	else{
			div_calendar = document.getElementById("calendrier");
	}

	//On insèrer dans la div, le contenu du calendrier généré
	//On assigne la taille du calendrier de façon dynamique ( on ajoute 10 px pour combler un bug sous ie )
	var width_calendar = largeur+"px";
 	//Ajout des éléments dans le calendrier
	calendrierSortie = calendrierSortie + "</div><div class=\"separator\"></div>";
	div_calendar.innerHTML = calendrierSortie;
	div_calendar.style.width = width_calendar;
	//On remplit le calendrier avec les jours
//	alert(CreateDayCalandar(year, month, day));
	CreateDivTempo('');
}

function CreateDayCalandar(){

	// On récupère le premier jour de la semaine du mois
	var dateTemp = new Date(current_year, current_month,1);

	//test pour vérifier quel jour était le prmier du mois
	current_day_since_start_week = (( dateTemp.getDay()== 0 ) ? 6 : dateTemp.getDay() - 1);

	//variable permettant de vérifier si l'on est déja rentré dans la condition pour éviter une boucle infinit
	var verifJour = false;

	//On initialise le nombre de jour par mois
	var nbJoursfevrier = (current_year % 4) == 0 ? 29 : 28;
	//Initialisation du tableau indiquant le nombre de jours par mois
	var day_number = new Array(31,nbJoursfevrier,31,30,31,30,31,31,30,31,30,31);

	var x = 0

	//On initialise la ligne qui comportera tous les noms des jours depuis le début du mois
	var list_day = '';
	var day_calendar = '';
	//On remplit le calendrier avec le nombre de jour, en remplissant les premiers jours par des champs vides
	for(var nbjours = 0 ; nbjours < (day_number[current_month] + current_day_since_start_week) ; nbjours++){

		// On boucle tous les 7 jours pour créer la ligne qui comportera le nom des jours en fonction des<br />
		// paramètres d'affichage
		if(verifJour == false){
			for(x = 0 ; x < 7 ; x++){
				if(x == 6){
					list_day += "<span>" + day_name[x] + "</span>";
				}
				else{
					list_day += "<span>" + day_name[x] + "</span>";
				}
			}
			verifJour = true;
		}
		//et enfin on ajoute les dates au calendrier
		//Pour gèrer les jours "vide" et éviter de faire une boucle on vérifit que le nombre de jours corespond bien au
		//nombre de jour du mois
		if(nbjours < day_number[current_month]){
			if(current_day == (nbjours+1)){
				day_calendar += "<span onclick=\"alimenterChamps(this.innerHTML)\" class=\"currentDay DayDate\">" + (nbjours+1) + "</span>";
			}
			else{
				day_calendar += "<span class=\"DayDate\" onclick=\"alimenterChamps(this.innerHTML)\">" + (nbjours+1) + "</span>";
			}
		}
	}

	//On ajoute les jours "vide" du début du mois
	for(i  = 0 ; i < current_day_since_start_week ; i ++){
		day_calendar = "<span>&nbsp;</span>" + day_calendar;
	}
	//On met également a jour le mois et l'année
	$('curentDateString').innerHTML = month_name[current_month]+ " "+ current_year;
	return (list_day  + day_calendar);
}

function initialiserCalendrier(objetClick){
		//on affecte la variable définissant sur quel input on a clické
		myObjectClick = objetClick;

		if(myObjectClick.disabled != true){
		    //On vérifit que le champs n'est pas déja remplit, sinon on va se positionner sur la date du champs
		    if(myObjectClick.value != ''){
			    //On utilise la chaine de separateur
					var reg=new RegExp("/", "g");
					var dateDuChamps = myObjectClick.value;
					var tableau=dateDuChamps.split(reg);
					calendrier(	tableau[2] , tableau[1] - 1 , tableau[0]);
		    }
		    else{
			    //on créer le calendrier
			    calendrier(objetClick);


		    }
		    //puis on le positionne par rapport a l'objet sur lequel on a clické
		    //positionCalendar(objetClick);
		    positionCalendar(objetClick);
			fadePic();
		    //masquerSelect();
			createFrame();
		}

}

 //Fonction permettant de trouver la position de l'élément ( input ) pour pouvoir positioner le calendrier
function ds_getleft(el) {
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}

function ds_gettop(el) {
	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}

//fonction permettant de positioner le calendrier
function positionCalendar(objetParent){
	//document.getElementById('calendrier').style.left = ds_getleft(objetParent) + "px";
	document.getElementById('calendrier').style.left = ds_getleft(objetParent) + "px";
	//document.getElementById('calendrier').style.top = ds_gettop(objetParent) + 20 + "px" ;
	document.getElementById('calendrier').style.top = ds_gettop(objetParent) + 20 + "px" ;
	// et on le rend visible
	document.getElementById('calendrier').style.visibility = "visible";
}
//Fonction permettant d'alimenter le champs
function alimenterChamps(daySelect){
		if(daySelect != ''){
			lastInput.value= formatInfZero(daySelect) + separateur + formatInfZero((current_month+1)) + separateur +current_year;
		}
		else{
			lastInput.value = '';
		}
		masquerCalendrier();
}
function masquerCalendrier(){
		fadePic();
		//On Masque la frame /!\
//		newFrame.style.display = "none";
		document.getElementById('calendrier').style.visibility = "hidden";
		//montrerSelect();
}

function formatInfZero(numberFormat){
		if(parseInt(numberFormat) < 10){
				numberFormat = "0"+numberFormat;
		}

		return numberFormat;
}

function CreateSpan(){
	var spanTemp = document.createElement("span");
		spanTemp.className = "";
		spanTemp.innerText = "";
		spanTemp.onClick = "";
	return spanTemp;
}

//######################## FONCTION PERMETTANT DE VERIFIER UNE DATE SAISI PAR L UTILISATEUR ########################//
function CheckDate(d) {
      // Format de la date : JJ/MM/AAAA .
      var j=(d.substring(0,2));
      var m=(d.substring(3,5));
      var a=(d.substring(6));
	  var regA = new RegExp("[0-9]{4}");
	  alert(regA.test(a));
      if ( ((isNaN(j))||(j<1)||(j>31))) {
         return false;
      }

      if ( ((isNaN(m))||(m<1)||(m>12))) {
         return false;
      }

      if ((isNaN(a))||(regA.test(a))) {
         return false;
      }
      return true;
}
//######################## FONCTION PERMETTANT D'AFFICHER LE CALENDRIER DE FACON PROGRESSIVE ########################//
var max = 100;
var min = 0;
var opacite=min;
up=true;
var IsIE=!!document.all;


function fadePic(){
try{
				var ThePic=document.getElementById("calendrier");
				if (opacite < max && up){opacite+=5;}
				if (opacite>min && !up){opacite-=5;}
				IsIE?ThePic.filters[0].opacity=opacite:document.getElementById("calendrier").style.opacity=opacite/100;

				if(opacite<max && up){
					timer = setTimeout('fadePic()',10);
				}
				else if(opacite>min && !up){
					timer = setTimeout('fadePic()',10);
				}
				else{
					if (opacite==max){up=false;}
					if (opacite<=min){up=true;}
					clearTimeout(timer);
				}
}
catch(error){
	alert(error.message);
}
}

window.onload = init_evenement;
</script>

<style type="text/css">
/* CSS Document */
.calendar{
	background-color:#f7f6f3;
	position:absolute;
	font-family:Arial, Helvetica, sans-serif;
	font-size:9px;
	border:1px solid #0099cc;
	-moz-opacity:0;
	filter:alpha(opacity=0);

}
.calendar a{
	text-decoration:none;
	color:#ffffff;
	font-weight:bold;
}
.ListeDate{
	background-color:#FFFFFF;
}
#Contenant_Calendar{
	float:left;
	width:100%;
	overflow:hidden;
	position:relative;
}
#Contenant_Calendar span{
	float:left;
	display:block;
	width:20px;
	height:20px;
	line-height:20px;
	text-align:center;
}
.DayDate:hover{
	background-color:#8CD1EC;
	cursor:pointer;
}
#curentDateString{
	width:100%;
	text-align:center;
}
.titleMonth{
	width:100%;
	background-color:#08a1d4;
	color:#FFFFFF;
	text-align:center;
	border-bottom:1px solid #666;
	margin:0px;
	padding:0px;
	padding-bottom:2px;
	margin-top:0px;
	margin-bottom:0px;
	font-weight:bold;
}
.separator{
	float:left;
	display:block;
	width:15px;
}
.currentDay{
	font-weight:bold;
	background-color:#FFB0B0;
}
/* pour l'image de fond (calendrier) du champ de saisie */
input.DatePicker_trigger{
	background-image:url(../template/img/DatePicker.gif);
	background-position:100% 50%;
	background-repeat:no-repeat;
	cursor:pointer;
	padding-right:20px;
	width:90px
}

</style>
<?php
}

