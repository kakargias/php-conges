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
include("../INCLUDE.PHP/session.php");
if($_SESSION['config']['verif_droits']==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
<?php 
	echo "<TITLE> CONGES : Utilisateur ".$_SESSION['userlogin']."</TITLE>\n"; 
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['p_num'])) { $p_num=$_GET['p_num']; }
	if(isset($_GET['onglet'])) { $onglet=$_GET['onglet']; }
	// POST
	if(isset($_POST['p_num_to_delete'])) { $p_num_to_delete=$_POST['p_num_to_delete']; }
	if(!isset($onglet))
		if(isset($_POST['onglet'])) { $onglet=$_POST['onglet']; }
	/*************************************/
	
	// TITRE
	printf("<H1>Suppression demande de conges .</H1>\n\n");
	printf("<br> \n");

	if(isset($p_num)) 
	{
		confirmer($p_num, $onglet);
	}
	else
	{
		if(isset($p_num_to_delete)) 
		{
			suppression($p_num_to_delete, $onglet);
		}
		else 
		{
			// renvoit sur la page principale .
			header("Location: user_index.php");
		}
	}
	
	
function confirmer($p_num, $onglet) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// Récupération des informations
	$sql1 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_num FROM conges_periode WHERE p_num = ".$p_num  ;
	//printf("sql1 = %s<br>\n", $sql1);
	
	// AFFICHAGE TABLEAU
	printf("<form action=\"$PHP_SELF\" method=\"POST\">\n" ) ;
	printf("<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">Debut</td><td class=\"titre\">Fin</td><td class=\"titre\">nb Jours Pris</td><td class=\"titre\">Commentaire</td><td class=\"titre\">Type</td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	printf("<tr align=\"center\">\n");
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$sql_date_deb=eng_date_to_fr($resultat1["p_date_deb"]);
		$sql_demi_jour_deb = $resultat1["p_demi_jour_deb"];
		if($sql_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
		$sql_date_fin=eng_date_to_fr($resultat1["p_date_fin"]);
		$sql_demi_jour_fin = $resultat1["p_demi_jour_fin"];
		if($sql_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
		$sql_nb_jours=affiche_decimal($resultat1["p_nb_jours"]);
		$sql_type=$resultat1["p_type"];
		$sql_comment=$resultat1["p_commentaire"];

		echo "<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td>\n";
		echo "<td class=\"histo\">$sql_date_fin _ $demi_j_fin</td>\n";
		echo "<td class=\"histo\">$sql_nb_jours</td>\n";
		echo "<td class=\"histo\">$sql_comment</td>\n"; 
		echo "<td class=\"histo\">$sql_type</td>\n"; 
	}
	printf("</tr>\n");
	printf("</table><br>\n\n");
	printf("<input type=\"hidden\" name=\"p_num_to_delete\" value=\"$p_num\">\n");
	printf("<input type=\"hidden\" name=\"session\" value=\"$session\">\n");
	printf("<input type=\"hidden\" name=\"onglet\" value=\"$onglet\">\n");
	printf("<input type=\"submit\" value=\"Supprimer\">\n");
	printf("</form>\n" ) ;
	
	printf("<form action=\"user_index.php?session=$session&onglet=$onglet\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
	
	mysql_close($mysql_link);

}

function suppression($p_num_to_delete, $onglet) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id() ;
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;

	$sql_delete = "DELETE FROM conges_periode 
		WHERE p_num = $p_num_to_delete AND p_etat='demande' AND p_login='".$_SESSION['userlogin']."' ;" ;

	$result_delete = mysql_query($sql_delete, $mysql_link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

	if($result_delete==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"user_index.php?session=$session&onglet=$onglet\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");
	
	mysql_close($mysql_link);

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>
