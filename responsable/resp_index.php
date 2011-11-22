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

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$session=session_id();

	if($_SESSION['config']['resp_vertical_menu']==TRUE) // menu vertical
	{
		printf("<HTML><HEAD>\n");
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
		printf("<TITLE> ".$_SESSION['config']['titre_resp_index']." </TITLE></HEAD>\n");
		printf("<FRAMESET COLS=\"200,*\">\n");
		printf("<FRAME NAME=\"MenuFrame\" SRC=\"resp_menu.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<FRAME NAME=\"MainFrame\" SRC=\"resp_main.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<NOFRAMES>\n");
		printf("<BODY>\n</BODY>\n</NOFRAMES>\n</FRAMESET>\n</HTML>\n");
	}
	elseif($_SESSION['config']['resp_vertical_menu']==FALSE) // menu horizontal
	{
		printf("<HTML><HEAD>\n");
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
		printf("<TITLE> CONGES : Page Responsable</TITLE></HEAD>\n");
		printf("<FRAMESET ROWS=\"90,*\">\n");
		printf("<FRAME NAME=\"MenuFrame\" SRC=\"resp_menu.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<FRAME NAME=\"MainFrame\" SRC=\"resp_main.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<NOFRAMES>\n");
		printf("<BODY>\n</BODY>\n</NOFRAMES>\n</FRAMESET>\n</HTML>\n");
	}

?>
