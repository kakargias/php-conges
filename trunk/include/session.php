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


defined( '_PHP_CONGES' ) or die( 'Restricted access' );

//include_once(ROOT_PATH."config/profile.php");
//include_once  INCLUDE_PATH .'get_text.php';

//
// MAIN
//

/*** initialisation des variables ***/
$session_username="";
$session_password="";
/************************************/

//
// recup du num  de session (mais on ne sais pas s'il est passé en GET ou POST
$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : "") ) ;
$_SESSION['lang'] = (isset($_GET['lang']) ? $_GET['lang'] : ((isset($_POST['lang'])) ? $_POST['lang'] : "") ) ;

$DEBUG=FALSE;
//$DEBUG=TRUE;

if( $DEBUG ) { print_r($_SESSION); echo "<br><br>\n"; }




if ($session != "") //  UNE SESSION EXISTE
{
	if( $DEBUG ) { echo "session = $session<br><br>\n"; }
	
	if(session_is_valid($session) )
	{
		session_update($session);
	}
	else
	{
		session_delete($session);
		$session="";
		$session_username="";
		$session_password="";
		$_SESSION['config']=init_config_tab();  // on recrée le tableau de config pour l'url du lien
		
		redirect(ROOT_PATH . 'index.php?error=session-invalid');
	}
}
else    //  PAS DE SESSION   ($session == "")
{
	redirect(ROOT_PATH . 'index.php');
}


