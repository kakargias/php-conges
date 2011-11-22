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
// site et numero de version de PHP_CONGES
// ne pas toucher ces variables SVP ;-)
$config_php_conges_version="1.1.1";
$config_url_site_web_php_conges="http://www.ced.univ-montp2.fr/php_conges";
// ne pas toucher ces variables SVP ;-)


/*************************************************************************************************/
// VARIABLES A RENSEIGNER :


//  CONFIG ACCES A LA DATABASE
//---------------------------------------
// MySql : 
$mysql_serveur="localhost" ;
$mysql_user="dbconges" ;
$mysql_pass="motdepasse";
$mysql_database= "db_conges" ;




/*************************************************************************************************/
//  CONFIG DES STYLES
//------------------------------
// NE PAS MODIFIER !!!
	// couleurs pour légende du calendrier 
	$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine 
	$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end 
	$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
	$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (congés acceptés par le responsable)
	$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demandés (non encore accordés par le responsable)
	$config_absence_autre_bgcolor="#D3FFB6"; // couleur de fond des jours d'absence pour mission, etc ...




/**************************************************************************************************************/
/**************************************************************************************************************/
//  ENVOI DE MAIL D'INFORMATION 
//
if(!isset($URL_ACCUEIL_CONGES)) { $URL_ACCUEIL_CONGES="";}

/***********************/
// message d'alerte au responsable en cas de nouvelle demande de conges
// sujet du message :
$config_mail_sujet_new_demande = "APPLI CONGES - Demande de congés";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_new_demande = " a solicité une demande de congés dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de saisie de nouveau conges par le responsable
// sujet du message :
$config_mail_sujet_valid_conges = "APPLI CONGES - Congés validé";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_valid_conges = " a enregistré/validé un congés pour vous dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de refus d'une demande de conges par le responsable
// sujet du message :
$config_mail_sujet_refus_conges = "APPLI CONGES - Congés refusé";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_refus_conges = " a refusé une demande de congés pour vous dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas d'annulation de conges par le responsable
// sujet du message :
$config_mail_sujet_annul_conges = "APPLI CONGES - Congés annulé";
// corps du message : (il y aura systématiquement le nom de la personne demandeuse au début du message)
$config_mail_contenu_annul_conges = " a annulé un de vos congés dans l'application de gestion des congès.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";


?>
