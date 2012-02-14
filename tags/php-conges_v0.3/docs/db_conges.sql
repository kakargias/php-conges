# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Serveur: localhost
# G�n�r� le : Mercredi 09 Mars 2005 � 13:55
# Version du serveur: 3.23.54
# Version de PHP: 4.2.2
# 
# Base de donn�es: `db_conges`
# 

# --------------------------------------------------------

#
# Structure de la table `conges_artt`
#

CREATE TABLE `conges_artt` (
  `a_login` varchar(16) NOT NULL default '',
  `sem_imp_lu_am` varchar(10) default NULL,
  `sem_imp_lu_pm` varchar(10) default NULL,
  `sem_imp_ma_am` varchar(10) default NULL,
  `sem_imp_ma_pm` varchar(10) default NULL,
  `sem_imp_me_am` varchar(10) default NULL,
  `sem_imp_me_pm` varchar(10) default NULL,
  `sem_imp_je_am` varchar(10) default NULL,
  `sem_imp_je_pm` varchar(10) default NULL,
  `sem_imp_ve_am` varchar(10) default NULL,
  `sem_imp_ve_pm` varchar(10) default NULL,
  `sem_p_lu_am` varchar(10) default NULL,
  `sem_p_lu_pm` varchar(10) default NULL,
  `sem_p_ma_am` varchar(10) default NULL,
  `sem_p_ma_pm` varchar(10) default NULL,
  `sem_p_me_am` varchar(10) default NULL,
  `sem_p_me_pm` varchar(10) default NULL,
  `sem_p_je_am` varchar(10) default NULL,
  `sem_p_je_pm` varchar(10) default NULL,
  `sem_p_ve_am` varchar(10) default NULL,
  `sem_p_ve_pm` varchar(10) default NULL,
  PRIMARY KEY  (`a_login`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Structure de la table `conges_periode`
#

CREATE TABLE `conges_periode` (
  `p_login` varchar(16) NOT NULL default '',
  `p_date_deb` date NOT NULL default '0000-00-00',
  `p_date_fin` date NOT NULL default '0000-00-00',
  `p_nb_jours` decimal(4,0) unsigned NOT NULL default '0',
  `p_commentaire` varchar(50) default NULL,
  `p_etat` varchar(15) NOT NULL default '"demande"',
  `p_num` int(5) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`p_num`)
) TYPE=MyISAM AUTO_INCREMENT=40 ;

# --------------------------------------------------------

#
# Structure de la table `conges_users`
#

CREATE TABLE `conges_users` (
  `u_login` varchar(16) NOT NULL default '',
  `u_nom` varchar(30) NOT NULL default '',
  `u_prenom` varchar(30) NOT NULL default '',
  `u_nb_jours_an` int(2) unsigned NOT NULL default '0',
  `u_nb_jours_reste` int(2) NOT NULL default '0',
  `u_is_resp` char(1) NOT NULL default 'N',
  `u_resp_login` varchar(16) default NULL,
  `u_passwd` varchar(16) NOT NULL default '',
  `u_quotite` int(3) default '100',
  KEY `u_login` (`u_login`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Structure de la table `session_appli_conges`
#

CREATE TABLE `session_appli_conges` (
  `user` varchar(255) binary NOT NULL default '',
  `session` varchar(255) NOT NULL default '',
  `connexion_start` bigint(20) NOT NULL default '0',
  `connexion_last` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`session`),
  KEY `user` (`user`),
  KEY `session` (`session`)
) TYPE=MyISAM;