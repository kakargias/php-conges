#
# ATTENTION :  toutes les requetes doivent se terminer par un point virgule ";"
#
# table `conges_users`
###############################################
ALTER TABLE  `conges_users` ADD  `u_is_hr` ENUM( 'Y','N' ) NOT NULL DEFAULT 'N' AFTER `u_is_admin`;
ALTER TABLE  `conges_users` ADD  `u_is_active` ENUM( 'Y','N' ) NOT NULL DEFAULT 'Y' AFTER `u_is_hr`;;

DELETE FROM conges_config WHERE conf_nom IN ( 'bgcolor','bgimage','img_login','lien_img_login','php_conges_authldap_include_path','php_conges_cas_include_path','php_conges_fpdf_include_path','php_conges_phpmailer_include_path','texte_img_login','texte_page_login');
ALTER TABLE  `conges_users` ADD  `u_is_active` ENUM( 'Y','N' ) NOT NULL DEFAULT 'Y' AFTER `u_is_hr`;
INSERT INTO  `conges_config` (`conf_nom` ,`conf_valeur` ,`conf_groupe` ,`conf_type` ,`conf_commentaire`) VALUES ('print_disable_users',  'FALSE',  '06_Responsable',  'Boolean',  'config_comment_print_disable_users');
UPDATE  `conges_config` SET  `conf_valeur` =  'style.css' WHERE  `conges_config`.`conf_nom` =  'stylesheet_file';

