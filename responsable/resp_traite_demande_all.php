<?php
/*************************************************************************************************
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

//session_start();
include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==1){ include("../INCLUDE.PHP/verif_droits.php");}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background="../img/watback.jpg">
<CENTER>

<?php
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$HTTP_SERVER_VARS['PHP_SELF'];
	// GET
	// POST
	$tab_bt_radio=$HTTP_POST_VARS['tab_bt_radio'];
	/*************************************/
	
	// titre
	printf("<H2>Traitement des demandes de congès :</H2>\n\n");
	//connexion mysql
	$link = connexion_mysql() ;

	if(!isset($tab_bt_radio)) {
		saisie();
	}
	else {
		traite_demande();
	}
	
	mysql_close($link);
	
/*** FONCTIONS ***/

function saisie() {
	global $PHP_SELF;
	global $session, $link, $session_username ;
	global $config_responsable_virtuel ;

	// Récupération des informations
	if($config_responsable_virtuel==0)
		$sql1 = "SELECT p_num, p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, u_login, u_nom, u_prenom, u_nb_jours_reste, u_quotite FROM conges_periode, conges_users WHERE p_etat =\"demande\" AND conges_users.u_login=conges_periode.p_login AND u_resp_login = '$session_username' ORDER BY u_nom, p_num";
	else
		$sql1 = "SELECT p_num, p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, u_login, u_nom, u_prenom, u_nb_jours_reste, u_quotite FROM conges_periode, conges_users WHERE p_etat =\"demande\" AND conges_users.u_login=conges_periode.p_login AND u_login != 'conges' ORDER BY u_nom, p_num";
	
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	$count1=mysql_num_rows($ReqLog1);
	if($count1==0)
	{
		echo "<b>Aucune demande de congés dans la base de données ...</b><br><br><br>\n";		
	}
	else
	{
		// AFFICHAGE TABLEAU DES DEMANDES EN COURS
		printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
		printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
		printf("<tr align=\"center\"><td class=\"titre\">nom</td><td class=\"titre\">prenom</td><td class=\"titre\">Quotité</td><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td class=\"titre\">Solde</td><td class=\"titre\">Accepter</td><td class=\"titre\">Refuser</td><td class=\"titre\">Attente</td></tr>\n");
		$tab_bt_radio=array();
		while ($resultat1 = mysql_fetch_array($ReqLog1)) {
				/** sur la ligne ,   **/
				/** le 1er bouton radio est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]-[valeur u_nb_jours_reste]-[valeur p_nb_jours]-OK"> */
				/**  et le 2ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]-[valeur u_nb_jours_reste]-[valeur p_nb_jours]-not_OK"> */
				/**  et le 3ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]-[valeur u_nb_jours_reste]-[valeur p_nb_jours]-RIEN"> */

				$boutonradio1=sprintf("<input type=\"radio\" name=\"tab_bt_radio[%s]\" value=\"%s\">", $resultat1["p_num"], $resultat1["p_login"]."-".$resultat1["p_nb_jours"]."-OK" );
				$boutonradio2=sprintf("<input type=\"radio\" name=\"tab_bt_radio[%s]\" value=\"%s\">", $resultat1["p_num"], $resultat1["p_login"]."-".$resultat1["p_nb_jours"]."-not_OK" );
				$boutonradio3=sprintf("<input type=\"radio\" name=\"tab_bt_radio[%s]\" value=\"%s\" checked>", $resultat1["p_num"], $resultat1["p_login"]."-".$resultat1["p_nb_jours"]."-RIEN" );
				printf("<td>%s</td><td>%s</td><td>%d%%</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
						$resultat1["u_nom"], $resultat1["u_prenom"], $resultat1["u_quotite"], $resultat1["p_date_deb"], $resultat1["p_date_fin"], affiche_decimal($resultat1["p_nb_jours"]), $resultat1["p_commentaire"], affiche_decimal($resultat1["u_nb_jours_reste"]), $boutonradio1, $boutonradio2, $boutonradio3 );
				printf("</tr>\n");
			}
		printf("</table>\n\n");

		printf("<input type=\"submit\" value=\"Valider\">\n");
		printf(" </form> \n");
	}

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"resp_main.php?session=$session\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour Page Principale\">\n");
	printf(" </form> \n");
}

function traite_demande() {
	global $PHP_SELF;
	global $session, $link, $tab_bt_radio ;

	while($elem_tableau = each($tab_bt_radio))
	{
		$champs = explode("-", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$etat=$champs[2];
		
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		//echo($numero."---".$user_login."---".$user_nb_jours_pris."---".$etat."<br>");

		/* Modification de la table conges_periode */
		if(strcmp($etat, "OK")==0)
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"pris\" WHERE p_num=$numero_int" ;
			//echo($sql1."<br>");
			/* On valide l'UPDATE dans la table ! */
			$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

			/* UPDATE table "conges_users" (jours restants) */
			$sql2 = "UPDATE conges_users SET u_nb_jours_reste=u_nb_jours_reste-$user_nb_jours_pris WHERE u_login='$user_login' " ;
			//echo($sql2."<br>");
			/* On valide l'UPDATE dans la table ! */
			$ReqLog2 = mysql_query($sql2,$link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		}
		else
		{
			if(strcmp($etat, "not_OK")==0)
			{
				$sql1 = "UPDATE conges_periode SET p_etat=\"refusé\" WHERE p_num=$numero_int" ;
				//echo($sql1."<br>");
				/* On valide l'UPDATE dans la table ! */
				$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
			}
		}
	}

	printf(" Changements pris en compte avec succes !<br><br> \n");
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=resp_main.php?session=$session\">";
}

?>

</CENTER>
</body>
</html>
