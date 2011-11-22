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
	// couleurs pour l�gende du calendrier 
	$config_semaine_bgcolor="#FFFFFF";       // couleur de fond des jours de semaine 
	$config_week_end_bgcolor="#BFBFBF";      // couleur de fond des jours de week end 
	$config_temps_partiel_bgcolor="#FFFFC4"; // couleur de fond des jours de temps partiel ou d'artt pour un user
	$config_conges_bgcolor="#DEDEDE";        // couleur de fond des jours de conges (cong�s accept�s par le responsable)
	$config_demande_conges_bgcolor="#E7C4C4";// couleur de fond des jours de conges demand�s (non encore accord�s par le responsable)
	$config_absence_autre_bgcolor="#D3FFB6"; // couleur de fond des jours d'absence pour mission, etc ...




/**************************************************************************************************************/
/**************************************************************************************************************/
//  ENVOI DE MAIL D'INFORMATION 
//
if(!isset($URL_ACCUEIL_CONGES)) { $URL_ACCUEIL_CONGES="";}

/***********************/
// message d'alerte au responsable en cas de nouvelle demande de conges
// sujet du message :
$config_mail_sujet_new_demande = "APPLI CONGES - Demande de cong�s";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_new_demande = " a solicit� une demande de cong�s dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de saisie de nouveau conges par le responsable
// sujet du message :
$config_mail_sujet_valid_conges = "APPLI CONGES - Cong�s valid�";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_valid_conges = " a enregistr�/valid� un cong�s pour vous dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas de refus d'une demande de conges par le responsable
// sujet du message :
$config_mail_sujet_refus_conges = "APPLI CONGES - Cong�s refus�";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_refus_conges = " a refus� une demande de cong�s pour vous dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";

/***********************/
// message d'alerte au user en cas d'annulation de conges par le responsable
// sujet du message :
$config_mail_sujet_annul_conges = "APPLI CONGES - Cong�s annul�";
// corps du message : (il y aura syst�matiquement le nom de la personne demandeuse au d�but du message)
$config_mail_contenu_annul_conges = " a annul� un de vos cong�s dans l'application de gestion des cong�s.

Merci de consulter l'application $URL_ACCUEIL_CONGES

-------------------------------------------------------------------------------------------------------
Ceci est un message automatique.";


?>
