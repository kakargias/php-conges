#
# ATTENTION :  toutes les requetes doivent se terminer par un point virgule ";"
#
# table `conges_users`
###############################################
ALTER TABLE  `conges_users` ADD  `u_is_hr` ENUM(  'Y',  'N' ) NOT NULL DEFAULT  'N' AFTER  `u_see_all`;
DELETE FROM conges_config WHERE conf_nom IN ( 'bgcolor','bgimage','img_login','lien_img_login','php_conges_authldap_include_path','php_conges_cas_include_path','php_conges_fpdf_include_path','php_conges_phpmailer_include_path','texte_img_login','texte_page_login');

