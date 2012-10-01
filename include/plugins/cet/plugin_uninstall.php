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

include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$DEBUG=FALSE;
$timeout=2 ; // refresh après maj.

$PHP_SELF=$_SERVER['PHP_SELF'];

if($session=="")
    $URL = "$PHP_SELF";
else
    $URL = "$PHP_SELF?session=$session";
echo "<META HTTP-EQUIV=REFRESH CONTENT=\"$timeout; URL=$URL\">";

$delete_table_plugin_cet_query = "DROP TABLE `conges_plugin_cet`;";


$result_delete_table_plugin = SQL::query($delete_table_plugin_cet_query);

$update_plugin_table = "INSERT INTO conges_plugins(p_name,p_is_install,p_is_active) VALUES ('".$plugin."','0','0')
  ON DUPLICATE KEY UPDATE p_is_install='0';";
//$update_plugin_table = "UPDATE conges_plugins SET p_is_install='0' WHERE p_name='$plugin';"
$result_update_plugin_table = SQL::query($update_plugin_table);

?>
