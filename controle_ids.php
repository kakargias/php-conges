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

// Ce script fait appel � la librairie PHPIDS pour d�tecter les tentatives d'intrusion 

// test si o� on est .....
if(is_dir("INCLUDE.EXTERNAL"))  // test si on est � la racine
{
	$ids_include_pathdir = "INCLUDE.EXTERNAL/phpids-0.5.3/lib";
	$deconnexion_page = "deconnexion.php";
}
else    // alors on est dans un autre r�pertoire ...
{
	$ids_include_pathdir = "../INCLUDE.EXTERNAL/phpids-0.5.3/lib";
	$deconnexion_page = "../deconnexion.php";
}

/* controle des param�tres par PHPIDS */
$old_incude_path=get_include_path(); // on stoque l'include_path par defaut pour le remettre ensuite 
set_include_path($ids_include_pathdir);

require_once 'IDS/Init.php';

$request = array( 'REQUEST' => $_REQUEST, 'GET' => $_GET, 'POST' => $_POST, 'COOKIE' => $_COOKIE );

$init = IDS_Init::init(dirname(__FILE__) . '/INCLUDE.EXTERNAL/phpids-0.5.3/lib/IDS/Config/Config.ini');
//
$init->config['General']['base_path'] = dirname(__FILE__) . '/INCLUDE.EXTERNAL/phpids-0.5.3/lib/IDS/';
$init->config['General']['use_base_path'] = true;
$init->config['Caching']['caching'] = 'none';
//
$ids = new IDS_Monitor($request, $init);
$result = $ids->run();

// en cas de detection de tentative frauduleuse :
if (!$result->isEmpty()) 
{
	// Take a look at the result object
	//echo $result;
	
	//on reagit � un impact > � 50  (intrusion serieuse) .... 
	if($result->getImpact() > 50 )
	{
		echo " Intrusion Alert : User Disconnected !!!<br>\n";
		 // on detruit la session et le cookie !!!
		$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;
		session_start();
		// destruction du cookie
		setcookie($session, '', time()-42000, '/');
		//destruction de la session
		session_destroy();
		// on sort compl�tement du prog
		exit;
	}
}

set_include_path($old_incude_path);
?>
