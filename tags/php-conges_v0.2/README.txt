***************************************
****         php_conges            ****
***************************************



Descriptif :
--------------
Application web interractive de gestion des conges du personnels d'un service .

PHP_conges se veut très configurable afin de fournir ou non diverses fonctionalités aux utilisateurs.
Cette application se présente en 3 volets :
1 - volet utilisateur :
   Les utilisateurs ont accès au bilan et à l'historique de leurs congés. Ils ont également accès au calendrier
   des congès de tous les personnels du service.
   Ce calendrier donne une représentation graphique des absences des personnes (conges, artt, temps partiels).
   Dans sa version par défaut, les utilisateurs peuvent également saisir leurs demandes de congès. Chaque demande 
   est ensuite acceptée ou refusée par le responsable. L'utilisateur à alors également accès à l'historique de ces
   demandes.
   Cependant, une option de configuration permet de supprimer cette possibilité. Dans ce cas, c'est le responsable
   qui saisi les congés des personnels.
2 - volet responsable :
   permet à un ou plusieurs responsables de gérer les demandes de congès des utilisateurs, de remettre les conges 
   à jour en début d'année, etc ....
3 - volet administrateur : 
   Ce volet ne sert qu'a administrer les utilisateurs dans la base de données. (ajout, suppression, modification, 
   changement de mot de passe, ... d'un utilisateur)

Le principe de fonctionnement utilisateurs/responsables est simple :
Chaque utilisateur est rattaché à un responsable (cf structure de la base de données). C'est ce responsable
qui valide des demandes de congés de l'utilisateur, ou saisi les congés de ce dernier (en fonction des options de
configuration choisies).



Prérequis :
--------------
serveur web + PHP + MySQL
PHP_conges a été testé sous apache (v1.3.x et v2) et PHP (v4.2.x et 4.3.x) et MySQL (v3.23.x)
(configuration de PHP  : "register_globals" ON ) 



Licence :
----------
(voir fichier license.txt ou http://www.linux-france.org/article/these/gpl.html )
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




INSTALL :
----------
voir le fichier INSTALL.txt



Configuration :
----------------
l'Application est configurable via un fichier "config.php" qui contient de nombreuses options de configuration. 
Ces options permettent de configurer l'afichage comme de valider ou dévalider certaines fonctionalités du logiciel.
(cf fichier config.php pour + de détails).



Contact :
------------
http://www.univ-montp2.fr/~ced/php_conges/
mail : cedri.chauvineau@univ-montp2.fr

.
