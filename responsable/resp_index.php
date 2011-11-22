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
