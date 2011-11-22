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
include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==1){ include("../INCLUDE.PHP/verif_droits.php");}
?>
<?php
	if($config_resp_vertical_menu==1) // menu vertical
	{
		printf("<HTML><HEAD><TITLE> CONGES : Page Responsable</TITLE></HEAD>\n");
		printf("<FRAMESET COLS=\"200,*\">\n");
		printf("<FRAME NAME=\"MenuFrame\" SRC=\"resp_menu.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<FRAME NAME=\"MainFrame\" SRC=\"resp_main.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<NOFRAMES>\n");
		printf("<BODY>\n</BODY>\n</NOFRAMES>\n</FRAMESET>\n</HTML>\n");
	}
	elseif($config_resp_vertical_menu==0) // menu horizontal
	{
		printf("<HTML><HEAD><TITLE> CONGES : Page Responsable</TITLE></HEAD>\n");
		printf("<FRAMESET ROWS=\"100,*\">\n");
		printf("<FRAME NAME=\"MenuFrame\" SRC=\"resp_menu.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<FRAME NAME=\"MainFrame\" SRC=\"resp_main.php?session=$session\" MARGINWIDTH=0 MARGINHEIGHT=0>\n");
		printf("<NOFRAMES>\n");
		printf("<BODY>\n</BODY>\n</NOFRAMES>\n</FRAMESET>\n</HTML>\n");
	}

?>
