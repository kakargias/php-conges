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

session_start();
if(isset($_GET['session'])) { $session=$_GET['session']; }
if(isset($_POST['session'])) { $session=$_POST['session']; }

//include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
//include("../INCLUDE.PHP/session.php");
//if($_SESSION['config']['verif_droits']==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
if($_SESSION['config']['where_to_find_user_email']=="ldap"){ include("../config_ldap.php");}
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
	if(isset($_GET['edit_id'])) { $edit_id=$_GET['edit_id']; }
	// POST
	if(!isset($user_login))
		if(isset($_POST['user_login'])) { $user_login=$_POST['user_login']; }
	if(!isset($edit_id))
		if(isset($_POST['edit_id'])) { $edit_id=$_POST['edit_id']; }
	/*************************************/

	/************************************/

	echo "<TITLE> Impression Etat Conges : $user_login</TITLE>\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";

	
	//connexion mysql
	$mysql_link = connexion_mysql();
	
	echo "\n<body class=\"edit\">\n";

	echo "<CENTER>\n";

	if($edit_id==0)   // si c'est une nouvelle édition, on insert dans la base avant d'éditer et on renvoit l'id de l'édition
		$edit_id=enregistrement_edition($user_login, $mysql_link);
	
	edition($user_login, $edit_id, $mysql_link);

	echo "</CENTER>\n";

	mysql_close($mysql_link);

	
	
	
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/

function edition($login, $edit_id, $mysql_link)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	// recup infos du user
	$sql_user = "SELECT u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users where u_login = '$login' ";
	$ReqLog_user = mysql_query($sql_user, $mysql_link) or die("ERREUR : edition.php : <br>\n".mysql_error());
	while ($resultat_user = mysql_fetch_array($ReqLog_user)) {
		$sql_nom=$resultat_user["u_nom"];
		$sql_prenom=$resultat_user["u_prenom"];
		$sql_nb_jours_an=affiche_decimal($resultat_user["u_nb_jours_an"]);
		$sql_nb_rtt_an=affiche_decimal($resultat_user["u_nb_rtt_an"]);
		$sql_quotite=$resultat_user["u_quotite"];
	}

	// recup infos de l'édition
	$sql_edition= "SELECT ep_date, ep_solde_jours, ep_solde_rtt FROM conges_edition_papier where ep_id = $edit_id ";
	$ReqLog_edition = mysql_query($sql_edition, $mysql_link) or die("ERREUR : edition.php : <br>\n".mysql_error());
	while ($resultat_edition = mysql_fetch_array($ReqLog_edition)) {
		$sql_date=$resultat_edition["ep_date"];
		$sql_solde_jours=affiche_decimal($resultat_edition["ep_solde_jours"]);
		$sql_solde_rtt=affiche_decimal($resultat_edition["ep_solde_rtt"]);
	}
	
	
	/**************************************/
	/* affichage du texte en haut de page */
	/**************************************/
	echo "\n<!-- affichage du texte en haut de page -->\n";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"770\">\n" ;
	echo "<tr align=\"center\">\n";
	echo "<td>".$_SESSION['config']['texte_haut_edition_papier']."<br><br></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	/**************************************/
	/* affichage du TITRE                 */
	/**************************************/
	echo "\n<!-- affichage du TITRE -->\n";
	echo "<H1>$sql_nom  $sql_prenom</H1>\n\n";
	$tab_date=explode("-", $sql_date);
	echo "<H2>bilan au $tab_date[2] / $tab_date[1] / $tab_date[0]</H2>\n\n";

	/****************************/
	/* tableau Bilan des Conges */
	/****************************/
	echo "\n<!-- tableau Bilan des Conges -->\n";
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
		$taille_tableau_bilan=500;
	else
		$taille_tableau_bilan=300;
	
	printf("<table cellpadding=\"2\" width=\"$taille_tableau_bilan\" class=\"tablo\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">quotité</td><td class=\"titre\">NB CONGES / AN</td><td class=\"titre\">SOLDE CONGES</td>");
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
		printf("<td class=\"titre\">NB RTT / AN</td><td class=\"titre\">SOLDE RTT</td>");
	printf("</tr>\n");
	printf("<tr align=\"center\">\n");
	echo "<td>$sql_quotite%</td><td>$sql_nb_jours_an</td><td bgcolor=\"#FF9191\"><b>$sql_solde_jours</b></td>\n";
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
		echo "<td>$sql_nb_rtt_an</td><td bgcolor=\"#FF9191\"><b>$sql_solde_rtt</b></td></tr>\n";
	printf("</tr>\n");
	printf("</table>\n");
	printf("<br><br><br>\n");


	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"770\">\n" ;
	echo "<tr align=\"center\">\n";
	echo "<td><h3>Historique :</h3></td>\n";
	echo "</tr>\n";
	
	/*********************************************/
	/* Tableau Historique des Conges et demandes */
	/*********************************************/
	echo "\n<!-- Tableau Historique des Conges et demandes -->\n";
	echo "<tr align=\"center\">\n";
	echo "<td>\n";

		// Récupération des informations
		$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat ";
		$sql2=$sql2."FROM conges_periode ";
		$sql2=$sql2."WHERE p_login = '$login' ";
		$sql2=$sql2."AND (p_type='conges' OR  p_type='rtt') ";
		$sql2=$sql2."AND (p_etat='ok' OR  p_etat='annul' OR  p_etat='refus' OR  p_etat='ajout') ";
		$sql2=$sql2."AND p_edition_id = $edit_id ";
		$sql2=$sql2."ORDER BY p_date_deb ASC ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			echo "<b>Aucun congés dans la base de données ...</b><br>\n";
		}
		else
		{
			// AFFICHAGE TABLEAU
			printf("<table cellpadding=\"2\" class=\"tablo-edit\" width=\"750\">\n");
			
			/*************************************/
			/* affichage anciens soldes          */
			/*************************************/
			echo "\n<!-- affichage anciens soldes -->\n";
			echo "<tr>\n";
			echo "<td colspan=\"5\">\n";
			$edition_precedente_id=get_num_edition_precedente_user($login, $edit_id, $mysql_link);
			if($edition_precedente_id!=0)
			{
				$sql_solde_prec = "SELECT ep_solde_jours, ep_solde_rtt FROM conges_edition_papier where ep_id=$edition_precedente_id ";
				$ReqLog_solde_prec = mysql_query($sql_solde_prec, $mysql_link) or die("ERREUR : edition.php : <br>\n".mysql_error());
				while ($resultat_solde_prec = mysql_fetch_array($ReqLog_solde_prec))
				{
					$sql_solde_prec_jours=affiche_decimal($resultat_solde_prec["ep_solde_jours"]);
					$sql_solde_prec_rtt=affiche_decimal($resultat_solde_prec["ep_solde_rtt"]);

					echo "<b>solde congés précédent : $sql_solde_prec_jours ";
					if($_SESSION['config']['rtt_comme_conges']==TRUE)
						echo "&nbsp;&nbsp; / &nbsp;&nbsp;solde rtt précédent : $sql_solde_prec_rtt ";
					echo "</b><br>&nbsp;\n";
				}
			}
			else
			{
				echo "<b>solde congés précédent : <i>inconnu</i> ";
				if($_SESSION['config']['rtt_comme_conges']==TRUE)
					echo "&nbsp;&nbsp; / &nbsp;&nbsp;solde rtt précédent : <i>inconnu</i> ";
				echo "</b><br>&nbsp;\n";
			}
			echo "<td>\n";
			echo "</tr>\n";

			/*************************************/
			/* affichage lignes de l'edition     */
			/*************************************/
			echo "\n<!-- affichage lignes de l'edition -->\n";
			echo "<tr align=\"center\">\n";
			if($_SESSION['config']['rtt_comme_conges']==TRUE)
				echo " <td class=\"titre-edit\">Type</td>\n";
			echo " <td class=\"titre-edit\">Etat</td>\n";
			echo " <td class=\"titre-edit\">nb Jours</td>\n";
			echo " <td class=\"titre-edit\">Debut</td>\n";
			echo " <td class=\"titre-edit\">Fin</td>\n";
			echo " <td class=\"titre-edit\">Commentaire</td>\n";
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
					if($_SESSION['config']['rtt_comme_conges']==TRUE)
						echo "<td class=\"histo-edit\">$sql_p_type</td>\n" ;
					echo "<td class=\"histo-edit\">";
					if($sql_p_etat=="refus")
						echo "refusé";
					elseif($sql_p_etat=="annul")
						echo "annulé";
					else
						echo "$sql_p_etat";
					echo "</td>\n" ;
					if($sql_p_etat=="ok")
						echo "<td class=\"histo-big\"> -$sql_p_nb_jours</td>";
					elseif($sql_p_etat=="ajout")
						echo "<td class=\"histo-big\"> +$sql_p_nb_jours</td>";
					else
						echo "<td class=\"histo\"> $sql_p_nb_jours</td>";
					echo "<td class=\"histo-edit\">$sql_p_date_deb _ $demi_j_deb</td>";
					echo "<td class=\"histo-edit\">$sql_p_date_fin _ $demi_j_fin</td>";
					echo "<td class=\"histo-edit\">$sql_p_commentaire</td>";
					echo "</tr>\n";
			}
			
			/*************************************/
			/* affichage nouveaux soldes         */
			/*************************************/
			echo "\n<!-- affichage nouveaux soldes -->\n";
			echo "<tr>\n";
			echo "<td colspan=\"5\">\n";
				echo "<br><b>nouveau solde congés : $sql_solde_jours ";
				if($_SESSION['config']['rtt_comme_conges']==TRUE)
					echo "&nbsp;&nbsp; / &nbsp;&nbsp;nouveau solde rtt : $sql_solde_rtt ";
				echo "</b>\n";
			echo "<td>\n";
			echo "</tr>\n";

			printf("</table>\n\n");
		}
	printf("<br><br>\n");
	echo "</td>\n";

	echo "</tr>\n";
	
	echo "</table>\n";
	
	
	/*************************************/
	/* affichage des zones de signature  */
	/*************************************/
	echo "\n<!-- affichage des zones de signature -->\n";
	echo "<br>\n" ;
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"770\">\n" ;
	echo "<tr align=\"center\">\n";
	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "<td align=\"left\">\n" ;
		echo "<b>date : <br>signature du titulaire :</b><br><br><br><br><br><br><br><br><br><br>\n" ;
	echo "</td>\n";
	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "<td align=\"left\">\n" ;
		echo "<b>date : <br>signature du responsable :</b><br><i>(et cachet de l'établissement)</i><br><br><br><br><br><br><br><br><br>\n" ;
	echo "</td>\n";
	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	
	/*************************************/
	/* affichage du texte en bas de page */
	/*************************************/
	echo "\n<!-- affichage du texte en bas de page -->\n";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"770\">\n" ;
	echo "<tr align=\"center\">\n";
	echo "<td><br>".$_SESSION['config']['texte_bas_edition_papier']."</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
}

function enregistrement_edition($login, $mysql_link)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql1 = "SELECT u_solde_jours, u_solde_rtt FROM conges_users where u_login = '$login' ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : edition.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$sql_solde_jours=$resultat1["u_solde_jours"];
		$sql_solde_rtt=$resultat1["u_solde_rtt"];
	}
	$new_edition_id=get_last_edition_id($mysql_link)+1;
	$aujourdhui = date("Y-m-d");
	$num_for_user=get_num_last_edition_user($login, $mysql_link)+1;

	/*************************************************/
	/* Insertion dans le table conges_edition_papier */
	/*************************************************/
	$sql_insert = "INSERT INTO conges_edition_papier
			SET ep_id=$new_edition_id, ep_login='$login', ep_date='$aujourdhui', ep_solde_jours=$sql_solde_jours, ep_solde_rtt=$sql_solde_rtt, ep_num_for_user=$num_for_user ";
	$result_insert = mysql_query($sql_insert, $mysql_link) or die("ERREUR : enregistrement_edition() : ".$sql_insert." --> ".mysql_error());
	
	
	/********************************************************************************************/
	/* Update du num edition dans la table periode pour les Conges et demandes de cette edition */
	/********************************************************************************************/
	$sql_update = "UPDATE conges_periode SET p_edition_id=$new_edition_id 
			WHERE p_login = '$login' 
			AND p_edition_id IS NULL
			AND (p_type='conges' OR  p_type='rtt')
			AND (p_etat='ok' OR  p_etat='annul' OR  p_etat='refus' OR  p_etat='ajout') ";
		$ReqLog_update = mysql_query($sql_update, $mysql_link) or die("ERREUR : enregistrement_edition() : ".$sql_update." --> ".mysql_error());
	
	return $new_edition_id;
}


// renvoi le + grand id de la table edition_papier (l'id de la derniere edition)
function get_last_edition_id($mysql_link)
{
	// verif si table edition pas vide
	$sql1 = "SELECT ep_id FROM conges_edition_papier ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
	if(mysql_num_rows($ReqLog1)==0) 
		return 0;    // c'est qu'il n'y a pas encore d'edition 
	else
	{
		$sql2 = "SELECT MAX(ep_id) FROM conges_edition_papier ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
		return mysql_result($ReqLog2, 0);
	}	
}

// renvoi le + grand num_par_user de la table edition_papier pour un user donné (le num de la derniere edition du user)
function get_num_last_edition_user($login, $mysql_link)
{
	// verif si le user a une edition
	$sql1 = "SELECT ep_num_for_user FROM conges_edition_papier WHERE ep_login='$login' ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
	if(mysql_num_rows($ReqLog1)==0) 
		return 0;    // c'est qu'il n'y a pas encore d'edition pour ce user
	else
	{
		$sql2 = "SELECT MAX(ep_num_for_user) FROM conges_edition_papier WHERE ep_login='$login' ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
		return mysql_result($ReqLog2, 0);
	}
}


// renvoi le num_par_user de la table edition_papier de l'edition précédente pour un user donné et un edition_id donné.
function get_num_edition_precedente_user($login, $edition_id, $mysql_link)
{
	// verif si le user n'a pas une seule edition
	$sql1 = "SELECT MAX(ep_num_for_user) FROM conges_edition_papier WHERE ep_login='$login' ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : get_num_edition_precedente_user() : ".mysql_error());
	$resultat1 = mysql_fetch_array($ReqLog1) ;
	if($resultat1[0]==1)    // une seule edition pour ce user
		return 0;
	else
	{
		$sql2 = "SELECT MAX(ep_num_for_user) FROM conges_edition_papier WHERE ep_login='$login' AND ep_num_for_user<$edition_id ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : get_num_edition_precedente_user() : ".mysql_error());
		return mysql_result($ReqLog2, 0);
		
	}
}



?>
<br>
<script type="text/javascript" language="javascript1.2">
<!--
// Do print the page
if (typeof(window.print) != 'undefined') {
    window.print();
}
//-->
</script>
</body>
</html>
