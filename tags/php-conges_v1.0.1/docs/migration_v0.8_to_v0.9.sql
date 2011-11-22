###########################################################
## 1
## MISE EN PLACE DU GID COMME IDENTIFIANT DES GROUPES :
###########################################################

#
# Structure de la table `conges_groupe`
###############################################
ALTER TABLE `conges_groupe` DROP PRIMARY KEY ;
ALTER TABLE `conges_groupe` ADD `g_gid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;


#
# Structure de la table `conges_groupe_resp`
###############################################
ALTER TABLE `conges_groupe_resp` ADD `gr_gid` INT NOT NULL FIRST ;

#
# Donn�es de la table `conges_groupe_resp`
###############################################
CREATE TABLE `temp_resp` (
`tr_gid` INT  NULL ,
`tr_groupename` VARCHAR( 50 )  NULL ,
`tr_login` VARCHAR( 50 )  NULL
);

insert into `temp_resp` (`tr_gid` ,`tr_groupename`,`tr_login`) SELECT g_gid, g_groupename, gr_login 
from conges_groupe, conges_groupe_resp where conges_groupe.g_groupename=conges_groupe_resp.gr_groupename ;

DELETE FROM `conges_groupe_resp` ;

insert into `conges_groupe_resp` (`gr_gid` ,`gr_groupename`,`gr_login`) SELECT tr_gid, tr_groupename, tr_login from `temp_resp`;
DROP TABLE `temp_resp` ;

#
# Structure de la table `conges_groupe_resp`
###############################################
ALTER TABLE `conges_groupe_resp` DROP `gr_groupename` ;




#
# Structure de la table `conges_groupe_users`
###############################################
ALTER TABLE `conges_groupe_users` ADD `gu_gid` INT NOT NULL  FIRST ;


#
# Donn�es de la table `conges_groupe_users`
###############################################
CREATE TABLE `temp_users` (
`tu_gid` INT  NULL ,
`tu_groupename` VARCHAR( 50 )  NULL ,
`tu_login` VARCHAR( 50 )  NULL
);

insert into `temp_users` (`tu_gid` ,`tu_groupename`,`tu_login`) SELECT g_gid, g_groupename, gu_login 
from conges_groupe, conges_groupe_users where conges_groupe.g_groupename=conges_groupe_users.gu_groupename ;

DELETE FROM `conges_groupe_users` ;

insert into `conges_groupe_users` (`gu_gid` ,`gu_groupename`,`gu_login`) SELECT tu_gid, tu_groupename, tu_login from `temp_users`;
DROP TABLE `temp_users` ;



###########################################################
## 2
## MISE EN PLACE DU CHAMP IS_ADMIN POUR LES USERS :
###########################################################


#
# Structure de la table `conges_groupe_users`
###############################################
ALTER TABLE `conges_groupe_users` DROP `gu_groupename` ;


#
# Structure de la table `conges_users`
###############################################
ALTER TABLE `conges_users` ADD `u_is_admin` ENUM( 'Y', 'N' ) DEFAULT 'N' NOT NULL AFTER `u_resp_login` ;


#
# Donn�es de la table `conges_users`
###############################################
UPDATE `conges_users` SET `u_is_admin` = 'Y' WHERE `u_is_resp` = 'Y' ;




###########################################################
## 3
## MISE EN PLACE DES EDITIONS PAPIER :
###########################################################

#
# Structure de la table `conges_edition_papier`
###############################################
CREATE TABLE `conges_edition_papier` (
`ep_id` INT NOT NULL AUTO_INCREMENT ,
`ep_login` VARCHAR( 16 ) NOT NULL ,
`ep_date` DATE NOT NULL ,
`ep_solde_jours` DECIMAL( 4, 2 ) DEFAULT '0.00' NOT NULL ,
`ep_solde_rtt` DECIMAL( 4, 2 ) DEFAULT '0.00' NOT NULL ,
`ep_num_for_user` INT( 5 ) UNSIGNED DEFAULT '1' NOT NULL,
PRIMARY KEY ( `ep_id` )
);

#
# Structure de la table `conges_periode`
###############################################
ALTER TABLE `conges_periode` ADD `p_edition_id` INT AFTER `p_etat` ;
ALTER TABLE `conges_periode` ADD `p_type` ENUM( 'conges', 'rtt', 'formation', 'mission','autre') NOT NULL AFTER `p_commentaire` ;
ALTER TABLE `conges_periode` CHANGE `p_etat` `p_etat` ENUM( 'ok', 'pris', 'demande', 'refus�', 'annul�', 'ajout', 'formation', 'mission', 'autre', 'absence-annul�e', 'rtt_annul�e', 'rtt_prise', 'rtt_refus�e', 'demande_rtt' ) DEFAULT 'demande' NOT NULL ;

#
# donn�es de la table `conges_periode`
###############################################
UPDATE  `conges_periode`  SET `p_type`='conges', `p_etat`='ok' WHERE  `p_etat`  LIKE  'pris';
UPDATE  `conges_periode`  SET `p_type`='conges', `p_etat`='demande' WHERE  `p_etat`  LIKE  'demande';
UPDATE  `conges_periode`  SET `p_type`='conges', `p_etat`='refus�' WHERE  `p_etat`  LIKE  'refus�';
UPDATE  `conges_periode`  SET `p_type`='conges', `p_etat`='annul�' WHERE  `p_etat`  LIKE  'annul�';
UPDATE  `conges_periode`  SET `p_type`='rtt', `p_etat`='ok' WHERE  `p_etat`  LIKE  'rtt_prise';
UPDATE  `conges_periode`  SET `p_type`='rtt', `p_etat`='demande' WHERE  `p_etat`  LIKE  'demande_rtt';
UPDATE  `conges_periode`  SET `p_type`='rtt', `p_etat`='refus�' WHERE  `p_etat`  LIKE  'rtt_refus�e';
UPDATE  `conges_periode`  SET `p_type`='rtt', `p_etat`='annul�' WHERE  `p_etat`  LIKE  'rtt_annul�e';
UPDATE  `conges_periode`  SET `p_type`='formation', `p_etat`='ok' WHERE  `p_etat`  LIKE  'formation';
UPDATE  `conges_periode`  SET `p_type`='mission', `p_etat`='ok' WHERE  `p_etat`  LIKE  'mission';
UPDATE  `conges_periode`  SET `p_type`='autre', `p_etat`='annul�' WHERE  `p_etat`  LIKE  'absence-annul�e';

#
# Structure de la table `conges_periode`
###############################################
ALTER TABLE `conges_periode` CHANGE `p_etat` `p_etat` ENUM( 'ok', 'demande', 'refus�', 'annul�', 'ajout' ) DEFAULT 'demande' NOT NULL ;




###########################################################
## 4
## CONSEQUENCE DU CHAMP IS_ADMIN POUR LES USERS :
###########################################################


#
# Donn�es de la table `conges_users`
###############################################
UPDATE `conges_users` SET `u_is_resp` = 'N' WHERE `u_login` = 'admin' ;











