#
# ATTENTION :  toutes les requetes doivent se terminer par un point virgule ";"
#
# table `conges_users`
###############################################
ALTER TABLE  `conges_users` ADD  `u_is_hr` ENUM(  'Y',  'N' ) NOT NULL DEFAULT  'N' AFTER  `u_see_all`;
UPDATE  `db_conges`.`conges_config` SET  `conf_valeur` =  'img/logo_adex.png' WHERE  `conges_config`.`conf_nom` =  'img_login';


