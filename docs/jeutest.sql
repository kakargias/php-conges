# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Serveur: localhost
# Généré le : Vendredi 06 Janvier 2006 à 10:41
# Version du serveur: 3.23.58
# Version de PHP: 4.2.2
# 
# Base de données: `db_conges`
# 

#
# Contenu de la table `conges_artt`
#

INSERT INTO `conges_artt` VALUES ('marie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('pierre', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('georges', 'Y', 'Y', 'Y', 'Y', 'Y', NULL, NULL, NULL, NULL, NULL, 'Y', 'Y', 'Y', 'Y', 'Y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('bernard', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('cecile', NULL, NULL, NULL, NULL, 'Y', 'Y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'Y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('albert', 'Y', 'Y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'Y', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('kevin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('paolo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');
INSERT INTO `conges_artt` VALUES ('jean', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '9999-12-31');

#
# Contenu de la table `conges_echange_rtt`
#


#
# Contenu de la table `conges_edition_papier`
#


#
# Contenu de la table `conges_groupe`
#

INSERT INTO `conges_groupe` VALUES (1, 'reseau', 'groupe reseau');
INSERT INTO `conges_groupe` VALUES (2, 'commerce', 'groupe commerciaux');

#
# Contenu de la table `conges_groupe_resp`
#

INSERT INTO `conges_groupe_resp` VALUES (1, 'marie');
INSERT INTO `conges_groupe_resp` VALUES (2, 'paolo');

#
# Contenu de la table `conges_groupe_users`
#

INSERT INTO `conges_groupe_users` VALUES (1, 'albert');
INSERT INTO `conges_groupe_users` VALUES (1, 'cecile');
INSERT INTO `conges_groupe_users` VALUES (1, 'georges');
INSERT INTO `conges_groupe_users` VALUES (1, 'marie');
INSERT INTO `conges_groupe_users` VALUES (2, 'kevin');
INSERT INTO `conges_groupe_users` VALUES (2, 'paolo');
INSERT INTO `conges_groupe_users` VALUES (2, 'pierre');

#
# Contenu de la table `conges_periode`
#

INSERT INTO `conges_periode` VALUES ('cecile', '2002-01-31', 'am', '2004-02-08', 'pm', '7.00', '1 semaine', 'conges', 'ok', NULL, 1);
INSERT INTO `conges_periode` VALUES ('marie', '2010-03-20', 'am', '2015-03-20', 'pm', '6.00', 'rien', 'conges', 'demande', NULL, 2);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-03-20', 'am', '2002-03-25', 'pm', '5.00', 'rien', 'conges', 'refusé', NULL, 3);
INSERT INTO `conges_periode` VALUES ('marie', '2002-06-01', 'am', '2002-06-05', 'pm', '5.00', 'non', 'conges', 'refusé', NULL, 4);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-11-10', 'am', '2002-11-15', 'pm', '5.00', 'essai1', 'conges', 'refusé', NULL, 6);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-11-10', 'am', '2002-11-16', 'pm', '5.00', 'essai1', 'conges', 'ok', NULL, 8);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-11-20', 'am', '2002-11-22', 'pm', '2.00', 'essai', 'conges', 'refusé', NULL, 9);
INSERT INTO `conges_periode` VALUES ('georges', '2004-02-10', 'am', '2004-02-15', 'pm', '3.00', 'recup java 2', 'conges', 'demande', NULL, 22);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-12-05', 'am', '2002-12-06', 'pm', '1.00', 'kjh', 'conges', 'annulé', NULL, 12);
INSERT INTO `conges_periode` VALUES ('cecile', '2003-12-07', 'am', '2003-12-09', 'pm', '1.00', '3 jours', 'conges', 'ok', NULL, 13);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-08', 'am', '2005-03-20', 'pm', '5.00', 'essai v6', 'conges', 'refusé', NULL, 27);
INSERT INTO `conges_periode` VALUES ('georges', '2004-02-09', 'am', '2004-02-01', 'pm', '6.00', 'no comment', 'conges', 'demande', NULL, 21);
INSERT INTO `conges_periode` VALUES ('georges', '2004-02-04', 'am', '2004-02-08', 'pm', '3.00', 'essai record 3', 'conges', 'demande', NULL, 16);
INSERT INTO `conges_periode` VALUES ('marie', '2004-01-28', 'am', '2004-01-31', 'pm', '2.00', 'java33', 'conges', 'ok', NULL, 20);
INSERT INTO `conges_periode` VALUES ('georges', '2004-01-31', 'am', '2004-02-02', 'pm', '3.00', 'java 4', 'conges', 'ok', NULL, 19);
INSERT INTO `conges_periode` VALUES ('cecile', '2004-03-27', 'am', '2004-03-30', 'pm', '3.00', 'voyage', 'conges', 'ok', NULL, 23);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-18', 'am', '2005-03-25', 'pm', '5.00', 'semaine', 'conges', 'demande', NULL, 40);
INSERT INTO `conges_periode` VALUES ('kevin', '2004-03-20', 'am', '2004-03-31', 'pm', '10.00', 'vacances', 'conges', 'ok', NULL, 25);
INSERT INTO `conges_periode` VALUES ('kevin', '2004-03-27', 'am', '2004-03-31', 'pm', '4.00', 'rien', 'conges', 'ok', NULL, 26);
INSERT INTO `conges_periode` VALUES ('cecile', '2004-11-10', 'am', '2004-11-15', 'pm', '3.00', 'rien', 'conges', 'ok', NULL, 28);
INSERT INTO `conges_periode` VALUES ('marie', '2004-11-19', 'am', '2004-11-23', 'pm', '3.00', 'rien', 'conges', 'ok', NULL, 29);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-28', 'am', '2005-03-29', 'pm', '2.00', 'week end prolongé 2', 'conges', 'ok', NULL, 38);
INSERT INTO `conges_periode` VALUES ('kevin', '2004-11-01', 'am', '2004-11-05', 'pm', '5.00', 'essai', 'conges', 'ok', NULL, 32);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-10', 'am', '2005-03-10', 'pm', '1.00', 'garde enfants', 'conges', 'demande', NULL, 39);
INSERT INTO `conges_periode` VALUES ('albert', '2005-02-14', 'am', '2005-02-18', 'pm', '5.00', 'vacances pablo', 'conges', 'annulé', NULL, 33);
INSERT INTO `conges_periode` VALUES ('albert', '2005-02-23', 'am', '2005-02-25', 'pm', '3.00', 'conges2', 'conges', 'annulé', NULL, 34);
INSERT INTO `conges_periode` VALUES ('albert', '2005-02-14', 'am', '2005-02-18', 'pm', '5.00', 'vacances', 'conges', 'ok', NULL, 35);
INSERT INTO `conges_periode` VALUES ('pierre', '2005-02-14', 'am', '2005-02-20', 'pm', '5.00', 'conges xav 1', 'conges', 'annulé', NULL, 36);
INSERT INTO `conges_periode` VALUES ('pierre', '2005-02-25', 'am', '2005-02-25', 'pm', '1.00', 'repos', 'conges', 'ok', NULL, 37);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-24', 'am', '2005-03-25', 'pm', '2.00', 'essa', 'conges', 'ok', NULL, 41);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-14', 'am', '2005-03-15', 'pm', '2.50', 'aqwzsx', 'conges', 'ok', NULL, 42);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-07', 'am', '2005-03-09', 'pm', '2.50', 'essay', 'conges', 'demande', NULL, 43);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-30', 'am', '2005-03-31', 'pm', '1.50', 'essai ajout  cong resp', 'conges', 'annulé', NULL, 44);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-04-04', 'am', '2005-04-06', 'pm', '2.50', 'essai virgule 2', 'conges', 'annulé', NULL, 46);

#
# Contenu de la table `conges_users`
#

INSERT INTO `conges_users` VALUES ('georges', 'simeon', 'georges', '20.00', '10.00', '0.00', '0.00', 'N', 'paolo', 'N', 'c5c6c8e77d4534ba39f5afec86a3a23a', 50, NULL);
INSERT INTO `conges_users` VALUES ('kevin', 'legrand', 'kevin', '40.00', '23.00', '0.00', '0.00', 'N', 'paolo', 'N', '9d5e3ecdeb4cdb7acfd63075ae046672', 100, NULL);
INSERT INTO `conges_users` VALUES ('jean', 'gauthier', 'jean', '40.00', '40.00', '0.00', '0.00', 'N', 'marie', 'N', 'b71985397688d6f1820685dde534981b', 100, NULL);
INSERT INTO `conges_users` VALUES ('pierre', 'point', 'pierre', '40.00', '18.00', '0.00', '0.00', 'N', 'paolo', 'N', '84675f2baf7140037b8f5afe54eef841', 100, NULL);
INSERT INTO `conges_users` VALUES ('cecile', 'fabre', 'cecile', '35.00', '11.50', '0.00', '0.00', 'N', 'marie', 'N', '0231a1bba275eac1ebb37acb638175e1', 80, NULL);
INSERT INTO `conges_users` VALUES ('marie', 'trinte', 'marie', '40.00', '30.00', '0.00', '0.00', 'Y', 'marie', 'Y', 'b3725122c9d3bfef5664619e08e31877', 100, NULL);
INSERT INTO `conges_users` VALUES ('paolo', 'durand', 'paolo', '40.00', '25.00', '0.00', '0.00', 'Y', 'paolo', 'Y', '969044ea4df948fb0392308cfff9cdce', 100, NULL);
INSERT INTO `conges_users` VALUES ('bernard', 'simon', 'bernard', '40.00', '40.00', '0.00', '0.00', 'N', 'marie', 'N', '78d6810e1299959f3a8db157045aa926', 100, NULL);
INSERT INTO `conges_users` VALUES ('albert', 'dupont', 'albert', '35.00', '25.00', '0.00', '0.00', 'N', 'marie', 'N', '6c5bc43b443975b806740d8e41146479', 80, NULL);

#
# Contenu de la table `session_appli_conges`
#

