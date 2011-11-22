# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Serveur: localhost
# Généré le : Mardi 22 Mars 2005 à 12:28
# Version du serveur: 3.23.54
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
# Contenu de la table `conges_periode`
#

INSERT INTO `conges_periode` VALUES ('cecile', '2002-01-31', 'am', '2004-02-08', 'pm', '7.00', '1 semaine', 'pris', 1);
INSERT INTO `conges_periode` VALUES ('marie', '2010-03-20', 'am', '2015-03-20', 'pm', '6.00', 'rien', 'demande', 2);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-03-20', 'am', '2002-03-25', 'pm', '5.00', 'rien', 'refusé', 3);
INSERT INTO `conges_periode` VALUES ('marie', '2002-06-01', 'am', '2002-06-05', 'pm', '5.00', 'non', 'refusé', 4);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-11-10', 'am', '2002-11-15', 'pm', '5.00', 'essai1', 'refusé', 6);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-11-10', 'am', '2002-11-16', 'pm', '5.00', 'essai1', 'pris', 8);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-11-20', 'am', '2002-11-22', 'pm', '2.00', 'essai', 'refusé', 9);
INSERT INTO `conges_periode` VALUES ('georges', '2004-02-10', 'am', '2004-02-15', 'pm', '3.00', 'recup java 2', 'demande', 22);
INSERT INTO `conges_periode` VALUES ('cecile', '2002-12-05', 'am', '2002-12-06', 'pm', '1.00', 'kjh', 'annulé', 12);
INSERT INTO `conges_periode` VALUES ('cecile', '2003-12-07', 'am', '2003-12-09', 'pm', '1.00', '3 jours', 'pris', 13);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-08', 'am', '2005-03-20', 'pm', '5.00', 'essai v6', 'refusé', 27);
INSERT INTO `conges_periode` VALUES ('georges', '2004-02-09', 'am', '2004-02-01', 'pm', '6.00', 'no comment', 'demande', 21);
INSERT INTO `conges_periode` VALUES ('georges', '2004-02-04', 'am', '2004-02-08', 'pm', '3.00', 'essai record 3', 'demande', 16);
INSERT INTO `conges_periode` VALUES ('marie', '2004-01-28', 'am', '2004-01-31', 'pm', '2.00', 'java33', 'pris', 20);
INSERT INTO `conges_periode` VALUES ('georges', '2004-01-31', 'am', '2004-02-02', 'pm', '3.00', 'java 4', 'pris', 19);
INSERT INTO `conges_periode` VALUES ('cecile', '2004-03-27', 'am', '2004-03-30', 'pm', '3.00', 'voyage', 'pris', 23);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-18', 'am', '2005-03-25', 'pm', '5.00', 'semaine', 'demande', 40);
INSERT INTO `conges_periode` VALUES ('kevin', '2004-03-20', 'am', '2004-03-31', 'pm', '10.00', 'vacances', 'pris', 25);
INSERT INTO `conges_periode` VALUES ('kevin', '2004-03-27', 'am', '2004-03-31', 'pm', '4.00', 'rien', 'pris', 26);
INSERT INTO `conges_periode` VALUES ('cecile', '2004-11-10', 'am', '2004-11-15', 'pm', '3.00', 'rien', 'pris', 28);
INSERT INTO `conges_periode` VALUES ('marie', '2004-11-19', 'am', '2004-11-23', 'pm', '3.00', 'rien', 'pris', 29);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-28', 'am', '2005-03-29', 'pm', '2.00', 'week end prolongé 2', 'pris', 38);
INSERT INTO `conges_periode` VALUES ('kevin', '2004-11-01', 'am', '2004-11-05', 'pm', '5.00', 'essai', 'pris', 32);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-10', 'am', '2005-03-10', 'pm', '1.00', 'garde enfants', 'demande', 39);
INSERT INTO `conges_periode` VALUES ('albert', '2005-02-14', 'am', '2005-02-18', 'pm', '5.00', 'vacances pablo', 'annulé', 33);
INSERT INTO `conges_periode` VALUES ('albert', '2005-02-23', 'am', '2005-02-25', 'pm', '3.00', 'conges2', 'annulé', 34);
INSERT INTO `conges_periode` VALUES ('albert', '2005-02-14', 'am', '2005-02-18', 'pm', '5.00', 'vacances', 'pris', 35);
INSERT INTO `conges_periode` VALUES ('pierre', '2005-02-14', 'am', '2005-02-20', 'pm', '5.00', 'conges xav 1', 'annulé', 36);
INSERT INTO `conges_periode` VALUES ('pierre', '2005-02-25', 'am', '2005-02-25', 'pm', '1.00', 'repos', 'pris', 37);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-24', 'am', '2005-03-25', 'pm', '2.00', 'essa', 'pris', 41);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-14', 'am', '2005-03-15', 'pm', '2.50', 'aqwzsx', 'pris', 42);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-07', 'am', '2005-03-09', 'pm', '2.50', 'essay', 'demande', 43);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-03-30', 'am', '2005-03-31', 'pm', '1.50', 'essai ajout  cong resp', 'annulé', 44);
INSERT INTO `conges_periode` VALUES ('cecile', '2005-04-04', 'am', '2005-04-06', 'pm', '2.50', 'essai virgule 2', 'annulé', 46);

#
# Contenu de la table `conges_users`
#

INSERT INTO `conges_users` VALUES ('georges', 'simeon', 'georges', '20.00', '10.00', '0.00', '0.00', 'N', 'paolo', '11f6955a5cd5c40b', 50, NULL);
INSERT INTO `conges_users` VALUES ('kevin', 'legrand', 'kevin', '40.00', '23.00', '0.00', '0.00', 'N', 'paolo', '74f5868f3aa602a4', 100, NULL);
INSERT INTO `conges_users` VALUES ('jean', 'gauthier', 'jean', '40.00', '40.00', '0.00', '0.00', 'N', 'marie', '355a40243e06e007', 100, NULL);
INSERT INTO `conges_users` VALUES ('pierre', 'point', 'pierre', '40.00', '18.00', '0.00', '0.00', 'N', 'paolo', '22a5d0652c5a1dda', 100, NULL);
INSERT INTO `conges_users` VALUES ('cecile', 'fabre', 'cecile', '35.00', '11.50', '0.00', '0.00', 'N', 'marie', '7d340d671fb7c048', 80, NULL);
INSERT INTO `conges_users` VALUES ('marie', 'trinte', 'marie', '40.00', '30.00', '0.00', '0.00', 'Y', 'marie', '3048aafc376f5d03', 100, NULL);
INSERT INTO `conges_users` VALUES ('paolo', 'durand', 'paolo', '40.00', '25.00', '0.00', '0.00', 'Y', 'paolo', '176f713970475c78', 100, NULL);
INSERT INTO `conges_users` VALUES ('bernard', 'simon', 'bernard', '40.00', '40.00', '0.00', '0.00', 'N', 'marie', '574a63a4440dee07', 100, NULL);
INSERT INTO `conges_users` VALUES ('albert', 'dupont', 'albert', '35.00', '25.00', '0.00', '0.00', 'N', 'marie', '0bb5005056b4f1c0', 80, NULL);

#
# Contenu de la table `session_appli_conges`
#
