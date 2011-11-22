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
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
if($config_where_to_find_user_email=="ldap"){ include("../config_ldap.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['user_login'])) { $user_login=$_GET['user_login']; }
	// POST
	/*************************************/

	/************************************/

	echo "<TITLE> Editions Conges : $user_login</TITLE>\n";
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";

	//connexion mysql
	$link = connexion_mysql();
	
	echo "<body text=\"#000000\" bgcolor=$config_bgcolor link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";

	echo "<CENTER>\n";

	affichage($user_login);

	echo "</CENTER>\n";

	mysql_close($link);

?>
</body>
</html>


<?php
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/

function affichage($login)
{
	global $PHP_SELF;
	global $session, $session_username ;
	global $config_rtt_comme_conges ;
	global $link;


	$sql1 = "SELECT u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users where u_login = '$login' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : edit_user.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$sql_nom=$resultat1["u_nom"];
		$sql_prenom=$resultat1["u_prenom"];
		$sql_nb_jours_an=affiche_decimal($resultat1["u_nb_jours_an"]);
		$sql_solde_jours=affiche_decimal($resultat1["u_solde_jours"]);
		$sql_nb_rtt_an=affiche_decimal($resultat1["u_nb_rtt_an"]);
		$sql_solde_rtt=affiche_decimal($resultat1["u_solde_rtt"]);
		$sql_quotite=$resultat1["u_quotite"];
	}

	// TITRE
	echo "<H1>$sql_prenom  $sql_nom  ($login)</H1>\n\n";

	/********************/
	/* Bilan des Conges */
	/********************/
	if($config_rtt_comme_conges==TRUE)
		$taille_tableau_bilan=500;
	else
		$taille_tableau_bilan=300;
	
	printf("<table cellpadding=\"2\" width=\"$taille_tableau_bilan\" class=\"tablo\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">quotité</td><td class=\"titre\">NB CONGES / AN</td><td class=\"titre\">SOLDE CONGES</td>");
	if($config_rtt_comme_conges==TRUE)
		printf("<td class=\"titre\">NB RTT / AN</td><td class=\"titre\">SOLDE RTT</td>");
	printf("</tr>\n");
	printf("<tr align=\"center\">\n");
	echo "<td>$sql_quotite%</td><td><b>$sql_nb_jours_an</b></td><td bgcolor=\"#FF9191\"><b>$sql_solde_jours</b></td>\n";
	if($config_rtt_comme_conges==TRUE)
		echo "<td><b>$sql_nb_rtt_an</b></td><td bgcolor=\"#FF9191\"><b>$sql_solde_rtt</b></td></tr>\n";
	printf("</tr>\n");
	printf("</table>\n");
	printf("<br><br><br>\n");


	affiche_nouvelle_edition($login, $link);
	
	affiche_anciennes_editions($login, $link);

}


function affiche_nouvelle_edition($login, $link)
{

	echo "<CENTER>\n" ;

	/*************************************/
	/* Historique des Conges et demandes */
	/*************************************/
	// Récupération des informations
	$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat ";
	$sql2=$sql2."FROM conges_periode WHERE p_login = '$login' ";
	$sql2=$sql2."AND (p_type='conges' OR  p_type='rtt') ";
	$sql2=$sql2."AND (p_etat='ok' OR  p_etat='annulé' OR  p_etat='refusé' OR  p_etat='ajout') ";
	$sql2=$sql2."AND p_edition_id IS NULL ";
	$sql2=$sql2."ORDER BY p_date_deb ASC ";
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

	printf("<h3>Prochaine Edition :</h3>\n");

	$count2=mysql_num_rows($ReqLog2);
	if($count2==0)
	{
		echo "<b>Aucun congés à éditer pour cet utilisateur ...</b><br>\n";
	}
	else
	{
		// AFFICHAGE TABLEAU
		printf("<table cellpadding=\"2\" class=\"tablo\" width=\"750\">\n");
		echo "<tr align=\"center\">\n";
		echo " <td class=\"titre\">type</td>\n";
		echo " <td class=\"titre\">Etat</td>\n";
		echo " <td class=\"titre\">nb Jours</td>\n";
		echo " <td class=\"titre\">Debut</td>\n";
		echo " <td class=\"titre\">Fin</td>\n";
		echo " <td class=\"titre\">Commentaire</td>\n";
		echo "</tr>\n";
		while ($resultat2 = mysql_fetch_array($ReqLog2)) {
				$sql_p_date_deb = eng_date_to_fr($resultat2["p_date_deb"]);
				$sql_p_demi_jour_deb = $resultat2["p_demi_jour_deb"];
				if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
				$sql_p_date_fin = eng_date_to_fr($resultat2["p_date_fin"]);
				$sql_p_demi_jour_fin = $resultat2["p_demi_jour_fin"];
				if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
				$sql_p_nb_jours = $resultat2["p_nb_jours"];
				$sql_p_commentaire = $resultat2["p_commentaire"];
				$sql_p_type = $resultat2["p_type"];
				$sql_p_etat = $resultat2["p_etat"];

				echo "<tr align=\"center\">\n";
				echo "<td class=\"histo\">$sql_p_type</td>\n" ;
				echo "<td class=\"histo\">$sql_p_etat</td>\n" ;
				if($sql_p_etat=="ok")
					echo "<td class=\"histo-big\"> -$sql_p_nb_jours</td>";
				elseif($sql_p_etat=="ajout")
					echo "<td class=\"histo-big\"> +$sql_p_nb_jours</td>";
				else
					echo "<td class=\"histo\"> $sql_p_nb_jours</td>";
				echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td>";
				echo "<td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td>";
				echo "<td class=\"histo\">$sql_p_commentaire</td>";
				echo "</tr>\n";
		}
		echo "</table>\n";
	
		/******************/
		/* bouton editer  */
		/******************/
		printf("<form action=\"edition.php?session=$session\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"user_login\" value=\"$login\">\n");
		printf("<input type=\"hidden\" name=\"edit_id\" value=\"0\">\n");
		printf("<input type=\"submit\" value=\"Lancer l'édition\">\n");
		printf("</form>\n" ) ;
	}
	echo "<br>\n";
	
	echo "</CENTER>\n";
	echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";
}


function affiche_anciennes_editions($login, $link)
{
	global $config_rtt_comme_conges;
	global $session;
	
	echo "<CENTER>\n" ;

	/*************************************/
	/* Historique des éditions           */
	/*************************************/
	// Récupération des informations
	$sql2 = "SELECT ep_id, ep_date, ep_solde_jours, ep_solde_rtt, ep_num_for_user ";
	$sql2=$sql2."FROM conges_edition_papier WHERE ep_login = '$login' ";
	$sql2=$sql2."ORDER BY ep_num_for_user DESC ";
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

	printf("<h3>Historique des éditions :</h3>\n");

	$count2=mysql_num_rows($ReqLog2);
	if($count2==0)
	{
		echo "<b>Aucune édition enregistrée pour cet utilisateur ...</b><br>\n";
	}
	else
	{
		// AFFICHAGE TABLEAU
		printf("<table cellpadding=\"2\" class=\"tablo\" width=\"750\">\n");
		echo "<tr align=\"center\">\n";
		echo " <td class=\"titre\">Numero</td>\n";
		echo " <td class=\"titre\">Date</td>\n";
		echo " <td class=\"titre\">Solde conges</td>\n";
		if($config_rtt_comme_conges==TRUE)
			echo " <td class=\"titre\">Solde rtt</td>\n";
		echo " <td class=\"titre\"></td>\n";
		echo "</tr>\n";
		
		while ($resultat2 = mysql_fetch_array($ReqLog2)) 
		{
			$sql_id = $resultat2["ep_id"];
			$sql_date = eng_date_to_fr($resultat2["ep_date"]);
			$sql_solde_jours = $resultat2["ep_solde_jours"];
			$sql_solde_rtt = $resultat2["ep_solde_rtt"];
			$sql_num_for_user = $resultat2["ep_num_for_user"];
			
			$text_edit_a_nouveau="<a href=\"edition.php?session=$session&user_login=$login&edit_id=$sql_id\">Editer à nouveau</a>" ;

			echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">$sql_num_for_user</td>\n" ;
			echo "<td class=\"histo-big\">$sql_date</td>";
			echo "<td class=\"histo\">$sql_solde_jours</td>";
			if($config_rtt_comme_conges==TRUE)
				echo "<td class=\"histo\">$sql_solde_rtt</td>";
			echo "<td class=\"histo\">$text_edit_a_nouveau</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
	echo "<br>\n";
		
	echo "</CENTER>\n";
	echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";
}


?>
