<?php
/************************************************************************************************
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

// Fichier de Langue FR (FRANCAIS )
$LANG=array();
/*************************************************************************************************/
// VARIABLES A RENSEIGNER :



/***********************/
// MOIS ET JOURS
$LANG['janvier']	= "Janvier";
$LANG['fevrier']	= "F�vrier";
$LANG['mars']		= "Mars";
$LANG['avril']		= "Avril";
$LANG['mai']		= "Mai";
$LANG['juin']		= "Juin";
$LANG['juillet']	= "Juillet";
$LANG['aout']		= "Ao�t";
$LANG['septembre']	= "Septembre";
$LANG['octobre']	= "Octobre";
$LANG['novembre']	= "Novembre";
$LANG['decembre']	= "D�cembre";

$LANG['lundi']		= "lundi";
$LANG['mardi']		= "mardi";
$LANG['mercredi']	= "mercredi";
$LANG['jeudi']		= "jeudi";
$LANG['vendredi']	= "vendredi";
$LANG['samedi']		= "samedi";
$LANG['dimanche']	= "dimanche";

$LANG['lundi_short']		= "lun";
$LANG['mardi_short']		= "mar";
$LANG['mercredi_short']		= "mer";
$LANG['jeudi_short']		= "jeu";
$LANG['vendredi_short']		= "ven";
$LANG['samedi_short']		= "sam";
$LANG['dimanche_short']		= "dim";

$LANG['lundi_2c']		= "lu";
$LANG['mardi_2c']		= "ma";
$LANG['mercredi_2c']	= "me";
$LANG['jeudi_2c']		= "je";
$LANG['vendredi_2c']	= "ve";
$LANG['samedi_2c']		= "sa";
$LANG['dimanche_2c']	= "di";

$LANG['lundi_1c']		= "L";
$LANG['mardi_1c']		= "M";
$LANG['mercredi_1c']	= "M";
$LANG['jeudi_1c']		= "J";
$LANG['vendredi_1c']	= "V";
$LANG['samedi_1c']		= "S";
$LANG['dimanche_1c']	= "D";



/***********************/
// BOUTONS COMMUNS
$LANG['button_deconnect']	= "D�connexion";
$LANG['button_refresh']		= "Actualiser la Page";
$LANG['button_editions']	= "Editions Papier";
$LANG['button_admin_mode']	= "Mode Administrateur";
$LANG['button_calendar']	= "Afficher le Calendrier";



/***********************/
// FORMULAIRES 
$LANG['form_ok']			= "OK";
$LANG['form_submit']		= "Valider";
$LANG['form_cancel']		= "Abandonner";
$LANG['form_retour']		= "Retour";
$LANG['form_ajout']			= "Ajouter";
$LANG['form_supprim']		= "Supprimer";
$LANG['form_modif']			= "Modifier";
$LANG['form_annul']			= "Annuler";
$LANG['form_redo']			= "Recommencer";
$LANG['form_am']			= "matin";
$LANG['form_pm']			= "apr�s midi";
$LANG['form_day']			= "journ�e compl�te";
$LANG['form_close_window']	= "Fermer cette Fen�tre";
$LANG['form_save_modif']	= "Enregistrer les modifications";
$LANG['form_modif_ok']		= "Modifications enregistr�es avec succ�s !";
$LANG['form_modif_not_ok']	= "ERREUR ! Modifications NON enregistr�es !";
$LANG['form_valid_global']	= "Valider la saisie globale";
$LANG['form_valid_groupe']	= "Valider la saisie pour le Groupe";
$LANG['form_password']		= "Password";
$LANG['form_start']			= "Commencer";
$LANG['form_continuer']		= "Continuer";



/***********************/
// DIVERS
$LANG['divers_quotite']			= "quotit�";
$LANG['divers_quotite_maj_1']	= "Quotit�";
$LANG['divers_annee']			= "an";
$LANG['divers_annee_maj']		= "AN";
$LANG['divers_solde']			= "solde";
$LANG['divers_solde_maj']		= "SOLDE";
$LANG['divers_solde_maj_1']		= "Solde";
$LANG['divers_debut_maj']		= "DEBUT";
$LANG['divers_debut_maj_1']		= "D�but";
$LANG['divers_fin_maj']			= "FIN";
$LANG['divers_fin_maj_1']		= "Fin";
$LANG['divers_type']			= "type";
$LANG['divers_type_maj_1']		= "Type";
$LANG['divers_comment_maj_1']	= "Commentaire";
$LANG['divers_etat_maj_1']		= "Etat";
$LANG['divers_nb_jours_pris_maj_1']	= "nb Jours Pris";
$LANG['divers_nb_jours_maj_1']	= "nb Jours";
$LANG['divers_inconnu']			= "inconnu";
$LANG['divers_motif_refus']		= "motif du refus";
$LANG['divers_motif_annul']		= "motif de l'annulation";
$LANG['divers_refuse']			= "refus�";
$LANG['divers_annule']			= "annul�";
$LANG['divers_login']			= "login";
$LANG['divers_login_maj_1']		= "Login";
$LANG['divers_personne_maj_1']	= "Personne";
$LANG['divers_responsable_maj_1']	= "Responsable";
$LANG['divers_nom_maj']			= "NOM";
$LANG['divers_nom_maj_1']		= "Nom";
$LANG['divers_prenom_maj']		= "PRENOM";
$LANG['divers_prenom_maj_1']	= "Pr�nom";
$LANG['divers_accepter_maj_1']	= "Accepter";
$LANG['divers_refuser_maj_1']	= "Refuser";
$LANG['divers_fermer_maj_1']	= "Fermer";
$LANG['divers_am_short']		= "mat";
$LANG['divers_pm_short']		= "aprm";
$LANG['divers_conges']			= "cong�s";
$LANG['divers_conges_maj_1']	= "Cong�s";
$LANG['divers_absences']		= "absences";
$LANG['divers_absences_maj_1']	= "Absences";
$LANG['divers_nouvelle_absence']	= "Nouvelle Absence";
$LANG['divers_mois_precedent']			= "mois pr�c�dent";
$LANG['divers_mois_precedent_maj_1']	= "Mois Pr�c�dent";
$LANG['divers_mois_suivant']			= "mois suivant";
$LANG['divers_mois_suivant_maj_1']		= "Mois sSuivant";




/***********************/
// PARTIE UTILISATEUR
//divers
$LANG['user']				= "Utilisateur";

//onglets
$LANG['user_onglet_echange_abs']		= "Echange jour absence";
$LANG['user_onglet_demandes']			= "demandes en cours";
$LANG['user_onglet_historique_conges']	= "Historique des cong�s";
$LANG['user_onglet_historique_abs']		= "Historique autres absences";
$LANG['user_onglet_change_passwd']		= "Changer mot de passe";

//titres des pages
$LANG['user_echange_rtt']				= "Echange jour rtt,temps partiel / jour travaill�";
$LANG['user_etat_demandes']				= "Etat des demandes en cours";
$LANG['user_historique_conges']			= "Historique des cong�s";
$LANG['user_historique_abs']			= "Historique des absences pour mission, formation, etc ...";
$LANG['user_change_password']			= "Changer votre mot de passe";

//page etat des demandes
$LANG['user_demandes_aucune_demande']	= "Aucune demande en cours ...";

//page historique des conges
$LANG['user_conges_aucun_conges']		= "Aucun cong�s dans la base de donn�es ...";

//page historique des absences
$LANG['user_abs_aucune_abs']			= "Aucune absences dans la base de donn�es ...";
$LANG['user_abs_type']				= "Absence";

//page changer password
$LANG['user_passwd_saisie_1']		= "1iere saisie";
$LANG['user_passwd_saisie_2']		= "2eme saisie";
$LANG['user_passwd_error']			= "ERREUR ! les 2 saisies sont diff�rentes ou vides !!";


//page modification demande / absence
$LANG['user_modif_demande_titre']		= "Modification d'une demande/absence.";

//page suppression demande / absence
$LANG['user_suppr_demande_titre']		= "Suppression demande de conges .";





/***********************/
// PARTIE RESPONSABLE
//menu
$LANG['resp_menu_titre']					= "MODE RESPONSABLE :";
$LANG['resp_menu_button_retour_main']		= "Retour page Principale";
$LANG['resp_menu_button_traite_demande']	= "Traiter toutes les Demandes";
$LANG['resp_menu_button_affiche_user']		= "afficher personne";
$LANG['resp_menu_button_ajout_jours']		= "Ajout Jours Conges";
$LANG['resp_menu_button_mode_user']			= "Mode Utilisateur";
$LANG['resp_menu_button_mode_admin']		= "Mode Administrateur";

//page etat des conges des users
$LANG['resp_etat_users_afficher']	= "Afficher";
$LANG['resp_etat_users_imprim']		= "Edition Papier";
//page traite toutes les demandes
$LANG['resp_traite_demandes_titre']				= "Traitement des demandes de cong�s :";
$LANG['resp_traite_demandes_aucune_demande']	= "Aucune demande de cong�s en cours dans la base de donn�es ...";
$LANG['resp_traite_demandes_nb_jours']			= "nb Jours<br>Pris";
$LANG['resp_traite_demandes_attente']			= "Attente";
$LANG['resp_traite_demandes_motif_refus']		= "Motif<br>de refus";
//page ajout conges
$LANG['resp_ajout_conges_titre']				= "Ajout de cong�s :";
$LANG['resp_ajout_conges_nb_jours_ajout']		= "NB jours &agrave; ajouter";
$LANG['resp_ajout_conges_ajout_all']			= "Ajout global pour Tous :";
$LANG['resp_ajout_conges_nb_jours_all_1']		= "Nombre de jours de";
$LANG['resp_ajout_conges_nb_jours_all_2']		= "� ajouter � tous :";
$LANG['resp_ajout_conges_calcul_prop']			= "Calcul proportionnel � la quotit� de chaque personne :";
$LANG['resp_ajout_conges_oui']					= "OUI";
$LANG['resp_ajout_conges_calcul_prop_arondi']	= "le calcul proportionnel est arrondi au 1/2 le plus proche";
$LANG['resp_ajout_conges_ajout_groupe']			= "Ajout par Groupe : (ajout � tous les membres d'un groupe)";
$LANG['resp_ajout_conges_choix_groupe']			= "choix du groupe";
$LANG['resp_ajout_conges_nb_jours_groupe_1']	= "Nombre de jours de";
$LANG['resp_ajout_conges_nb_jours_groupe_2']	= "� ajouter au groupe :";
$LANG['resp_ajout_conges_comment_periode_user']	= "ajout jour";
$LANG['resp_ajout_conges_comment_periode_all']	= "ajout pour tous les personnels";
$LANG['resp_ajout_conges_comment_periode_groupe']	= "ajout pour le groupe";
//page traite user
$LANG['resp_traite_user_titre']				= "Traitement de :";
$LANG['resp_traite_user_new_conges']		= "Nouveau Cong�s/Absence :";
$LANG['resp_traite_user_etat_demandes']		= "Etat des demandes :";
$LANG['resp_traite_user_etat_conges']		= "Etat des cong�s :";
$LANG['resp_traite_user_aucune_demande']	= "Aucune demande de cong�s pour cette personne dans la base de donn�es ...";
$LANG['resp_traite_user_motif_refus']		= "motif refus";
$LANG['resp_traite_user_aucun_conges']		= "Aucun cong�s pour cette personne dans la base de donn�es ...";
$LANG['resp_traite_user_motif_possible']	= "motif refus ou annulation �ventuel";
$LANG['resp_traite_user_annul']				= "Annuler";
$LANG['resp_traite_user_motif_annul']		= "motif annulation";
$LANG['resp_traite_user_motif']				= "motif";
$LANG['resp_traite_user_valeurs_not_ok']	= "ERREUR ! Les valeurs saisies sont invalides ou manquantes  !!!";





/***********************/
// PARTIE ADMINISTRATEUR
//divers
$LANG['admin_titre']					= "Administration de la DataBase : ";
$LANG['admin_button_close_window_1']	= "Fermeture du mode Administrateur";
$LANG['admin_button_config_1']			= "Configuration de php_conges";
$LANG['admin_button_config_2']			= "Configuration";
$LANG['admin_button_config_abs_1']		= "Configuration des types d'absence g�r�es par php_conges";
$LANG['admin_button_config_abs_2']		= "Config Absences";
$LANG['admin_button_jours_chomes_1']	= "saisie des jours ch�m�s";
$LANG['admin_button_jours_chomes_2']	= "saisie des jours ch�m�s";
$LANG['admin_button_save_db_1']			= "Sauvegarde/Restauration Database";
$LANG['admin_button_save_db_2']			= "Sauvegarde/Restauration Database";
//
$LANG['admin_onglet_gestion_user']		= "Gestion des Utilisateurs";
$LANG['admin_onglet_add_user']			= "Ajout d'un Utilisateur";
$LANG['admin_onglet_gestion_groupe']	= "Gestion des Groupes";
$LANG['admin_onglet_groupe_user']		= "Gestion Groupes <-> Utilisateurs";
$LANG['admin_onglet_user_groupe']		= "Gestion Utilisateurs <-> Groupes";
$LANG['admin_onglet_groupe_resp']		= "Gestion Groupes <-> Responsables";
$LANG['admin_onglet_resp_groupe']		= "Gestion Responsables <-> Groupes";
//
$LANG['admin_verif_param_invalides']	= "ATTENTION : certain champs saisis ne sont pas valides ......";
$LANG['admin_verif_login_exist']		= "ATTENTION : login d�j� utilis�, veuillez en changer ......";
$LANG['admin_verif_bad_mail']			= "ATTENTION : adresse mail �ronn�e ......";
$LANG['admin_verif_groupe_invalide']	= "ATTENTION : nom de groupe d�j� utilis�, veuillez en changer ......";
// page gestion utilisateurs
$LANG['admin_users_titre']				= "Etat des Utilisateurs";
$LANG['admin_users_is_resp']			= "is_resp";
$LANG['admin_users_resp_login']			= "resp_login";
$LANG['admin_users_is_admin']			= "is_admin";
$LANG['admin_users_see_all']			= "see_all";
$LANG['admin_users_mail']				= "email";
$LANG['admin_users_password_1']			= "password1";
$LANG['admin_users_password_2']			= "password2";
// page ajout utilisateur
$LANG['admin_new_users_titre']			= "Nouvel Utilisateur :";
$LANG['admin_new_users_is_resp']		= "est_responsable";
$LANG['admin_new_users_is_admin']		= "est_administrateur";
$LANG['admin_new_users_see_all']		= "voir_tous";
$LANG['admin_new_users_password']		= "password";
$LANG['admin_new_users_nb_par_an']		= "nb / an";
// page ajout utilisateur
$LANG['admin_groupes_groupe']			= "Groupe";
$LANG['admin_groupes_libelle']			= "libell�";
$LANG['admin_groupes_new_groupe']		= "Nouveau Groupe :";
// page gestion groupes
$LANG['admin_gestion_groupe_etat']		= "Etat des Groupes";
//
$LANG['admin_aff_choix_groupe_titre']	= "Choix d'un Groupe";
$LANG['admin_aff_choix_user_titre']		= "Choix d'un Utilisateur";
$LANG['admin_aff_choix_resp_titre']		= "Choix d'un Responsable";
// page gestion groupe <-> users
$LANG['admin_gestion_groupe_users_membres']	= "Membres du Groupe";
$LANG['admin_gestion_groupe_users_group_of_user']	= "Groupes auxquels appartient";
// page gestion groupe <-> users
$LANG['admin_gestion_groupe_resp_groupes']		= "Groupes du Responsable";
$LANG['admin_gestion_groupe_resp_responsables']	= "Responsables du Groupe";
// page change password user
$LANG['admin_chg_passwd_titre']		= "Modification Password utilisateur";
// page admin_suppr_user
$LANG['admin_suppr_user_titre']		= "Suppression Utilisateur";
// page admin_modif_user
$LANG['admin_modif_user_titre']		= "Modification utilisateur";
$LANG['admin_modif_nb_jours_an']	= "nb jours / an";
// grille saisie temps partiel et RTT
$LANG['admin_temps_partiel_titre']			= "saisie des jours d'abscence pour ARTT ou temps partiel";
$LANG['admin_temps_partiel_sem_impaires']	= "semaines Impaires";
$LANG['admin_temps_partiel_sem_paires']		= "semaines Paires";
$LANG['admin_temps_partiel_am']				= "matin";
$LANG['admin_temps_partiel_pm']				= "apres-midi";
$LANG['admin_temps_partiel_date_valid']		= "Date de d�but de validit� de cette grille";
// page admin_suppr_groupe
$LANG['admin_suppr_groupe_titre']		= "Suppression de Groupe.";
// page admin_suppr_groupe
$LANG['admin_modif_groupe_titre']		= "Modification de Groupe.";
// page admin_sauve_restaure_db
$LANG['admin_sauve_db_titre']			= "Sauvegarde / Restauration de la Base de donn�es";
$LANG['admin_sauve_db_choisissez']		= "Choisissez";
$LANG['admin_sauve_db_sauve']			= "Sauvegarder";
$LANG['admin_sauve_db_restaure']		= "Restaurer";
$LANG['admin_sauve_db_do_sauve']		= "D�marrer la sauvegarde";
$LANG['admin_sauve_db_options']			= "Options de Sauvegarde";
$LANG['admin_sauve_db_complete']		= "Sauvegarde compl�te";
$LANG['admin_sauve_db_data_only']		= "Sauvegarde des donn�es seules";
$LANG['admin_sauve_db_save_ok']			= "Sauvegarde effectu�e";
$LANG['admin_sauve_db_restaure']		= "Restauration de la base de donn�es";
$LANG['admin_sauve_db_file_to_restore']	= "Fichier � restaurer";
$LANG['admin_sauve_db_warning']			= "ATTENTION : toutes les donn�es de la database php_conges vont �tre �cras�es avant la restauration";
$LANG['admin_sauve_db_do_restaure']		= "Lancer la Restauration";
$LANG['admin_sauve_db_bad_file']		= "Fichier indiqu� inexistant";
$LANG['admin_sauve_db_restaure_ok']		= "Restauration effectu�e avec succ�s";
// page admin_jours_chomes
$LANG['admin_jours_chomes_titre']				= "Saisie des jours ch�m�s";
$LANG['admin_jours_chomes_annee_precedente']	= "ann�e pr�c�dente";
$LANG['admin_jours_chomes_annee_suivante']		= "ann�e suivante";
$LANG['admin_jours_chomes_confirm']				= "Confirmer cette Saisie";



/***********************/
// EDITIONS PAPIER
$LANG['editions_titre']			= "Editions Conges";
$LANG['editions_last_edition']	= "Prochaine Edition";
$LANG['editions_aucun_conges']	= "Aucun cong�s � �diter dans la base de donn�es ...";
$LANG['editions_lance_edition']		= "Lancer l'�dition";
$LANG['editions_pdf_edition']		= "Edition en PDF";
$LANG['editions_hitorique_edit']		= "Historique des �ditions";
$LANG['editions_aucun_hitorique']	= "Aucune �dition enregistr�e pour cet utilisateur ...";
$LANG['editions_numero']			= "Numero";
$LANG['editions_date']				= "Date";
$LANG['editions_edit_again']		= "Editer � nouveau";
$LANG['editions_edit_again_pdf']	= "Editer � nouveau en PDF";
//
$LANG['editions_bilan_au']			= "bilan au";
$LANG['editions_historique']		= "Historique";
$LANG['editions_soldes_precedents_inconnus']	= "soldes pr�c�dents inconnus";
$LANG['editions_solde_precedent']	= "solde pr�c�dent";
$LANG['editions_nouveau_solde']		= "nouveau solde";
$LANG['editions_signature_1']		= "Signature du titulaire";
$LANG['editions_signature_2']		= "Signature du responsable";
$LANG['editions_cachet_etab']		= "et cachet de l'�tablissement";
$LANG['editions_jours_an']			= "jours / an";



/***********************/
// SAISIE CONGES
$LANG['saisie_conges_compter_jours']		= "Compter les jours";
$LANG['saisie_conges_nb_jours']				= "NB_Jours_Pris";



/***********************/
// SAISIE ECHANGE ABSENCE
$LANG['saisie_echange_titre_calendrier_1']		= "Jour d'absence ordinaire";
$LANG['saisie_echange_titre_calendrier_2']		= "Jour d'absence souhait�";



/***********************/
// CALENDRIER
$LANG['calendrier_titre']			= "CALENDRIER des CONGES";
$LANG['calendrier_imprimable']		= "version imprimable";
$LANG['calendrier_jour_precedent']	= "Jour Precedent";
$LANG['calendrier_jour_suivant']	= "Jour Suivant";
$LANG['calendrier_legende_we']			= "week-end ou jour f�ri�";
$LANG['calendrier_legende_conges']		= "cong�s pris ou a prendre";
$LANG['calendrier_legende_demande']		= "cong�s demand� (non encore accord�)";
$LANG['calendrier_legende_part_time']	= "absence hebdomadaire (temps partiel , RTT)";
$LANG['calendrier_legende_abs']			= "absence autre (mission, formation, maladie, ...)";



/***********************/
// CALCUL NB JOURS
$LANG['calcul_nb_jours_nb_jours']	= "Nombre de jours � prendre :";
$LANG['calcul_nb_jours_reportez']	= "reportez ce nombre dans la case";
$LANG['calcul_nb_jours_form']		= "du formulaire";



/***********************/
// ERREUR
$LANG['erreur_user']			= "Impossible d'identifier le user";
$LANG['erreur_login_password']	= "couple login/mot de passe non valide ou login absent";
$LANG['erreur_session']			= "session invalide ou expir�e";



/***********************/
// INCLUDE_PHP
$LANG['mysql_srv_connect_failed']	= "Impossible de se connecter au serveur ";
$LANG['mysql_db_connect_failed']		= "Impossible de se connecter � la base de donn�es";

// page d'authentification / login screen
$LANG['cookies_obligatoires']		= "Il est n&eacute;cessaire que votre navigateur accepte les <b>cookies</b> pour pouvoir vous connecter &agrave; PHP_CONGES.";
$LANG['javascript_obligatoires']		= "Il est conseill&eacute; que votre navigateur accepte le <b>Javascript</b> pour utiliser PHP_CONGES.";
$LANG['login_passwd_incorrect']		= "ERREUR : Nom d'utilisateur et/ou mot de passe incorrect !!!";
$LANG['login_non_connu']				= "ERREUR : Utilisateur non enregistr� pour la gestion des cong�s !!!";
//
$LANG['login_fieldset']			= "Identification";
$LANG['password']					= "Mot de Passe";
$LANG['msie_alert']				= "Remarque : Certains affichages peuvent ne pas �tre pris en charge par Microsoft IE. Utilisez plut�t Mozilla Firefox.";


// verif saisie
$LANG['verif_saisie_erreur_valeur_manque']		= "ERREUR : mauvaise saisie : valeurs <b>manquantes !!!</b>";
$LANG['verif_saisie_erreur_nb_jours_bad']		= "ERREUR : mauvaise saisie : <b>le nombre de jours est invalide</b>";
$LANG['verif_saisie_erreur_fin_avant_debut']	= "ERREUR : mauvaise saisie : <b>la date de fin est anterieure � la date de d�but !!!</b>";
$LANG['verif_saisie_erreur_debut_apres_fin']	= "ERREUR : mauvaise saisie : <b>la date de d�but est post�rieure � la date de fin !!!</b>";
$LANG['verif_saisie_erreur_nb_bad']				= "ERREUR : mauvaise saisie : <b>le nombre saisi est invalide</b>";


/***********************/
// CONFIG TYPES ABSENCES
$LANG['config_abs_titre']				= "Configuration des types d'absence g�r�es par PHP_CONGES";
$LANG['config_abs_comment_conges']		= "Les types d'absences list�s ici sont des cong�s divers, d�compt�s chacuns sur des comptes s�par�s." ;
$LANG['config_abs_comment_absences']	= "Les types d'absences list�s ici ne sont pas d�compt�s (ce sont des absences autoris�es)." ;
$LANG['config_abs_libelle']				= "libell�";
$LANG['config_abs_libelle_short']		= "libell� court";
$LANG['config_abs_add_type_abs']			= "ajouter un type d'absence :";
$LANG['config_abs_add_type_abs_comment']	= "Saisissez le type d'absence que vous voulez ajouter :";
$LANG['config_abs_saisie_not_ok']			= "saisie incorrecte :";
$LANG['config_abs_bad_caracteres']			= "les caract�res suivants sont interdits:";
$LANG['config_abs_champs_vides']			= "des champs sont vides !";
$LANG['config_abs_suppr_impossible']		= "Suppression IMPOSSIBLE !";
$LANG['config_abs_already_used']			= "Des cong�s/absences de ce type sont en cours !";
$LANG['config_abs_confirm_suppr_of']		= "Veuillez confirmer la supression de";



/***************************/
// CONFIGURATION PHP_CONGES
$LANG['config_appli_titre_1']		= "Configuration de l'Application PHP_CONGES";
$LANG['config_appli_titre_2']		= "Configuration de php_conges";
//groupes de param�tres
$LANG['00_php_conges']				= "00 php_conges";
$LANG['01_Serveur Web']				= "01 Serveur Web";
$LANG["02_PAGE D'AUTENTIFICATION"]	= "02 PAGE D'AUTENTIFICATION";
$LANG['03_TITRES']					= "03 TITRES";
$LANG['04_Authentification']		= "04 Authentification";
$LANG['05_Utilisateur']				= "05 Utilisateur";
$LANG['06_Responsable']				= "06 Responsable";
$LANG['07_Administrateur']			= "07 Administrateur";
$LANG['08_Mail']					= "08 Mail";
$LANG['09_jours ouvrables']			= "09 jours ouvrables";
$LANG['10_Gestion par groupes']		= "10 Gestion par groupes";
$LANG['11_Editions papier']			= "11 Editions papier";
$LANG["12_Fonctionnement de l'Etablissement"]	= " 12 Fonctionnement de l'Etablissement";
$LANG['13_Divers']					= "13 Divers";
$LANG['14_Pr�sentation']			= "14Pr�sentation";
$LANG['15_Modules Externes']		= "15 Modules Externes";
// parametres de config
$LANG['config_comment_installed_version']	= "num�ro de version install�e";
$LANG['config_comment_lang']				= "// LANGUE / LANGUAGE<br>\n//---------------------------<br>\n// fr = fran�ais<br>\n// test = seulement pour les developpeurs de php_conges (only for php_conges developpers)";
$LANG['config_comment_URL_ACCUEIL_CONGES']	= "// URL DE BASE DE VOTRE INSTALLATION DE PHP_CONGES<br>\n//-------------------------------------------------<br>\n// URL de base de php_conges sur votre serveur (ce que vous devez taper pour obtenir la page d'authentification.<br>\n// (PAS termin� par un / et sans le index.php � la fin)<br>\n// URL_ACCUEIL_CONGES = \"http://monserveurweb.mondomaine/php_conges\"";
$LANG['config_comment_img_login']			= "// IMAGE DE LA PAGE DE LOGIN<br>\n//---------------------------<br>\n// image qui apparait en haut de la page d'authentification de php_conges";
$LANG['config_comment_texte_img_login']		= "// TEXTE DE L'IMAGE<br>\n//-------------------<br>\n// texte de l'image";
$LANG['config_comment_lien_img_login']		= "// LIEN DE L'IMAGE<br>\n//------------------<br>\n// URL o� renvoit l'image de la page de login";
$LANG['config_comment_titre_page_accueil']	= "Titre de la page d'accueil de php_conges";
$LANG['config_comment_titre_calendrier']	= "Titre de la page calendrier de php_conges";
$LANG['config_comment_titre_user_index']	= "Titre des pages Utilisateur (sera suivi du login de l'utilisateur)";
$LANG['config_comment_titre_resp_index']	= "Titre des pages Responsable";
$LANG['config_comment_titre_admin_index']	= "Titre des pages Administrateur";
$LANG['config_comment_auth']				= "// Autentification :<br>\n//---------------------<br>\n// si = FALSE : pas d'authetification au d�marrage , il faut passer le parametre login � l'appel de php_conges<br>\n// si = TRUE  : la page d'autentification apparait � l'appel de php_conges (TRUE est la valeur par defaut)";
$LANG['config_comment_how_to_connect_user']	= "// Comment v�rifier le login et mot de passe des utilisateurs au d�marrage :<br>\n//--------------------------------------------------------------------------<br>\n// si � \"dbconges\" : l'authentification des user se fait dans la table users de la database db_conges<br>\n// si � \"ldap\"     : l'authentification des user se fait dans un annuaire LDAP que l'on va int�rroger (cf config_ldap.php)<br>\n// si � \"CAS\"      : l'authentification des user se fait sur un serveur CAS que l'on va int�rroger (cf config_CAS.php)<br>\n// attention : toute autre valeur que \"dbconges\" ou \"ldap\" ou \"CAS\" entrainera une �rreur !!!";
$LANG['config_comment_export_users_from_ldap']	= "// Export des Users depuis LDAP :<br>\n//--------------------------------<br>\n// si = FALSE : les users sont cr��s \"� la main\" directement dans php_conges (FALSE est la valeur par defaut)<br>\n// si = TRUE  : les user sont import�s du serveur LDAP (grace� une iste d�roulante) (cf config_ldap.php)";
$LANG['config_comment_user_saisie_demande']		= "//  DEMANDES DE CONGES<br>\n//---------------------------------------<br>\n// si � FALSE : pas de saisie de demande par l'utilisateur, pas de gestion des demandes par le responsable<br>\n// si � TRUE : saisie de demande par l'utilisateur, et gestion des demandes par le responsable (TRUE est la valeur par defaut)";
$LANG['config_comment_user_affiche_calendrier']	= "//  AFFICHAGE DU BOUTON DE CALENDRIER POUR L'UTILISATEUR<br>\n//--------------------------------------------------------------------------------------<br>\n// si � FALSE : les utilisateurs n'ont pas la possibilit� d'afficher le calendrier des cong�s<br>\n// si � TRUE : les utilisateurs ont la possibilit� d'afficher le calendrier des cong�s (TRUE est la valeur par defaut)";
$LANG['config_comment_user_saisie_mission']		= "//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC .... PAR L'UTILISATEUR<br>\n//--------------------------------------------------------------------------------------<br>\n// ( les absences de ce type n'enl�vent pas de jours de cong�s ! )<br>\n// si � FALSE : pas de saisie par l'utilisateur des absences pour mission, formation, congr�s, etc ....<br>\n// si � TRUE : saisie par l'utilisateur des absences pour mission, formation, congr�s, etc .... (TRUE est la valeur par defaut)";
$LANG['config_comment_user_ch_passwd']			= "//  CHANGER SON PASSWORD<br>\n//---------------------------------------<br>\n// si � FALSE : l'utilisateur ne peut pas changer son password<br>\n// si � TRUE : l'utilisateur peut changer son password (TRUE est la valeur par defaut)";
$LANG['config_comment_responsable_virtuel']		= "//  RESPONSABLE GENERIQUE VIRTUEL OU NON<br>\n//-------------------------------------------<br>\n// si � FALSE : le responsable qui traite les cong�s des personnels est une personne reelle (utilisateur de php_conges) (FALSE est la valeur par defaut)<br>\n// si � TRUE : le responsable qui traite les cong�s des personnels est un utilisateur generique virtuel (login=conges)";
$LANG['config_comment_resp_affiche_calendrier']	= "//  AFFICHAGE DU BOUTON DE CALENDRIER POUR LE RESPONSABLE<br>\n//--------------------------------------------------------------------------------------<br>\n// si � FALSE : les responsables n'ont pas la possibilit� d'afficher le calendrier des cong�s<br>\n// si � TRUE : les responsables ont la possibilit� d'afficher le calendrier des cong�s (TRUE est la valeur par defaut)";
$LANG['config_comment_resp_saisie_mission']		= "//  SAISIE  DES ABSENCES POUR MISSIONS, FORMATIONS, CONGRES, ETC .... PAR LE RESPONSABLE<br>\n//---------------------------------------------------------------------------------------<br>\n// ( les absences de ce type n'enl�vent pas de jours de cong�s ! )<br>\n// si � FALSE : pas de saisie par le responsable des absences pour mission, formation, congr�s, etc ....(FALSE est la valeur par defaut)<br>\n// si � TRUE : saisie par le responsable des absences pour mission, formation, congr�s, etc ....";
$LANG['config_comment_resp_vertical_menu']		= "//  CONFIG  DU MENU DU RESPONSABLE<br>\n//---------------------------------------<br>\n// si � TRUE : dans la fenetre responsable, le menu est vertical (� gauche) (TRUE est la valeur par defaut)<br>\n// si � FALSE : dans la fenetre responsable, le menu est horizontal (en haut)";
$LANG['config_comment_admin_see_all']			= "//  CONFIG  DU MODE ADMINISTRATEUR<br>\n//---------------------------------------<br>\n// si � FALSE : l'admin ne gere que les users dont il est responsable (FALSE est la valeur par defaut)<br>\n// si � TRUE : l'admin gere tous les users";
$LANG['config_comment_admin_change_passwd']		= "//  CHANGER LE PASSWORD D'UN UTILSATEUR<br>\n//-----------------------------------------<br>\n// si � FALSE : l'administrateur ne peut pas changer le password des utilisateurs<br>\n// si � TRUE : l'administrateur peut changer le password des utilisateurs (TRUE est la valeur par defaut)";
$LANG['config_comment_affiche_bouton_config_pour_admin']			= "// ACCES A LA CONFIG DE L'APPLI POUR LES ADMINS<br>\n//-------------------------------------------------------<br>\n// si � FALSE : le bouton d'acces � la configuration de php_conges n'apparait pas sur la page administrateur (FALSE est la valeur par defaut)<br>\n// si � TRUE : le bouton d'acces � la configuration de php_conges apparait sur la page administrateur";
$LANG['config_comment_affiche_bouton_config_absence_pour_admin']	= "// ACCES A LA CONFIG DES TYPES D'ABSENCES DE L'APPLI POUR LES ADMINS<br>\n//---------------------------------------------------------------------<br>\n// si � FALSE : le bouton d'acces � la configuration des types d'absences g�r�es par php_conges n'apparait pas sur la page administrateur (FALSE est la valeur par defaut)<br>\n// si � TRUE : le bouton d'acces � la configuration des types d'absences g�r�es par php_conges apparait sur la page administrateur";
$LANG['config_comment_mail_new_demande_alerte_resp']	= "// ENVOI DE MAIL AU RESPONSABLE POUR UNE NOUVELLE DEMANDE :<br>\n//----------------------------------------------------------<br>\n// si � FALSE : le responsable ne re�oit pas de mail lors d'une nouvelle demande de cong�s par un utilisateur (FALSE est la valeur par defaut)<br>\n// si � TRUE : le responsable re�oit un mail d'avertissement � chaque nouvelle demande de cong�s d'un utilisateur<br>\n// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)";
$LANG['config_comment_mail_valid_conges_alerte_user']	= "// ENVOI DE MAIL AU USER POUR UN NOUVEAU CONGES SAISI OU VALIDE :<br>\n//----------------------------------------------------------------<br>\n// si � FALSE : le user ne re�oit pas de mail lorsque le responsable lui saisi ou accepte un nouveau conges (FALSE est la valeur par defaut)<br>\n// si � TRUE : le user re�oit un mail d'avertissement � chaque que le responsable saisi un nouveau cong�s ou accepte une demande pour lui<br>\n// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)";
$LANG['config_comment_mail_refus_conges_alerte_user']	= "// ENVOI DE MAIL AU USER POUR LE REFUS D'UNE DEMANDE DE CONGES :<br>\n//----------------------------------------------------------------<br>\n// si � FALSE : le user ne re�oit pas de mail lorsque le responsable refuse une de ses demandes de conges (FALSE est la valeur par defaut)<br>\n// si � TRUE : le user re�oit un mail d'avertissement � chaque que le responsable refuse une de ses demandes de cong�s <br>\n// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)";
$LANG['config_comment_mail_annul_conges_alerte_user']	= "// ENVOI DE MAIL AU USER POUR L'ANNULATION PAR LE RESP D'UN CONGES DEJA VALIDE :<br>\n//---------------------------------------------------------------------------------<br>\n// si � FALSE : le user ne re�oit pas de mail lorsque le responsable lui annule un conges (FALSE est la valeur par defaut)<br>\n// si � TRUE : le user re�oit un mail d'avertissement � chaque que le responsable annule un de ses cong�s<br>\n// (ATTENTION : vous pouvez personaliser le sujet et le corps du mail d'alerte plus bas dans ce fichier)";
$LANG['config_comment_serveur_smtp']					= "//  SERVEUR SMTP A UTILSER<br>\n//---------------------------------------<br>\n// adresse ip  ou  nom du serveur smpt � utiliser pour envoyer les mails<br>\n// Si vous ne ma�triser pas le serveur SMTP ou si, � l'utilisation, vous avez une �rreur de connexion au serveur, laissez cette variable vide (\"\")";
$LANG['config_comment_where_to_find_user_email']		= "//  OU TROUVER LES ADRESSES MAIL DES UTILISATEURS<br>\n//-------------------------------------------------<br>\n// plusieurs possibilit� pour retrouver les adresses mail des users :<br>\n// si � \"dbconges\" : le mail des user se trouve dans la table users de la database db_conges<br>\n// si � \"ldap\"     : le mail des user se trouve dans un annuaire LDAP que l'on va int�rroger (cf fichier config_ldap.php)<br>\n// ATTENTION : toute autre valeur que \"dbconges\" ou \"ldap\" entrainera une �rreur !!!";
$LANG['config_comment_samedi_travail']		= "//  GESTION DES SAMEDI COMME TRAVAILLES OU NON<br>\n//--------------------------------------------------------------------------------------<br>\n// on d�finit ici si les samedis peuvent �tre travaill�s ou pas.<br>\n// si � TRUE : le jour consid�r� est travaill� ....<br>\n// si � FALSE : le jour consid�r� n'est pas travaill� (weeekend).... (FALSE est la valeur par defaut)";
$LANG['config_comment_dimanche_travail']	= "//  GESTION DES DIMANCHES COMME TRAVAILLES OU NON<br>\n//--------------------------------------------------------------------------------------<br>\n// on d�finit ici si les dimanches peuvent �tre travaill�s ou pas.<br>\n// si � TRUE : le jour consid�r� est travaill� ....<br>\n// si � FALSE : le jour consid�r� n'est pas travaill� (weeekend).... (FALSE est la valeur par defaut)";
$LANG['config_comment_gestion_groupes']		= "//  GESTION DES GROUPES D'UTILISATEURS<br>\n//--------------------------------------<br>\n// on d�finit ici si l'on veut pouvoir g�rer les utilisateurs par groupe ou pas.<br>\n// si � TRUE : les groupes d'utilisateurs sont g�r�s dans l'application ....<br>\n// si � FALSE : les groupes d'utilisateurs ne sont PAS g�r�s dans l'application .... (FALSE est la valeur par defaut)";
$LANG['config_comment_affiche_groupe_in_calendrier']	= "//  AFFICHAGE DU CALENDRIER : tous les utilisateurs ou les utilisateurs d'un groupe seulement<br>\n//--------------------------------------------------------------------------------------------<br>\n// si � FALSE : tous les personnes apparaissent sur le calendrier des cong�s (FALSE est la valeur par defaut)<br>\n// si � TRUE : seuls les personnes du m�me  groupe que l'utilisateur apparaissent sur le calendrier des cong�s";
$LANG['config_comment_editions_papier']				= "//  EDITIONS PAPIER<br>\n//--------------------------------------<br>\n// on d�finit ici si le responsable peut g�n�rer des �tats papier des cong�s d'un user.<br>\n// si � TRUE : les �ditions papier sont disponibles ....(TRUE est la valeur par defaut)<br>\n// si � FALSE : les �ditions papier ne sont pas disponibles dans l'application ....";
$LANG['config_comment_texte_haut_edition_papier']	= "//  Texte en haut des EDITIONS PAPIER<br>\n//--------------------------------------<br>\n// on d�finit ici le texte �v�ntuel qui figurera en haut de page des �tats papier des cong�s d'un user.";
$LANG['config_comment_texte_bas_edition_papier']	= "//  Texte au bas des EDITIONS PAPIER<br>\n//--------------------------------------<br>\n// on d�finit ici le texte �v�ntuel qui figurera en bas de page des �tats papier des cong�s d'un user.";
$LANG['config_comment_user_echange_rtt']			= "//  ECHANGE RTT OU TEMPS PARTIEL AUTORIS� POUR LES UTILISATEURS<br>\n//---------------------------------------------------------------------------------------<br>\n// on autorise ou non l'utilisateur � inverser ponctuellement une jour travaill� et un jour d'absence (de rtt ou temps partiel)<br>\n// si � FALSE : pas d'�change autoris� pour l'utilisateur (FALSE est la valeur par defaut)<br>\n// si � TRUE : �change autoris� pour l'utilisateur";
$LANG['config_comment_affiche_bouton_calcul_nb_jours_pris']	= "//  BOUTON DE CALCUL DU NB DE JOURS PRIS<br>\n//--------------------------------------------------------------------------------------<br>\n// si � FALSE : on n'affiche pas le bouton du calcul du nb de jours pris lors de la saisie d'une nouvelle abscence<br>\n// si � TRUE : affiche le bouton du calcul du nb de jours pris lors de la saisie d'une nouvelle abscence (TRUE est la valeur par defaut)<br>\n// ATTENTION : si est � TRUE : les jours chaum�s doivent �tre saisis (voir le module d'administration)";
$LANG['config_comment_rempli_auto_champ_nb_jours_pris']		= "//  REMPLISSAGE AUTOMATIQUE DU CHAMP LORS DE L'APPEL AU CALCUL DU NB DE JOURS PRIS<br>\n//--------------------------------------------------------------------------------------<br>\n// si � FALSE : l'appel au bouton de calcul du nb de jours pris ne rempli pas automatiquement le champ du formulaire (saisi � la main)<br>\n// si � TRUE : l'appel au bouton de calcul du nb de jours pris rempli automatiquement le champ du formulaire (TRUE est la valeur par defaut)";
$LANG['config_comment_duree_session']	= "// Dur�e max d'inactivit� d'une session avant expiration (en secondes)";
$LANG['config_comment_verif_droits']	= "// V�rification des Droits d'acc�s :<br>\n//------------------------------------<br>\n// mettre a TRUE Pour g�rer les droits d'acc�s aux pages (est a FALSE par defaut)<br>\n/* parametre propre � certains environnements d'install seulement !!!...... */";
$LANG['config_comment_stylesheet_file']	= "// FEUILLE DE STYLE<br>\n//--------------------------<br>\n// nom du fichier de la feuille de style � utiliser (avec chemin relatif depuis la racine de php_conges)";
$LANG['config_comment_bgcolor']			= "// couleur de fond des pages";
$LANG['config_comment_bgimage']			= "// image de fond des pages (PAS de / au d�but !!)";
$LANG['config_comment_light_grey_bgcolor']					= "// couleurs diverses (gris clair)";
$LANG['config_comment_php_conges_fpdf_include_path']		= "// CHEMIN VERS LE REPERTOIRE DE fpdf<br>\n//-------------------------------------------------------<br>\n// On d�fini ici le chemin pour acc�der au r�pertoire de la librairie PHP \"fpdf\".<br>\n// Le chemin doit etre relatif depuis la racine de l'application php_conges.";
$LANG['config_comment_php_conges_phpmailer_include_path']	= "// CHEMIN VERS LE REPERTOIRE DE phpmailer<br>\n//-------------------------------------------------------<br>\n// On d�fini ici le chemin pour acc�der au r�pertoire de la librairie PHP \"phpmailer\".<br>\n// Le chemin doit etre relatif depuis la racine de l'application php_conges.";
$LANG['config_comment_php_conges_cas_include_path']			= "// CHEMIN VERS LE REPERTOIRE DE cas<br>\n//-------------------------------------------------------<br>\n// On d�fini ici le chemin pour acc�der au r�pertoire de la librairie PHP \"CAS\".<br>\n// Le chemin doit etre relatif depuis la racine de l'application php_conges.";
$LANG['config_comment_php_conges_authldap_include_path']	= "// CHEMIN VERS LE fichier authLDAP.php<br>\n//-------------------------------------------------------<br>\n// On d�fini ici le chemin pour acc�der au r�pertoire de la librairie PHP \"authLDAP.php\".<br>\n// Le chemin doit etre relatif depuis la racine de l'application php_conges.";



/***************************/
// INSTALLATION PHP_CONGES
//page index
$LANG['install_le_fichier']		= "Le fichier";
$LANG['install_bad_fichier']	= "est introuvable dans le r�pertoire racine du nouveau php_conges, ou n'a pas des droits en lecture suffisant";
$LANG['install_read_the_file']	= "reportez vous au fichier";
$LANG['install_reload_page']	= "puis rechargez cette page";
$LANG['install_db_inaccessible']		= "la database n'est pas accessible";
$LANG['install_verifiez_param_file']	= "Veuillez v�rifier les param�tres du fichier";
$LANG['install_verifiez_priv_mysql']	= "Assurez vous que la database, l'utilisateur et les privil�ges MySql ont bien �t� cr��s.";
$LANG['install_install_phpconges']		= "Installation de php_conges";
$LANG['install_index_titre']			= "Application PHP_CONGES";
$LANG['install_no_prev_version_found']	= "Aucune version ant�rieure n'a pu �tre d�termine�";
$LANG['install_indiquez']				= "Veuillez indiquer  s'il s'agit";
$LANG['install_nouvelle_install']		= "d'une Nouvelle Installation";
$LANG['install_mise_a_jour']			= "d'une Mise � Jour";
$LANG['install_indiquez_pre_version']	= "veuillez indiquer la version d�j� install�e";
$LANG['install_installed_version']		= "version d�j� install�e";
$LANG['install_configuration']			= "Configuration";
$LANG['install_config_appli']			= "configurer l'application";
$LANG['install_config_types_abs']		= "configurer les types de cong�s � g�rer";
//page install
$LANG['install_install_titre']			= "Installation de l'application PHP_CONGES";
$LANG['install_impossible_sur_db']		= "impossible sur la database";
$LANG['install_verif_droits_mysql']		= "verifier les droits mysql de";
$LANG['install_puis']					= "puis";
$LANG['install_ok']						= "Installation effectu�e avec succ�s";
$LANG['install_vous_pouvez_maintenant']	= "Vous pouvez maintenant";
$LANG['install_acceder_appli']			= "acc�der � l'application";
//page mise_a_jour
$LANG['install_version_non_choisie']	= "la version � mettre � jour n'a pas �t� choisie";
$LANG['install_maj_titre_1']			= "Mise a jour";
$LANG['install_maj_titre_2']			= "Mise � jour de l'application PHP_CONGES";
$LANG['install_maj_passer_de']			= "vous �tes sur le point de passer de la version";
$LANG['install_maj_a_version']			= "� la version";
$LANG['install_maj_sauvegardez']		= "Avant de continuer, prenez soin de faire une sauvegarde de votre base de donn�es";
$LANG['install_etape']					= "etape";
$LANG['install_inaccessible']			= "n'est pas accessible";
$LANG['install_maj_conserv_config']		= "Afin d'assurer la conservation de votre configuration,";
$LANG['install_maj_copy_config_file']	= "veuillez copier votre ancien fichier config.php dans le nouveau r�pertoire";
$LANG['install_maj_whith_name']			= "sous le nom";
$LANG['install_maj_and']				= "et";
$LANG['install_maj_verif_droit_fichier']	= "verifier les droits de lecture sur ce fichier.";



// FIN DES VARIABLES A RENSEIGNER :
/*************************************************************************************************/
$_SESSION['lang']=$LANG ;





?>
