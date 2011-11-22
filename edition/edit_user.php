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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

include("fonctions_edition.php") ;

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

//$DEBUG = TRUE ;
$DEBUG = FALSE ;
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<?php

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$user_login = getpost_variable("user_login") ;
	/*************************************/

	/************************************/

	echo "<TITLE> Editions Conges : $user_login</TITLE>\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";

	//connexion mysql
	$mysql_link = connexion_mysql();
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";

	echo "<CENTER>\n";

	affichage($user_login, $mysql_link, $DEBUG);

	echo "</CENTER>\n";

	mysql_close($mysql_link);

?>
</body>
</html>


<?php
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/

function affichage($login, $mysql_link, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();


	$sql1 = "SELECT u_nom, u_prenom, u_quotite FROM conges_users where u_login = '$login' ";
	$ReqLog1 = requete_mysql($sql1, $mysql_link, "affichage", $DEBUG);

	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$sql_nom=$resultat1["u_nom"];
		$sql_prenom=$resultat1["u_prenom"];
		$sql_quotite=$resultat1["u_quotite"];
	}

	// TITRE
	echo "<H1>$sql_prenom  $sql_nom  ($login)</H1>\n\n";

	/********************/
	/* Bilan des Conges */
	/********************/
	// affichage du tableau récapitulatif des solde de congés d'un user
	affiche_tableau_bilan_conges_user($login, $mysql_link, $DEBUG);
	echo "<br><br><br>\n";

	affiche_nouvelle_edition($login, $mysql_link, $DEBUG);
	
	affiche_anciennes_editions($login, $mysql_link, $DEBUG);

}


function affiche_nouvelle_edition($login, $mysql_link, $DEBUG=FALSE)
{
	$session=session_id();

	echo "<CENTER>\n" ;

	/*************************************/
	/* Historique des Conges et demandes */
	/*************************************/
	// Récupération des informations
	// recup de ttes les periodes de type conges du user, sauf les demandes, qui ne sont pas dejà sur une édition papier
	$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, ta_libelle ";
	$sql2=$sql2."FROM conges_periode as a, conges_type_absence as b ";
	$sql2=$sql2."WHERE (p_etat!='demande') ";
	$sql2=$sql2."AND p_edition_id IS NULL ";
	$sql2=$sql2."AND (p_login = '$login') ";
	$sql2=$sql2."AND (a.p_type=b.ta_id AND  b.ta_type='conges') ";
	$sql2=$sql2."ORDER BY p_date_deb ASC ";
	$ReqLog2 = requete_mysql($sql2, $mysql_link, "affiche_nouvelle_edition", $DEBUG);

	echo "<h3>Prochaine Edition :</h3>\n";

	$count2=mysql_num_rows($ReqLog2);
	if($count2==0)
	{
		echo "<b>Aucun congés à éditer pour cet utilisateur ...</b><br>\n";
	}
	else
	{
		// AFFICHAGE TABLEAU
		echo "<table cellpadding=\"2\" class=\"tablo\" width=\"750\">\n";
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
				$sql_p_type = $resultat2["ta_libelle"];
				$sql_p_etat = $resultat2["p_etat"];

				echo "<tr align=\"center\">\n";
				echo "<td class=\"histo\">$sql_p_type</td>\n" ;
				echo "<td class=\"histo\">";
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
				echo "<td class=\"histo\">$sql_p_date_deb _ $demi_j_deb</td>";
				echo "<td class=\"histo\">$sql_p_date_fin _ $demi_j_fin</td>";
				echo "<td class=\"histo\">$sql_p_commentaire</td>";
				echo "</tr>\n";
		}
		echo "</table>\n";
		echo "<br>\n";
			
		/******************/
		/* bouton editer  */
		/******************/
		echo "<table cellpadding=\"2\" width=\"400\">\n";
		echo "<tr align=\"center\">\n";
		echo " <td width=\"200\">\n";
			echo "<a href=\"edition_papier.php?session=$session&user_login=$login&edit_id=0\"><img src=\"../img/fileprint_2.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Lancer l'édition\" alt=\"Lancer l'édition\"><b> Lancer l'édition</b></a>\n";
		echo "</td>\n";
		echo " <td width=\"200\">\n";
			echo "<a href=\"edition_pdf.php?session=$session&user_login=$login&edit_id=0\"><img src=\"../img/pdf_22x22_2.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Edition en PDF\" alt=\"Edition en PDF\"><b> Edition en PDF</b></a>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

	}
	echo "<br>\n";
	
	echo "</CENTER>\n";
	echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";
}


function affiche_anciennes_editions($login, $mysql_link, $DEBUG=FALSE)
{
	$session=session_id();
	
	echo "<CENTER>\n" ;

	// recup du tableau des types de conges (seulement les conges)
	$tab_type_cong=recup_tableau_types_conges($mysql_link);

	/*************************************/
	/* Historique des éditions           */
	/*************************************/
	// Récupération des informations des editions du user
	$tab_editions_user = recup_editions_user($login, $mysql_link, $DEBUG);
	if($DEBUG==TRUE) {echo "tab_editions_user<br>\n"; print_r($tab_editions_user); echo "<br>\n"; }

	echo "<h3>Historique des éditions :</h3>\n";

	if(count($tab_editions_user)==0)
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
		foreach($tab_type_cong as $id_abs => $libelle)
		{
			echo " <td class=\"titre\">Solde $libelle</td>\n";
		}

		echo " <td class=\"titre\"></td>\n";
		echo " <td class=\"titre\"></td>\n";
		echo "</tr>\n";
		
		foreach($tab_editions_user as $id_edition => $tab_ed)
		{			
			//$text_edit_a_nouveau="<a href=\"edition_papier.php?session=$session&user_login=$login&edit_id=$sql_id\">Editer à nouveau</a>" ;
			$text_edit_a_nouveau="<a href=\"edition_papier.php?session=$session&user_login=$login&edit_id=$id_edition\"><img src=\"../img/fileprint_16x16_2.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Editer à nouveau\" alt=\"Editer à nouveau\"> Editer à nouveau</a>\n";
			$text_edit_pdf_a_nouveau="<a href=\"edition_pdf.php?session=$session&user_login=$login&edit_id=$id_edition\"><img src=\"../img/pdf_16x16_2.png\" width=\"16\" height=\"16\" border=\"0\" title=\"Editer à nouveau en PDF\" alt=\"Editer à nouveau en PDF\"> Editer à nouveau en PDF</a>\n";

			echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">".$tab_ed['num_for_user']."</td>\n" ;
			echo "<td class=\"histo-big\">".$tab_ed['date']."</td>";
			foreach($tab_type_cong as $id_abs => $libelle)
			{
				echo "<td class=\"histo\">".$tab_ed['conges'][$id_abs]."</td>";
			}

			echo "<td class=\"histo\">$text_edit_a_nouveau</td>";
			echo "<td class=\"histo\">$text_edit_pdf_a_nouveau</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
	echo "<br>\n";
		
	echo "</CENTER>\n";
	echo "<hr align=\"center\" size=\"2\" width=\"90%\">\n";
}


?>
