<?php

include("controle_ids.php") ;

/**
**	@author Benjamin Husson
**
**	-------------------------Fichier de configuration du serveur CAS-------------------------
**
**	CAS pour Syst�me d'Authentification  Centralis� http://www.yale.edu/tp/cas/
** 
**  Pre-requis pour l'utilisation du mode d'authentification CAS (utilisation de la librairie phpcas et de ses d�pendances)
**	http://esup-phpcas.sourceforge.net/requirements.html
**
**	Pour utiliser le syst�me d'authentification CAS le param�tre de configuration de php_conges "how_to_connect_user"
**	doit �tre positionn� a "CAS"
**	
** 	ATTENTION : Un utilisateur ne peut se connecter en utilisant CAS uniquement si le login utilis� par CAS 
**	est identique au champ u_login de la table conges_users de la bdd.
**	REMARQUE : CAS s'appuyant souvent sur un annuaire LDAP, l'utilisation de CAS ne rentre pas en conflit avec l'utilisation de LDAP 
**	pour gerer la cr�ation d'utilisateurs en mode Admin.
**	(il est m�me recommand� d'utiliser l'authentification CAS en parrallele avec la cr�ation d'utilisateurs en mode ldap.)
**	De cette fa�on les logins associ�s aux utilisateurs de php_conges seront identique � ceux utilis�s par CAS.
**
**	Fichier de configuration du syst�me d'authentification CAS. 
**	
**	$config_CAS_host = hostName				adresse du serveur CAS
**	$config_CAS_portNumber = PortNumber		numero de port sur lequel tourne le service (par d�faut 443)
**	$config_CAS_URI = "" 					vide par d�faut, c'est la sous adresse pour le service CAS
**/

$config_CAS_host = "localhost";		//adresse http
$config_CAS_portNumber = 443; 	//entier
$config_CAS_URI = "";		//chemin relatif (peut �tre vide)
?>
