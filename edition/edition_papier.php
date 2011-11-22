<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Cong�s
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les
termes de la Licence Publique G�n�rale GNU publi�e par la Free Software Foundation.
Ce programme est distribu� car potentiellement utile, mais SANS AUCUNE GARANTIE,
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation
dans un but sp�cifique. Reportez-vous � la Licence Publique G�n�rale GNU pour plus de d�tails.
Vous devez avoir re�u une copie de la Licence Publique G�n�rale GNU en m�me temps
que ce programme ; si ce n'est pas le cas, �crivez � la Free Software Foundation,
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, �tats-Unis.
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

include("fonctions_edition.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

//$DEBUG = TRUE ;
$DEBUG = FALSE ;
	

	/*************************************/
	// recup des parametres re�us :
	// GET / POST
	$user_login = getpost_variable("user_login") ;
	$edit_id = getpost_variable("edit_id", 0) ;
	/*************************************/

	/************************************/

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<TITLE> Impression Etat Conges : $user_login</TITLE>\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	echo "\n<body class=\"edit\">\n";
	echo "<CENTER>\n";

	
	//connexion mysql
	$mysql_link = connexion_mysql();
	
	if($edit_id==0)   // si c'est une nouvelle �dition, on insert dans la base avant d'�diter et on renvoit l'id de l'�dition
		$edit_id=enregistrement_edition($user_login, $mysql_link, $DEBUG);
	
	edition($user_login, $edit_id, $mysql_link, $DEBUG);

	mysql_close($mysql_link);

	echo "</CENTER>\n";
	
	
	
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/

function edition($login, $edit_id, $mysql_link, $DEBUG=FALSE)
{
	$session=session_id();
	
	// recup infos du user
	$tab_info_user=recup_info_user_pour_edition($login, $mysql_link, $DEBUG);

	// recup infos de l'�dition
	$tab_info_edition=recup_info_edition($edit_id, $mysql_link, $DEBUG);
	
	
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
	echo "<H1>".$tab_info_user['nom']."  ".$tab_info_user['prenom']."</H1>\n\n";
	$tab_date=explode("-", $tab_info_edition['date']);
	echo "<H2>bilan au $tab_date[2] / $tab_date[1] / $tab_date[0]</H2>\n\n";
	$quotite=$tab_info_user['quotite'];
	echo "<h3> quotit�&nbsp; : &nbsp;$quotite % </h3>\n" ;


	/****************************/
	/* tableau Bilan des Conges */
	/****************************/
	// affichage du tableau r�capitulatif des solde de cong�s d'un user DE cette edition !
	affiche_tableau_bilan_conges_user_edition($tab_info_user, $tab_info_edition, $mysql_link, $DEBUG);
	echo "<br><br><br>\n";



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

		// R�cup�ration des informations
		// on ne recup QUE les periodes de l'edition choisie
		$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat ";
		$sql2=$sql2."FROM conges_periode ";
		$sql2=$sql2."WHERE p_edition_id = $edit_id ";
		$sql2=$sql2."ORDER BY p_date_deb ASC ";
		$ReqLog2 = requete_mysql($sql2, $mysql_link, "edition", $DEBUG);

		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			echo "<b>Aucun cong�s dans la base de donn�es ...</b><br>\n";
		}
		else
		{
			// recup du tableau des types de conges (seulement les conges)
			$tab_type_cong=recup_tableau_types_conges($mysql_link);
			
			// AFFICHAGE TABLEAU
			echo "<table cellpadding=\"2\" class=\"tablo-edit\" width=\"750\">\n";
			
			/*************************************/
			/* affichage anciens soldes          */
			/*************************************/
			echo "\n<!-- affichage anciens soldes -->\n";
			echo "<tr>\n";
			echo "<td colspan=\"5\">\n";
			$edition_precedente_id=get_id_edition_precedente_user($login, $edit_id, $mysql_link, $DEBUG);
			if($edition_precedente_id==0)
				echo "<b>soldes pr�c�dents inconnus !... ";
			else
			{
				$tab_edition_precedente=recup_info_edition($edition_precedente_id, $mysql_link, $DEBUG);
				foreach($tab_type_cong as $id_abs => $libelle)
				{
					echo "solde pr�c�dent <b>$libelle : ".$tab_edition_precedente['conges'][$id_abs]."</b><br>\n";
				}
			}
			
			echo "<td>\n";
			echo "</tr>\n";


			/*************************************/
			/* affichage lignes de l'edition     */
			/*************************************/
			echo "\n<!-- affichage lignes de l'edition -->\n";
			echo "<tr align=\"center\">\n";
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
					echo "<td class=\"histo-edit\">".$tab_type_cong[$sql_p_type]."</td>\n" ;
					echo "<td class=\"histo-edit\">";
					if($sql_p_etat=="refus")
						echo "refus�";
					elseif($sql_p_etat=="annul")
						echo "annul�";
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
				foreach($tab_type_cong as $id_abs => $libelle)
				{
					echo "nouveau solde <b>$libelle : ".$tab_info_edition['conges'][$id_abs]."</b><br>\n";
				}
			echo "<td>\n";
			echo "</tr>\n";

			echo "</table>\n\n";
		}
	echo "<br><br>\n";
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
		echo "<b>date : <br>signature du responsable :</b><br><i>(et cachet de l'�tablissement)</i><br><br><br><br><br><br><br><br><br>\n" ;
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

<?php

/************************************************************************/
/*   FONCTIONS   */

// affichage du tableau r�capitulatif des solde de cong�s d'un user d'une edition donn�e !
function affiche_tableau_bilan_conges_user_edition($tab_info_user, $tab_info_edition, $mysql_link, $DEBUG=FALSE)
{
	if($DEBUG==TRUE)
	{
		echo "$tab_info_user :<br>\n" ; print_r($tab_info_user) ; echo "<br>\n" ;
		echo "$tab_info_edition :<br>\n" ; print_r($tab_info_edition) ; echo "<br>\n" ;
	}
	
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_cong=recup_tableau_types_conges($mysql_link);
	
	echo "<table cellpadding=\"2\" width=\"250\" class=\"tablo\">\n";
//	echo "<tr align=\"center\"><td class=\"titre\" colspan=\"3\"> quotit� &nbsp; : &nbsp; $quotite % </td></tr>\n" ;
	echo "<tr align=\"center\"><td class=\"titre\"></td><td class=\"titre\"> jours / an </td><td class=\"titre\"> SOLDE</td></tr>\n" ;
	
	foreach($tab_type_cong as $id_abs => $libelle)
		echo "<tr align=\"center\"><td class=\"titre\"> $libelle </td>" .
				"<td class=\"histo\">".$tab_info_user['conges'][$id_abs]['nb_an']."</td>" .
				"<td bgcolor=\"#FF9191\"><b>".$tab_info_edition['conges'][$id_abs]."</b></td>";
	echo "</tr>\n";
	
	echo "</table>\n";
}
/*function affiche_tableau_bilan_conges_user_edition($tab_info_user, $tab_info_edition, $mysql_link, $DEBUG=FALSE)
{
	if($DEBUG==TRUE)
	{
		echo "$tab_info_user :<br>\n" ; print_r($tab_info_user) ; echo "<br>\n" ;
		echo "$tab_info_edition :<br>\n" ; print_r($tab_info_edition) ; echo "<br>\n" ;
	}
	
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_cong=recup_tableau_types_conges($mysql_link);
	
	$quotite=$tab_info_user['quotite'];
	
	// taille du tableau � afficher
	$taille_tableau_bilan=100 + (150 * count($tab_type_cong));
	
	echo "<table cellpadding=\"2\" width=\"$taille_tableau_bilan\" class=\"tablo\">\n";
	echo "<tr align=\"center\"><td class=\"titre\" >quotit�</td>" ;
	foreach($tab_type_cong as $id => $libelle)
		echo "<td class=\"titre\">$libelle / AN</td><td class=\"titre\">SOLDE $libelle</td>" ;
	echo "</tr>\n";
	
	echo "<tr align=\"center\">\n";
	echo "<td>$quotite%</td>\n";
	foreach($tab_type_cong as $id_abs => $libelle)
	{
		echo "<td><b>".$tab_info_user['conges'][$id_abs]['nb_an']."</b></td><td bgcolor=\"#FF9191\"><b>".$tab_info_edition['conges'][$id_abs]."</b></td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
}
*/

?>