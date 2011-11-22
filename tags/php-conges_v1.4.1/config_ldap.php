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

include("controle_ids.php") ;

//  CONFIG ACCES AU SERVEUR LDAP (optionnelle)
//----------------------------------------------

/*****************************************************************/
/*			PARAMATRAGE LDAP
Ce fichier est utilisis� par PHP_CONGES SEULEMENT SI vous avez activ� les options 
concernants le ldap dans la configuration de php_conges :
	where_to_find_user_email="ldap" ;
	how_to_connect_user="ldap";

=> vous devez configurer ce fichier pour que les requ�tes LDAP 
s'effectuent sans probl�me.

/!\ quelques notions de LDAP sont n�cessaires � la bonne compr�hension
de la chose. Pour d�grossir ces notions, le web sera votre ami.
Notions de base : http://www.commentcamarche.net/ldap/ldapintro.php3
http://www-sop.inria.fr/semir/personnel/Laurent.Mirtain/ldap-livre.html

Je vous conseille �galement afin d'avoir une bonne visilit� de votre
arborescence ldap d'utiliser "LDAP Browser", qui va vous permettre de vous
aider � renseigner les champs de ce fichier...

Nous n'utiliserons pas tous les champs de l'annuaire. Voici la liste des champs
utilis�s (et pour vous aider 2 exemples de param�trage pour ActiveDirectory2003 et OpenLDAP)

- $config_ldap_server : nom de votre serveur ayant le ldap (ou adresse IP).
	Pour info, fonctionne aussi en ldaps.
	ex : 	   $config_ldap_server = "http://nom_de_mon_serveur";
	(en ldaps) $config_ldap_server = "https://nom_de_mon_serveur";

- Vous pouvez �galement d�finir un serveur de "backup" (serveur secondaire de domaine) :
	$config_ldap_bupsvr = "http://serveur2"; 
	Si vous n'en avez pas, laisser le champ vide.

- $config_ldap_protocol_version : num�ro de version du protocole LDAP utilis� par votre serveur.
	Les serveur LDAP r�cents (notamment OpenLDAP 2.x.x) utilisent la version 3 du protocole.
	Pour les serveur utilisant une version ant�rieure du protocole, cette option doit rester � 0;
	$config_ldap_protocol_version = 0 ;

---------------------------------------------------------------------------------------
- $config_basedn : quelle est la racine de votre domaine ?
	ex : $config_basedn = "dc=mon_domaine,dc=fr";
	Si vous avez un domaine toto.com, vous avez configur� sous un AD2003 un
	sous-domaine (uniquement pour vos besoins internes) 'administration',
	$config_basedn sera alors "dc=administration,dc=toto,dc=com"


- $config_ldap_user : s'il faut s'identifier pour acc�der au ldap, rentrer un login ici.
	Pour un AD2003, il me semble que c'est obligatoire. Il est vivement conseill� 
	de cr�er un utilisateur qui ne servira qu'� cela (et ainsi �vitera de mettre en clair
	le mot de passe d'un utilisateur existant), et interdire l'acc�s aux PC de cet utilisateur.
	(cf votre documentation (ou votre bible) ;-) Windows Server...)
	Ex (AD, utilisateur "ldap") : $config_ldap_user   = "CN=ldap,dc=administration,dc=toto,dc=com" ;

- $config_ldap_pass : le mot de passe associ� au compte ci-dessus...

On peut laisser ces deux champ vides si la connexion au ldap anonyme est autoris�e.


- $config_searchdn : permet d'indiquer le point d'entr�e dans l'arborescence du LDAP.
	En effet, sous un AD, par exemple, vous avez "MesOrdinateurs", "MesUtilisateurs", "MesGroupes"... 
	Il est inutile de rechercher dans tout l'arbre.
	Ex (AD) : $config_searchdn = "ou=MesUtilisateurs,dc=administration,dc=toto,dc=com";
	(pour un OpenLdap 	   = "ou=people,dc=mon_domaine,dc=fr"		) 


Les champs suivants vont nous permettre d'extraire du ldap les donn�es qui nous int�resse : 
En effet, m�me standardis�, d'un ldap � l'autre le nommage des champs vont �tre diff�rents.
Nous prendrons 2 exemples "les + courants", Active Directory et OpenLDAP.
Pour Novell, IBM, ... : cf doc de votre ldap !

- $config_ldap_prenom : dans quel champ est indiqu� le pr�nom de la personne ?
	AD ou OpenLDAP : "givenname"

- $config_ldap_nom : dans quel champ est indiqu� le nom ?
	AD ou OpenLDAP : "sn"

- $config_ldap_mail : quel champ poss�de le mail de la personne ?
	AD ou OpenLDAP : "mail"

- $config_ldap_login : quel champ poss�de l'identifiant de l'utilisateur ?
	AD : "samaccountname", OpenLdap : "uid"

- $config_ldap_nomaff : quel champ du ldap affiche le nom et le pr�nom (dans le m�me champ) ?
	AD ou OpenLDAP : "displayName"

---------------------------------------------------------------------------------------
Ce qui suit sert uniquement dans le mode Administrateur pour lister les utilisateurs de
votre LDAP dans une zone de liste d�roulante (et ainsi �viter la saisie du login, nom,
pr�nom, mot de passe, mail, ...).

On va devoir d�finir des crit�res de recherche :
- $config_ldap_filtre : sur quel filtre (quel champ du ldap) ?
- $config_ldap_filrec : crit�re de recherche.
	Ex : si on veut les "users" (permet de lister que les personnes) d'AD, il faut :
	$config_ldap_filtre = "objectclass";
	$config_ldap_filrech= "user";

	Vous pouvez aller plus loin suivant la fa�on dont vous avez renseign� le LDAP.
	Ex pour une universit�, nous avons le champ "supannAffectation" qui permet
	d'affecter les personnes aux composantes, on renseignera donc :
	$config_ldap_filtre = "supannAffectation";
	$config_ldap_filrech= "Scienc*";

	On recherche donc toutes les personnes dont l'affectation commencent par 'Scienc'
	(noter ici l'utilisation du caract�re joker *)

Vous pouvez aller ENCORE plus loin en d�finissant vous-m�me le filtre complet de recherche :
$config_ldap_filtre_complet

/!\ Laisser ce champ vide si vous ne souhaitez pas l'utiliser !

	Et vous pouvez d�finir vos propres requ�tes ldap (en utilisant le langage associ�).
	Reprenons l'exemple de notre universit� qui veut que tous les cong�s personnels de scienc*
	et de droit soient g�r�s par l'application.
	$config_ldap_filtre_complet = "(&(displayName=*)(|(supannAffectation=Scienc*)(supannAffectation=DROIT)))"; 

	Je recherche tous les (utilisateurs) displayName=* ET
			(dont l'affectation 'supannAffectation' commence par Scien*
			   OU l'affectation 'supannAffectation' est DROIT)


**********************************************************************

INFORMATION IMPORTANTE :

	Je ne r�pondrai pas aux questions concernant le ldap, sa configuration,
	son utilisation. 
	Si vous �tes le responsable de la mise en place des cong�s, veuillez
	voir votre responsable informatique / administrateur r�seau.
	Si vous �tes administrateur r�seau, chercher sur le net pour configurer
	un ldap.
	
	Pourquoi ? Tout simplement parce que je n'ai pas mis en place de ldap,
	et que j'utilise celui qui a �t� mis � ma disposition.


					Didier CHABAUD
					Universit� d'Auvergne
					D�cembre 2005


********************************************************************/

/********************************************************************/

// Voici ci-dessous 2 configs "pr�-remplies" qui peuvent vous aider.
// (d�commenter celle qui vous int�resse)


/**********  config Active Directory  ********/
/*-------------------------------------------*/

$config_ldap_server = "ldap://mon_serveur";
$config_ldap_protocol_version = 0 ;   // 3 si version 3 , 0 sinon !
$config_ldap_bupsvr = "";
$config_basedn      = "dc=mon_domaine,dc=fr";
$config_ldap_user   = "CN=user_ldap,dc=mon_domaine,dc=com" ;
$config_ldap_pass   = "user_ldap_pass";
$config_searchdn    = "ou=MesUtilisateurs,dc=mon_domaine,dc=com";

$config_ldap_prenom = "givenname"; 
$config_ldap_nom    = "sn";
$config_ldap_mail   = "mail";
$config_ldap_login  = "samaccountname";
$config_ldap_nomaff = "displayName";
$config_ldap_filtre = "objectclass";
$config_ldap_filrech= "user";

$config_ldap_filtre_complet = "";   



/********** Config OpenLDAP (en ldaps, anonyme autoris�) **********/
/*----------------------------------------------------------------*/
/*
$config_ldap_server = "ldaps://mon_serveur";
$config_ldap_protocol_version = 0 ;   // 3 si version 3 , 0 sinon !
$config_ldap_bupsvr = "";
$config_basedn      = "dc=mon_domaine,dc=com";
$config_ldap_user   = "";
$config_ldap_pass   = "";
$config_searchdn    = "ou=people,dc=mon_domaine,dc=com";

$config_ldap_prenom = "givenname"; 
$config_ldap_nom    = "sn";
$config_ldap_mail   = "mail";
$config_ldap_login  = "uid";
$config_ldap_nomaff = "displayName";
$config_ldap_filtre = "mon_filtre_de_recherche";
$config_ldap_filrech= "mon_crit�re_de_recherche";

$config_ldap_filtre_complet = "";   
*/


?>
