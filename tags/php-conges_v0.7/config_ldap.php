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

/*
ATTENTION :
-----------
L'utilisation d'annuaire LDAP est EXPERIMENTALE dans php_conges !
Cette fonctionnalit� va �tre d�vellop�e prochainnement. Actuellement, seule la r�cup�ration del'adresse mail d'un utilisateur
fonctionne, et ce, seulement avec un annuaire de type 'active'directory'.
Merci de votre patience ;-)
*/


//  CONFIG ACCES AU SERVEUR LDAP (optionnelle)
//----------------------------------------------
// param�tres de connexion au serveur LDAP : 
$config_basedn = "mydn";
$config_ldap_server = "myldapserver";
$config_ldap_user = "myldapuser";
$config_ldap_pass = "myldappasswd";
$config_searchdn = "mysearchdn";


?>
