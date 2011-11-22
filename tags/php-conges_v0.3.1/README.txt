***************************************
****         php_conges            ****
***************************************



Descriptif :
--------------
Application web int�ractive de gestion des cong�s du personnels d'un service .

PHP_conges se veut tr�s configurable afin de fournir ou non diverses fonctionnalit�s aux utilisateurs.
Cette application se pr�sente en 3 volets :
1 - volet utilisateur :
   Les utilisateurs ont acc�s au bilan et � l'historique de leurs cong�s. Ils ont �galement acc�s au calendrier
   des cong�s de tous les personnels du service.
   Ce calendrier donne une repr�sentation graphique des absences des personnes (cong�s, artt, temps partiels).
   Dans sa version par d�faut, les utilisateurs peuvent �galement saisir leurs demandes de cong�s. Chaque demande 
   est ensuite accept�e ou refus�e par le responsable. L'utilisateur � alors �galement acc�s � l'historique de ces
   demandes.
   Cependant, une option de configuration permet de supprimer cette possibilit�. Dans ce cas, c'est le responsable
   qui saisi les cong�s des personnels.
2 - volet responsable :
   permet � un ou plusieurs responsables de g�rer les demandes de cong�s des utilisateurs, de remettre les cong�s 
   � jour en d�but d'ann�e, etc ....
   L'application peut �galement fonctionner en mode "responsable g�n�rique virtuel", ce qui permet d'avoir plusieurs 
   responsables r��ls (physiques) qui se connectent avec le m�me login pour g�rer les cong�s des personnels.
   Choisir ce mode de fonctionnement entraine que tous les utilisateurs de php_conges sont trait�s comme des utilisateurs
   classiques (m�me s'ils sont enregistr�s comme responsable dans la database !!!).
   (le login du responsable virtuel est "conges" et le mot de passe par d�faut est "conges" ... � changer au + vite)
3 - volet administrateur : 
   Ce volet ne sert qu'a administrer les utilisateurs dans la base de donn�es. (ajout, suppression, modification, 
   changement de mot de passe, ... d'un utilisateur)

Le principe de fonctionnement utilisateurs/responsables est simple :
Chaque utilisateur est rattach� � un responsable (cf structure de la base de donn�es). C'est ce responsable
qui valide des demandes de cong�s de l'utilisateur, ou saisi les cong�s de ce dernier (en fonction des options de
configuration choisies).



Pr�requis :
--------------
serveur web + PHP + MySQL
PHP_conges a �t� test� sous apache (v1.3.x et v2) et PHP (v4.2.x et 4.3.x) et MySQL (v3.23.x)
(configuration de PHP  : "register_globals" ON ) 



Licence :
----------
(voir fichier license.txt ou http://www.linux-france.org/article/these/gpl.html )
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




INSTALL :
----------
voir le fichier INSTALL.txt



Configuration :
----------------
l'Application est configurable via un fichier "config.php" qui contient de nombreuses options de configuration. 
Ces options permettent de configurer l'affichage comme de valider ou d�valider certaines fonctionnalit�s du logiciel.
(cf fichier config.php pour + de d�tails).



Contact :
------------
http://www.univ-montp2.fr/~ced/php_conges/
mail : cedri.chauvineau@univ-montp2.fr

.
