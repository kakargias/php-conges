<?php
/************************************************************************************************
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

// Fichier de Langue FR (FRANCAIS )
$LANG=array();
/*************************************************************************************************/
// VARIABLES A RENSEIGNER :



/***********************/
// MOIS ET JOURS
$LANG['janvier']	= "janvier";
$LANG['fevrier']	= "fevrier";
$LANG['mars']		= "mars";
$LANG['avril']		= "avril";
$LANG['mai']		= "mai";
$LANG['juin']		= "juin";
$LANG['juillet']	= "juillet";
$LANG['aout']		= "aout";
$LANG['septembre']	= "septembre";
$LANG['octobre']	= "octobre";
$LANG['novembre']	= "novembre";
$LANG['decembre']	= "decembre";

$LANG['lundi']		= "lundi";
$LANG['mardi']		= "mardi";
$LANG['mercredi']	= "mercredi";
$LANG['jeudi']		= "jeudi";
$LANG['vendredi']	= "vendredi";
$LANG['samedi']		= "samedi";
$LANG['dimanche']	= "dimanche";

$LANG['lundi_short']		= "lundi_short";
$LANG['mardi_short']		= "mardi_short";
$LANG['mercredi_short']		= "mercredi_short";
$LANG['jeudi_short']		= "jeudi_short";
$LANG['vendredi_short']		= "vendredi_short";
$LANG['samedi_short']		= "samedi_short";
$LANG['dimanche_short']		= "dimanche_short";

$LANG['lundi_2c']		= "lu";
$LANG['mardi_2c']		= "ma";
$LANG['mercredi_2c']	= "me";
$LANG['jeudi_2c']		= "je";
$LANG['vendredi_2c']	= "ve";
$LANG['samedi_2c']		= "sa";
$LANG['dimanche_2c']	= "di";

$LANG['lundi_1c']		= "lundi_1c";
$LANG['mardi_1c']		= "mardi_1c";
$LANG['mercredi_1c']	= "mercredi_1c";
$LANG['jeudi_1c']		= "jeudi_1c";
$LANG['vendredi_1c']	= "vendredi_1c";
$LANG['samedi_1c']		= "samedi_1c";
$LANG['dimanche_1c']	= "dimanche_1c";



/***********************/
// BOUTONS COMMUNS
$LANG['button_deconnect']		= "button_deconnect";
$LANG['button_refresh']			= "button_refresh";
$LANG['button_editions']		= "button_editions";
$LANG['button_admin_mode']		= "button_admin_mode";
$LANG['button_calendar']		= "button_calendar";



/***********************/
// FORMULAIRES 
$LANG['form_ok']		= "form_ok";
$LANG['form_submit']	= "form_submit";
$LANG['form_cancel']	= "form_cancel";
$LANG['form_retour']	= "form_retour";
$LANG['form_ajout']		= "form_ajout";
$LANG['form_supprim']	= "form_supprim";
$LANG['form_modif']		= "form_modif";
$LANG['form_annul']		= "form_annul";
$LANG['form_redo']		= "form_redo";
$LANG['form_am']		= "form_am";
$LANG['form_pm']		= "form_pm";
$LANG['form_day']		= "form_day";
$LANG['form_close_window']	= "form_close_window";
$LANG['form_save_modif']	= "form_save_modif";
$LANG['form_modif_ok']		= "form_modif_ok";
$LANG['form_modif_not_ok']	= "form_modif_not_ok";
$LANG['form_valid_global']	= "form_valid_global";
$LANG['form_valid_groupe']	= "form_valid_groupe";
$LANG['form_password']		= "form_password";
$LANG['form_start']			= "form_start";
$LANG['form_continuer']		= "form_continuer";



/***********************/
// DIVERS
$LANG['divers_quotite']			= "divers_quotite";
$LANG['divers_quotite_maj_1']	= "divers_quotite_maj_1";
$LANG['divers_an']				= "divers_an";
$LANG['divers_an_maj']			= "divers_an_maj";
$LANG['divers_solde']			= "divers_solde";
$LANG['divers_solde_maj']		= "divers_solde_maj";
$LANG['divers_solde_maj_1']		= "divers_solde_maj_1";
$LANG['divers_debut_maj']		= "divers_debut_maj";
$LANG['divers_debut_maj_1']		= "divers_debut_maj_1";
$LANG['divers_fin_maj']			= "divers_fin_maj";
$LANG['divers_fin_maj_1']		= "divers_fin_maj_1";
$LANG['divers_type']			= "divers_type";
$LANG['divers_type_maj_1']		= "divers_type_maj_1";
$LANG['divers_comment_maj_1']	= "divers_comment_maj_1";
$LANG['divers_etat_maj_1']		= "divers_etat_maj_1";
$LANG['divers_nb_jours_pris_maj_1']	= "divers_nb_jours_pris_maj_1";
$LANG['divers_nb_jours_maj_1']	= "divers_nb_jours_maj_1";
$LANG['divers_inconnu']			= "divers_inconnu";
$LANG['divers_motif_refus']		= "divers_motif_refus";
$LANG['divers_motif_annul']		= "divers_motif_annul";
$LANG['divers_refuse']			= "divers_refuse";
$LANG['divers_annule']			= "divers_annule";
$LANG['divers_login']			= "divers_login";
$LANG['divers_login_maj_1']		= "divers_login_maj_1";
$LANG['divers_personne_maj_1']	= "divers_personne_maj_1";
$LANG['divers_responsable_maj_1']	= "divers_responsable_maj_1";
$LANG['divers_nom_maj']			= "divers_nom_maj";
$LANG['divers_nom_maj_1']		= "divers_nom_maj_1";
$LANG['divers_prenom_maj']		= "divers_prenom_maj";
$LANG['divers_prenom_maj_1']	= "divers_prenom_maj_1";
$LANG['divers_accepter_maj_1']	= "divers_accepter_maj_1";
$LANG['divers_refuser_maj_1']	= "divers_refuser_maj_1";
$LANG['divers_fermer_maj_1']	= "divers_fermer_maj_1";
$LANG['divers_am_short']		= "divers_am_short";
$LANG['divers_pm_short']		= "divers_pm_short";
$LANG['divers_conges']			= "divers_conges";
$LANG['divers_conges_maj_1']	= "divers_conges_maj_1";
$LANG['divers_absences']		= "divers_absences";
$LANG['divers_absences_maj_1']	= "divers_absences_maj_1";
$LANG['divers_nouvelle_absence']	= "divers_nouvelle_absence";
$LANG['divers_mois_precedent']			= "divers_mois_precedent";
$LANG['divers_mois_precedent_maj_1']	= "divers_mois_precedent_maj_1";
$LANG['divers_mois_suivant']			= "divers_mois_suivant";
$LANG['divers_mois_suivant_maj_1']		= "divers_mois_suivant_maj_1";



/***********************/
// PARTIE UTILISATEUR
//divers
$LANG['user']				= "user";

//onglets
$LANG['user_onglet_echange_abs']			= "user_onglet_echange_abs";
$LANG['user_onglet_demandes']				= "user_onglet_demandes";
$LANG['user_onglet_historique_conges']	= "user_onglet_historique_conges";
$LANG['user_onglet_historique_abs']		= "user_onglet_historique_abs";
$LANG['user_onglet_change_passwd']		= "user_onglet_change_passwd";

//titres des pages
$LANG['user_echange_rtt']				= "user_echange_rtt";
$LANG['user_etat_demandes']				= "user_etat_demandes";
$LANG['user_historique_conges']			= "user_historique_conges";
$LANG['user_historique_abs']			= "user_historique_abs";
$LANG['user_change_password']			= "user_change_password";

//page etat des demandes
$LANG['user_demandes_aucune_demande']	= "user_demandes_aucune_demande";

//page historique des conges
$LANG['user_conges_aucun_conges']		= "user_conges_aucun_conges";

//page historique des absences
$LANG['user_abs_aucune_abs']			= "user_abs_aucune_abs";
$LANG['user_abs_type']				= "user_abs_type";

//page changer password
$LANG['user_passwd_saisie_1']		= "user_passwd_saisie_1";
$LANG['user_passwd_saisie_2']		= "user_passwd_saisie_2";
$LANG['user_passwd_error']			= "user_passwd_error";


//page modification demande / absence
$LANG['user_modif_demande_titre']		= "user_modif_demande_titre";

//page suppression demande / absence
$LANG['user_suppr_demande_titre']		= "user_suppr_demande_titre";





/***********************/
// PARTIE RESPONSABLE
//menu
$LANG['resp_menu_titre']					= "resp_menu_titre";
$LANG['resp_menu_button_retour_main']		= "resp_menu_button_retour_main";
$LANG['resp_menu_button_traite_demande']	= "resp_menu_button_traite_demande";
$LANG['resp_menu_button_affiche_user']		= "resp_menu_button_affiche_user";
$LANG['resp_menu_button_ajout_jours']		= "resp_menu_button_ajout_jours";
$LANG['resp_menu_button_mode_user']			= "resp_menu_button_mode_user";
$LANG['resp_menu_button_mode_admin']		= "resp_menu_button_mode_admin";

//page etat des conges des users
$LANG['resp_etat_users_afficher']	= "resp_etat_users_afficher";
$LANG['resp_etat_users_imprim']		= "resp_etat_users_imprim";
//page traite toutes les demandes
$LANG['resp_traite_demandes_titre']				= "resp_traite_demandes_titre";
$LANG['resp_traite_demandes_aucune_demande']	= "resp_traite_demandes_aucune_demande";
$LANG['resp_traite_demandes_nb_jours']			= "resp_traite_demandes_nb_jours";
$LANG['resp_traite_demandes_attente']			= "resp_traite_demandes_attente";
$LANG['resp_traite_demandes_motif_refus']		= "resp_traite_demandes_motif_refus";
//page ajout conges
$LANG['resp_ajout_conges_titre']				= "resp_ajout_conges_titre";
$LANG['resp_ajout_conges_nb_jours_ajout']		= "resp_ajout_conges_nb_jours_ajout";
$LANG['resp_ajout_conges_ajout_all']			= "resp_ajout_conges_ajout_all";
$LANG['resp_ajout_conges_nb_jours_all_1']		= "resp_ajout_conges_nb_jours_all_1";
$LANG['resp_ajout_conges_nb_jours_all_2']		= "resp_ajout_conges_nb_jours_all_2";
$LANG['resp_ajout_conges_calcul_prop']			= "resp_ajout_conges_calcul_prop";
$LANG['resp_ajout_conges_oui']					= "resp_ajout_conges_oui";
$LANG['resp_ajout_conges_calcul_prop_arondi']	= "resp_ajout_conges_calcul_prop_arondi";
$LANG['resp_ajout_conges_ajout_groupe']			= "resp_ajout_conges_ajout_groupe";
$LANG['resp_ajout_conges_choix_groupe']			= "resp_ajout_conges_choix_groupe";
$LANG['resp_ajout_conges_nb_jours_groupe_1']	= "resp_ajout_conges_nb_jours_groupe_1";
$LANG['resp_ajout_conges_nb_jours_groupe_2']	= "resp_ajout_conges_nb_jours_groupe_2";
$LANG['resp_ajout_conges_comment_periode_user']	= "resp_ajout_conges_comment_periode_user";
$LANG['resp_ajout_conges_comment_periode_all']	= "resp_ajout_conges_comment_periode_all";
$LANG['resp_ajout_conges_comment_periode_groupe']	= "resp_ajout_conges_comment_periode_groupe";
//page traite user
$LANG['resp_traite_user_titre']				= "resp_traite_user_titre";
$LANG['resp_traite_user_new_conges']		= "resp_traite_user_new_conges";
$LANG['resp_traite_user_etat_demandes']		= "resp_traite_user_etat_demandes";
$LANG['resp_traite_user_etat_conges']		= "resp_traite_user_etat_conges";
$LANG['resp_traite_user_aucune_demande']	= "resp_traite_user_aucune_demande";
$LANG['resp_traite_user_motif_refus']		= "resp_traite_user_motif_refus";
$LANG['resp_traite_user_aucun_conges']		= "resp_traite_user_aucun_conges";
$LANG['resp_traite_user_motif_possible']	= "resp_traite_user_motif_possible";
$LANG['resp_traite_user_annul']				= "resp_traite_user_annul";
$LANG['resp_traite_user_motif_annul']		= "resp_traite_user_motif_annul";
$LANG['resp_traite_user_motif']				= "resp_traite_user_motif";
$LANG['resp_traite_user_valeurs_not_ok']	= "resp_traite_user_valeurs_not_ok";





/***********************/
// PARTIE ADMINISTRATEUR
//divers
$LANG['admin_titre']					= "admin_titre";
$LANG['admin_button_close_window_1']	= "admin_button_close_window_1";
$LANG['admin_button_config_1']			= "admin_button_config_1";
$LANG['admin_button_config_2']			= "admin_button_config_2";
$LANG['admin_button_config_abs_1']		= "admin_button_config_abs_1";
$LANG['admin_button_config_abs_2']		= "admin_button_config_abs_2";
$LANG['admin_button_jours_chomes_1']	= "admin_button_jours_chomes_1";
$LANG['admin_button_jours_chomes_2']	= "admin_button_jours_chomes_2";
$LANG['admin_button_save_db_1']			= "admin_button_save_db_1";
$LANG['admin_button_save_db_2']			= "admin_button_save_db_2";
//
$LANG['admin_onglet_gestion_user']		= "admin_onglet_gestion_user";
$LANG['admin_onglet_add_user']			= "admin_onglet_add_user";
$LANG['admin_onglet_gestion_groupe']	= "admin_onglet_gestion_groupe";
$LANG['admin_onglet_groupe_user']		= "admin_onglet_groupe_user";
$LANG['admin_onglet_user_groupe']		= "admin_onglet_user_groupe";
$LANG['admin_onglet_groupe_resp']		= "admin_onglet_groupe_resp";
$LANG['admin_onglet_resp_groupe']		= "admin_onglet_resp_groupe";
//
$LANG['admin_verif_param_invalides']	= "admin_verif_param_invalides";
$LANG['admin_verif_login_exist']		= "admin_verif_login_exist";
$LANG['admin_verif_bad_mail']			= "admin_verif_bad_mail";
$LANG['admin_verif_groupe_invalide']	= "admin_verif_groupe_invalide";
// page gestion utilisateurs
$LANG['admin_users_titre']				= "admin_users_titre";
$LANG['admin_users_is_resp']			= "admin_users_is_resp";
$LANG['admin_users_resp_login']			= "admin_users_resp_login";
$LANG['admin_users_is_admin']			= "admin_users_is_admin";
$LANG['admin_users_see_all']			= "admin_users_see_all";
$LANG['admin_users_mail']				= "admin_users_mail";
$LANG['admin_users_password_1']			= "admin_users_password_1";
$LANG['admin_users_password_2']			= "admin_users_password_2";
// page ajout utilisateur
$LANG['admin_new_users_titre']			= "admin_new_users_titre";
$LANG['admin_new_users_is_resp']		= "admin_new_users_is_resp";
$LANG['admin_new_users_is_admin']		= "admin_new_users_is_admin";
$LANG['admin_new_users_see_all']		= "admin_new_users_see_all";
$LANG['admin_new_users_password']		= "admin_new_users_password";
$LANG['admin_new_users_nb_par_an']		= "admin_new_users_nb_par_an";
// page ajout utilisateur
$LANG['admin_groupes_groupe']			= "admin_groupes_groupe";
$LANG['admin_groupes_libelle']			= "admin_groupes_libelle";
$LANG['admin_groupes_new_groupe']		= "admin_groupes_new_groupe";
// page gestion groupes
$LANG['admin_gestion_groupe_etat']		= "admin_gestion_groupe_etat";
// 
$LANG['admin_aff_choix_groupe_titre']		= "admin_aff_choix_groupe_titre";
$LANG['admin_aff_choix_user_titre']		= "admin_aff_choix_user_titre";
$LANG['admin_aff_choix_resp_titre']		= "admin_aff_choix_resp_titre";
// page gestion groupe <-> users
$LANG['admin_gestion_groupe_users_membres']	= "admin_gestion_groupe_users_membres";
$LANG['admin_gestion_groupe_users_group_of_user']	= "admin_gestion_groupe_users_group_of_user";
// page gestion groupe <-> users
$LANG['admin_gestion_groupe_resp_groupes']		= "admin_gestion_groupe_resp_groupes";
$LANG['admin_gestion_groupe_resp_responsables']	= "admin_gestion_groupe_resp_responsables";
// page change password user
$LANG['admin_chg_passwd_titre']		= "admin_chg_passwd_titre";
// page admin_suppr_user
$LANG['admin_suppr_user_titre']		= "admin_suppr_user_titre";
// page admin_modif_user
$LANG['admin_modif_user_titre']		= "admin_modif_user_titre";
$LANG['admin_modif_nb_jours_an']	= "admin_modif_nb_jours_an";
// grille saisie temps partiel et RTT
$LANG['admin_temps_partiel_titre']			= "admin_temps_partiel_titre";
$LANG['admin_temps_partiel_sem_impaires']	= "admin_temps_partiel_sem_impaires";
$LANG['admin_temps_partiel_sem_paires']		= "admin_temps_partiel_sem_paires";
$LANG['admin_temps_partiel_am']				= "admin_temps_partiel_am";
$LANG['admin_temps_partiel_pm']				= "admin_temps_partiel_pm";
$LANG['admin_temps_partiel_date_valid']		= "admin_temps_partiel_date_valid";
// page admin_suppr_groupe
$LANG['admin_suppr_groupe_titre']		= "admin_suppr_groupe_titre";
// page admin_suppr_groupe
$LANG['admin_modif_groupe_titre']		= "admin_modif_groupe_titre";
// page admin_sauve_restaure_db
$LANG['admin_sauve_db_titre']			= "admin_sauve_db_titre";
$LANG['admin_sauve_db_choisissez']		= "admin_sauve_db_choisissez";
$LANG['admin_sauve_db_sauve']			= "admin_sauve_db_sauve";
$LANG['admin_sauve_db_restaure']		= "admin_sauve_db_restaure";
$LANG['admin_sauve_db_do_sauve']		= "admin_sauve_db_do_sauve";
$LANG['admin_sauve_db_options']			= "admin_sauve_db_options";
$LANG['admin_sauve_db_complete']		= "admin_sauve_db_complete";
$LANG['admin_sauve_db_data_only']		= "admin_sauve_db_data_only";
$LANG['admin_sauve_db_save_ok']			= "admin_sauve_db_save_ok";
$LANG['admin_sauve_db_restaure']		= "admin_sauve_db_restaure";
$LANG['admin_sauve_db_file_to_restore']	= "admin_sauve_db_file_to_restore";
$LANG['admin_sauve_db_warning']			= "admin_sauve_db_warning";
$LANG['admin_sauve_db_do_restaure']		= "admin_sauve_db_do_restaure";
$LANG['admin_sauve_db_bad_file']		= "admin_sauve_db_bad_file";
$LANG['admin_sauve_db_restaure_ok']		= "admin_sauve_db_restaure_ok";
// page admin_jours_chomes
$LANG['admin_jours_chomes_titre']				= "admin_jours_chomes_titre";
$LANG['admin_jours_chomes_annee_precedente']	= "admin_jours_chomes_annee_precedente";
$LANG['admin_jours_chomes_annee_suivante']		= "admin_jours_chomes_annee_suivante";
$LANG['admin_jours_chomes_confirm']				= "admin_jours_chomes_confirm";



/***********************/
// EDITIONS PAPIER
$LANG['editions_titre']			= "editions_titre";
$LANG['editions_last_edition']	= "editions_last_edition";
$LANG['editions_aucun_conges']	= "editions_aucun_conges";
$LANG['editions_lance_edition']		= "editions_lance_edition";
$LANG['editions_pdf_edition']		= "editions_pdf_edition";
$LANG['editions_hitorique_edit']	= "editions_hitorique_edit";
$LANG['editions_aucun_hitorique']	= "editions_aucun_hitorique";
$LANG['editions_numero']			= "editions_numero";
$LANG['editions_date']				= "editions_date";
$LANG['editions_edit_again']		= "editions_edit_again";
$LANG['editions_edit_again_pdf']	= "editions_edit_again_pdf";
//
$LANG['editions_bilan_au']			= "editions_bilan_au";
$LANG['editions_historique']		= "editions_historique";
$LANG['editions_soldes_precedents_inconnus']	= "editions_soldes_precedents_inconnus";
$LANG['editions_solde_precedent']	= "editions_solde_precedent";
$LANG['editions_nouveau_solde']		= "editions_nouveau_solde";
$LANG['editions_signature_1']		= "editions_signature_1";
$LANG['editions_signature_2']		= "editions_signature_2";
$LANG['editions_cachet_etab']		= "editions_cachet_etab";
$LANG['editions_jours_an']			= "editions_jours_an";




/***********************/
// SAISIE CONGES
$LANG['saisie_conges_compter_jours']		= "saisie_conges_compter_jours";
$LANG['saisie_conges_nb_jours']				= "saisie_conges_nb_jours";



/***********************/
// SAISIE ECHANGE ABSENCE
$LANG['saisie_echange_titre_calendrier_1']		= "saisie_echange_titre_calendrier_1";
$LANG['saisie_echange_titre_calendrier_2']		= "saisie_echange_titre_calendrier_2";



/***********************/
// CALENDRIER
$LANG['calendrier_titre']			= "calendrier_titre";
$LANG['calendrier_imprimable']		= "calendrier_imprimable";
$LANG['calendrier_jour_precedent']	= "calendrier_jour_precedent";
$LANG['calendrier_jour_suivant']	= "calendrier_jour_suivant";
$LANG['calendrier_legende_we']			= "calendrier_legende_we";
$LANG['calendrier_legende_conges']		= "calendrier_legende_conges";
$LANG['calendrier_legende_demande']		= "calendrier_legende_demande";
$LANG['calendrier_legende_part_time']	= "calendrier_legende_part_time";
$LANG['calendrier_legende_abs']			= "calendrier_legende_abs";



/***********************/
// CALCUL NB JOURS
$LANG['calcul_nb_jours_nb_jours']	= "calcul_nb_jours_nb_jours";
$LANG['calcul_nb_jours_reportez']	= "calcul_nb_jours_reportez";
$LANG['calcul_nb_jours_form']		= "calcul_nb_jours_form";



/***********************/
// ERREUR
$LANG['erreur_user']			= "erreur_user";
$LANG['erreur_login_password']	= "erreur_login_password";
$LANG['erreur_session']			= "erreur_session";



/***********************/
// INCLUDE_PHP
$LANG['mysql_srv_connect_failed']		= "mysql_srv_connect_failed";
$LANG['mysql_db_connect_failed']		= "mysql_db_connect_failed";

// page d'authentification / login screen
$LANG['cookies_obligatoires']		= "cookies_obligatoires";
$LANG['javascript_obligatoires']	= "javascript_obligatoires";
$LANG['login_passwd_incorrect']	= "login_passwd_incorrect";
$LANG['login_non_connu']			= "login_non_connu";
//
$LANG['login_fieldset']			= "login_fieldset";
$LANG['password']					= "password";
$LANG['msie_alert']				= "msie_alert";


// verif saisie
$LANG['verif_saisie_erreur_valeur_manque']		= "verif_saisie_erreur_valeur_manque";
$LANG['verif_saisie_erreur_nb_jours_bad']		= "verif_saisie_erreur_nb_jours_bad";
$LANG['verif_saisie_erreur_fin_avant_debut']	= "verif_saisie_erreur_fin_avant_debut";
$LANG['verif_saisie_erreur_debut_apres_fin']	= "verif_saisie_erreur_debut_apres_fin";
$LANG['verif_saisie_erreur_nb_bad']				= "verif_saisie_erreur_nb_bad";


/***********************/
// CONFIG TYPES ABSENCES
$LANG['config_abs_titre']				= "config_abs_titre";
$LANG['config_abs_comment_conges']		= "config_abs_comment_conges" ;
$LANG['config_abs_comment_absences']	= "config_abs_comment_absences" ;
$LANG['config_abs_libelle']				= "config_abs_libelle";
$LANG['config_abs_libelle_short']		= "config_abs_libelle_short";
$LANG['config_abs_add_type_abs']			= "config_abs_add_type_abs";
$LANG['config_abs_add_type_abs_comment']	= "config_abs_add_type_abs_comment";
$LANG['config_abs_saisie_not_ok']			= "config_abs_saisie_not_ok";
$LANG['config_abs_bad_caracteres']			= "config_abs_bad_caracteres";
$LANG['config_abs_champs_vides']			= "config_abs_champs_vides";
$LANG['config_abs_suppr_impossible']		= "config_abs_suppr_impossible";
$LANG['config_abs_already_used']			= "config_abs_already_used";
$LANG['config_abs_confirm_suppr_of']		= "config_abs_confirm_suppr_of";



/***************************/
// CONFIGURATION PHP_CONGES
$LANG['config_appli_titre_1']		= "config_appli_titre_1";
$LANG['config_appli_titre_2']		= "config_appli_titre_2";
//groupes de paramètres
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
$LANG['14_Présentation']			= "14Présentation";
$LANG['15_Modules Externes']		= "15 Modules Externes";
// parametres de config
$LANG['config_comment_installed_version']	= "config_comment_installed_version";
$LANG['config_comment_lang']				= "config_comment_lang";
$LANG['config_comment_URL_ACCUEIL_CONGES']	= "config_comment_URL_ACCUEIL_CONGES";
$LANG['config_comment_img_login']			= "config_comment_img_login";
$LANG['config_comment_texte_img_login']		= "config_comment_texte_img_login";
$LANG['config_comment_lien_img_login']		= "config_comment_lien_img_login";
$LANG['config_comment_titre_calendrier']	= "config_comment_titre_calendrier";
$LANG['config_comment_titre_user_index']	= "config_comment_titre_user_index";
$LANG['config_comment_titre_resp_index']	= "config_comment_titre_resp_index";
$LANG['config_comment_titre_admin_index']	= "config_comment_titre_admin_index";
$LANG['config_comment_auth']				= "config_comment_auth";
$LANG['config_comment_how_to_connect_user']	= "config_comment_how_to_connect_user";
$LANG['config_comment_export_users_from_ldap']	= "config_comment_export_users_from_ldap";
$LANG['config_comment_user_saisie_demande']		= "config_comment_user_saisie_demande";
$LANG['config_comment_user_affiche_calendrier']	= "config_comment_user_affiche_calendrier";
$LANG['config_comment_user_saisie_mission']		= "config_comment_user_saisie_mission";
$LANG['config_comment_user_ch_passwd']			= "config_comment_user_ch_passwd";
$LANG['config_comment_responsable_virtuel']		= "config_comment_responsable_virtuel";
$LANG['config_comment_resp_affiche_calendrier']	= "config_comment_resp_affiche_calendrier";
$LANG['config_comment_resp_saisie_mission']		= "config_comment_resp_saisie_mission";
$LANG['config_comment_resp_vertical_menu']		= "config_comment_resp_vertical_menu";
$LANG['config_comment_admin_see_all']			= "config_comment_admin_see_all";
$LANG['config_comment_admin_change_passwd']		= "config_comment_admin_change_passwd";
$LANG['config_comment_affiche_bouton_config_pour_admin']			= "config_comment_affiche_bouton_config_pour_admin";
$LANG['config_comment_affiche_bouton_config_absence_pour_admin']	= "config_comment_affiche_bouton_config_absence_pour_admin";
$LANG['config_comment_mail_new_demande_alerte_resp']	= "config_comment_mail_new_demande_alerte_resp";
$LANG['config_comment_mail_valid_conges_alerte_user']	= "config_comment_mail_valid_conges_alerte_user";
$LANG['config_comment_mail_refus_conges_alerte_user']	= "config_comment_mail_refus_conges_alerte_user";
$LANG['config_comment_mail_annul_conges_alerte_user']	= "config_comment_mail_annul_conges_alerte_user";
$LANG['config_comment_serveur_smtp']					= "config_comment_serveur_smtp";
$LANG['config_comment_where_to_find_user_email']		= "config_comment_where_to_find_user_email";
$LANG['config_comment_samedi_travail']		= "config_comment_samedi_travail";
$LANG['config_comment_dimanche_travail']	= "config_comment_dimanche_travail";
$LANG['config_comment_gestion_groupes']		= "config_comment_gestion_groupes";
$LANG['config_comment_affiche_groupe_in_calendrier']	= "config_comment_affiche_groupe_in_calendrier";
$LANG['config_comment_editions_papier']				= "config_comment_editions_papier";
$LANG['config_comment_texte_haut_edition_papier']	= "config_comment_texte_haut_edition_papier";
$LANG['config_comment_texte_bas_edition_papier']	= "config_comment_texte_bas_edition_papier";
$LANG['config_comment_user_echange_rtt']			= "config_comment_user_echange_rtt";
$LANG['config_comment_affiche_bouton_calcul_nb_jours_pris']	= "config_comment_affiche_bouton_calcul_nb_jours_pris";
$LANG['config_comment_rempli_auto_champ_nb_jours_pris']		= "config_comment_rempli_auto_champ_nb_jours_pris";
$LANG['config_comment_duree_session']	= "config_comment_duree_session";
$LANG['config_comment_verif_droits']	= "config_comment_verif_droits";
$LANG['config_comment_stylesheet_file']	= "config_comment_stylesheet_file";
$LANG['config_comment_bgcolor']			= "config_comment_bgcolor";
$LANG['config_comment_bgimage']			= "config_comment_bgimage";
$LANG['config_comment_light_grey_bgcolor']					= "config_comment_light_grey_bgcolor";
$LANG['config_comment_php_conges_fpdf_include_path']		= "config_comment_php_conges_fpdf_include_path";
$LANG['config_comment_php_conges_phpmailer_include_path']	= "config_comment_php_conges_phpmailer_include_path";
$LANG['config_comment_php_conges_cas_include_path']			= "config_comment_php_conges_cas_include_path";
$LANG['config_comment_php_conges_authldap_include_path']	= "config_comment_php_conges_authldap_include_path";


/***************************/
// INSTALLATION PHP_CONGES
//page index
$LANG['install_le_fichier']		= "install_le_fichier";
$LANG['install_bad_fichier']	= "install_bad_fichier";
$LANG['install_read_the_file']	= "install_read_the_file";
$LANG['install_reload_page']	= "install_reload_page";
$LANG['install_db_inaccessible']		= "install_db_inaccessible";
$LANG['install_verifiez_param_file']	= "install_verifiez_param_file";
$LANG['install_verifiez_priv_mysql']	= "install_verifiez_priv_mysql";
$LANG['install_install_phpconges']		= "install_install_phpconges";
$LANG['install_titre']					= "install_titre";
$LANG['install_no_prev_version_found']	= "install_no_prev_version_found";
$LANG['install_indiquez']				= "install_indiquez";
$LANG['install_nouvelle_install']		= "install_nouvelle_install";
$LANG['install_mise_a_jour']			= "install_mise_a_jour";
$LANG['install_indiquez_pre_version']	= "install_indiquez_pre_version";
$LANG['install_installed_version']		= "install_installed_version";
$LANG['install_configuration']			= "install_configuration";
$LANG['install_config_appli']			= "install_config_appli";
$LANG['install_config_types_abs']		= "install_config_types_abs";
//page install
$LANG['install_install_titre']			= "install_install_titre";
$LANG['install_impossible_sur_db']		= "install_impossible_sur_db";
$LANG['install_verif_droits_mysql']		= "install_verif_droits_mysql";
$LANG['install_puis']					= "install_puis";
$LANG['install_ok']						= "install_ok";
$LANG['install_vous_pouvez_maintenant']	= "install_vous_pouvez_maintenant";
$LANG['install_acceder_appli']			= "install_acceder_appli";
//page mise_a_jour
$LANG['install_version_non_choisie']	= "install_version_non_choisie";
$LANG['install_maj_titre_1']			= "install_maj_titre_1";
$LANG['install_maj_titre_2']			= "install_maj_titre_2";
$LANG['install_maj_passer_de']			= "install_maj_passer_de";
$LANG['install_maj_a_version']			= "install_maj_a_version";
$LANG['install_maj_sauvegardez']		= "install_maj_sauvegardez";
$LANG['install_etape']					= "install_etape";
$LANG['install_inaccessible']			= "install_inaccessible";
$LANG['install_maj_conserv_config']		= "install_maj_conserv_config";
$LANG['install_maj_copy_config_file']	= "install_maj_copy_config_file";
$LANG['install_maj_whith_name']			= "install_maj_whith_name";
$LANG['install_maj_and']				= "install_maj_and";
$LANG['install_maj_verif_droit_fichier']	= "install_maj_verif_droit_fichier";


/***********************/
/***********************/
/***********************/
// NEW : V1.1.2
$LANG['button_export_1']			= "button_export_1";
$LANG['button_export_2']			= "button_export_2";
$LANG['config_comment_disable_saise_champ_nb_jours_pris']	= "config_comment_disable_saise_champ_nb_jours_pris";


// FIN DES VARIABLES A RENSEIGNER :
/*************************************************************************************************/
$_SESSION['lang']=$LANG ;

?>
