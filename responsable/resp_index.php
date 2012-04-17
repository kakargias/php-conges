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

include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';
include INCLUDE_PATH .'session.php';
include ROOT_PATH .'fonctions_calcul.php';

$DEBUG = FALSE ;
//$DEBUG = TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_resp", $DEBUG);


	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$onglet = getpost_variable('onglet', "page_principale");
	
	
	/*********************************/
	/*   COMPOSITION DES ONGLETS...  */
	/*********************************/

	$onglets = array();
	
	
	$onglets['page_principale'] = _('resp_menu_button_retour_main');

	if( $_SESSION['config']['user_saisie_demande'] ) 
		$onglets['traitement_demandes'] = _('resp_menu_button_traite_demande');
		
	if( $_SESSION['config']['resp_ajoute_conges'] )
		$onglets['ajout_conges'] = _('resp_ajout_conges_titre');
	
	if (false)
		$onglets['cloture_exercice'] = _('button_cloture');
	
	if ( !isset($onglets[ $onglet ]) && !in_array($onglet, array('traite_user')))
		$onglet = 'page_principale';
	
	/*********************************/
	/*   COMPOSITION DU HEADER...    */
	/*********************************/
	
	$add_css = '<style>
		#onglet_menu { width: 100%;}
		#onglet_menu .onglet.active{ border: 1px solid black; margin: -1px; }
		#onglet_menu .onglet{ width: '. (100 / count($onglets) ).'% ; float: left; padding: 10px 0px 10px 0px;}
	</style>';
	
	header_menu('responsable',$_SESSION['config']['titre_resp_index'],$add_css);
		
	/*********************************/
	/*   AFFICHAGE DES ONGLETS...  */
	/*********************************/
	
	echo '<div id="onglet_menu" class="ui-widget-header ui-helper-clearfix ui-corner-all">';
	foreach($onglets as $key => $title) {
		echo '<div class="onglet '.($onglet == $key ? ' active': '').'" >
			<a href="'.$PHP_SELF.'?session='.$session.'&onglet='.$key.'">'. $title .'</a>
		</div>';
	}
	echo '</div>';
	
	
	/*********************************/
	/*   AFFICHAGE DE L'ONGLET ...    */
	/*********************************/
	
	
	/** initialisation des tableaux des types de conges/absences  **/
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_cong=recup_tableau_types_conges( $DEBUG);

	// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
//	if ($_SESSION['config']['gestion_conges_exceptionnels']) 
		$tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels( $DEBUG);
	
	echo '<div class="'.$onglet.'">';
		include ROOT_PATH . 'responsable/resp_'.$onglet.'.php';
	echo '</div>';
	
	/*********************************/
	/*   AFFICHAGE DU BOTTOM ...   */
	/*********************************/
	
	bottom();
	exit;
	
