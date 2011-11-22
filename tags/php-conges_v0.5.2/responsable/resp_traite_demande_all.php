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
<?php
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	echo "<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";
	echo "<CENTER>\n";
	
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	// POST
	if(isset($_POST['tab_bt_radio'])) { $tab_bt_radio=$_POST['tab_bt_radio']; }
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
	global $config_responsable_virtuel, $config_rtt_comme_conges ;

	// Récupération des informations
	// préparation de la requète :
	$sql1 = "SELECT p_num, p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, u_login, u_nom, u_prenom, u_solde_jours, u_solde_rtt, u_quotite FROM conges_periode, conges_users WHERE ";
	if($config_rtt_comme_conges==1)
		$sql1=$sql1." (p_etat =\"demande\" OR p_etat =\"demande_rtt\") ";
	else	
		$sql1=$sql1." p_etat =\"demande\" ";
	if($config_responsable_virtuel==0)
		$sql1=$sql1." AND conges_users.u_login=conges_periode.p_login AND u_resp_login = '$session_username' ORDER BY u_nom, p_num";
	else
		$sql1=$sql1." AND conges_users.u_login=conges_periode.p_login AND u_login != 'conges' ORDER BY u_nom, p_num";
	
		
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	$count1=mysql_num_rows($ReqLog1);
	if($count1==0)
	{
		echo "<b>Aucune demande de congés en cours dans la base de données ...</b><br><br><br>\n";		
	}
	else
	{
		// AFFICHAGE TABLEAU DES DEMANDES EN COURS
		echo " <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n" ;
		
		echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n" ;
		echo "<tr align=\"center\">\n" ;
		echo "<td class=\"titre\">nom</td><td class=\"titre\">prenom</td><td class=\"titre\">Quotité</td>" ;
		if($config_rtt_comme_conges==1)
			echo "<td class=\"titre\">type</td>" ;
		echo "<td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">Commentaire</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Solde congés</td>" ;
		if($config_rtt_comme_conges==1)
			echo "<td class=\"titre\">Solde rtt</td>" ;
		echo "<td class=\"titre\">Accepter</td><td class=\"titre\">Refuser</td><td class=\"titre\">Attente</td>\n" ;
		echo "</tr>\n";
		
		$tab_bt_radio=array();
		while ($resultat1 = mysql_fetch_array($ReqLog1)) 
		{
			/** sur la ligne ,   **/
			/** le 1er bouton radio est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]-[valeur p_nb_jours]-[valeur p_etat]-OK"> */
			/**  et le 2ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]-[valeur p_nb_jours]-[valeur p_etat]-not_OK"> */
			/**  et le 3ieme est <input type="radio" name="tab_bt_radio[valeur de p_num]" value="[valeur de p_login]-[valeur p_nb_jours]-[valeur p_etat]-RIEN"> */

			$sql_u_nom = $resultat1["u_nom"];
			$sql_u_prenom = $resultat1["u_prenom"];
			$sql_u_quotite = $resultat1["u_quotite"];
			$sql_u_solde_jours = $resultat1["u_solde_jours"];
			$sql_u_solde_rtt = $resultat1["u_solde_rtt"];
			$sql_p_date_deb = $resultat1["p_date_deb"];
			$sql_p_date_fin = $resultat1["p_date_fin"];
			$sql_p_commentaire = $resultat1["p_commentaire"];
			$sql_p_num = $resultat1["p_num"];
			$sql_p_login = $resultat1["p_login"];
			$sql_p_nb_jours = $resultat1["p_nb_jours"];
			$sql_p_etat = $resultat1["p_etat"];
			
			$boutonradio1="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login-$sql_p_nb_jours-$sql_p_etat-OK\">";
			$boutonradio2="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login-$sql_p_nb_jours-$sql_p_etat-not_OK\">";
			$boutonradio3="<input type=\"radio\" name=\"tab_bt_radio[$sql_p_num]\" value=\"$sql_p_login-$sql_p_nb_jours-$sql_p_etat-RIEN\" checked>";
			
			echo "<tr>\n" ;
			printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%d%%</td>", $sql_u_nom, $sql_u_prenom, $sql_u_quotite);
			if($config_rtt_comme_conges==1)
				printf("<td class=\"histo\">%s</td>", $sql_p_etat);
			printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>", $sql_p_date_deb, $sql_p_date_fin, $sql_p_commentaire, affiche_decimal($sql_p_nb_jours), affiche_decimal($sql_u_solde_jours) );
			if($config_rtt_comme_conges==1)
				printf("<td class=\"histo\">%s</td>", affiche_decimal($sql_u_solde_rtt) );
			printf("<td class=\"histo\">%s</td><td class=\"histo\">%s</td><td class=\"histo\">%s</td>\n", $boutonradio1, $boutonradio2, $boutonradio3 );
			
			echo "</tr>\n" ;
		}
		echo "</table>\n\n" ;

		echo "<input type=\"submit\" value=\"Valider\">\n" ;
		echo " </form> \n" ;
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
		$reponse=$champs[3];
		
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		echo "$numero---$user_login---$user_nb_jours_pris---$etat---$reponse<br>";

		/* Modification de la table conges_periode */
		if(strcmp($reponse, "OK")==0)
		{
			/* UPDATE table "conges_periode" et de table "conges_users" (jours restants)*/
			if($etat=="demande")
			{
				$sql1 = "UPDATE conges_periode SET p_etat=\"pris\" WHERE p_num=$numero_int" ;
				$sql2 = "UPDATE conges_users SET u_solde_jours=u_solde_jours-$user_nb_jours_pris WHERE u_login='$user_login' " ;
			}
			else   /// alors $etat=="demande_rtt"
			{
				$sql1 = "UPDATE conges_periode SET p_etat=\"rtt_prise\" WHERE p_num=$numero_int" ;
				$sql2 = "UPDATE conges_users SET u_solde_rtt=u_solde_rtt-$user_nb_jours_pris WHERE u_login='$user_login' " ;
			}
			//echo "$sql1<br>$sql2<br>\n";
			
			/* On valide l'UPDATE dans la table "conges_periode" ! */
			$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

			/* On valide l'UPDATE dans la table "conges_users" ! */
			$ReqLog2 = mysql_query($sql2,$link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
		}
		elseif(strcmp($reponse, "not_OK")==0)
		{
			if($etat=="demande")
				$sql1 = "UPDATE conges_periode SET p_etat=\"refusé\" WHERE p_num=$numero_int" ;
			else   /// alors $etat=="demande_rtt"
				$sql1 = "UPDATE conges_periode SET p_etat=\"rtt_refusée\" WHERE p_num=$numero_int" ;
			//echo "$sql1<br>\n");
			
			/* On valide l'UPDATE dans la table ! */
			$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
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
