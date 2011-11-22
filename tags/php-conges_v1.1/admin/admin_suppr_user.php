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

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG=FALSE;
//$DEBUG=TRUE ;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
<?php 
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<TITLE> ".$_SESSION['config']['titre_admin_index']." </TITLE>\n";
	echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";
	
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$u_login = getpost_variable("u_login") ;
	$u_login_to_delete = getpost_variable("u_login_to_delete") ;
	/*************************************/
	
	// TITRE
	if($u_login!="")
		echo "<H1>Suppression Utilisateur : $u_login .</H1>\n\n";
	elseif($u_login_to_delete!="")
		echo "<H1>Suppression Utilisateur : $u_login_to_delete .</H1>\n\n";

		
	if($u_login!="") 
	{
		confirmer($u_login, $DEBUG);
	}
	elseif($u_login_to_delete!="") 
	{
		suppression($u_login_to_delete, $DEBUG);
	}
	else 
	{
		// renvoit sur la page principale .
		header("Location: admin_index.php?session=$session&onglet=admin-users");
	}


function confirmer($u_login, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;

	/*****************************/
	/* Etat Utilisateur en cours */
	/*****************************/
	// AFFICHAGE TABLEAU
	echo "<form action=\"$PHP_SELF?session=$session&u_login_to_delete=$u_login\" method=\"POST\">\n"  ;
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
	echo "<tr align=\"center\"><td class=\"histo\">login</td><td class=\"histo\">Nom</td><td class=\"histo\">Prenom</td></tr>\n";
	
	// R�cup�ration des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login = '$u_login' "  ;
	$ReqLog1 = requete_mysql($sql1, $mysql_link, "confirmer", $DEBUG);
	
	echo "<tr align=\"center\">\n";
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		echo "<td class=\"histo\">".$resultat1["u_login"]."</td><td class=\"histo\">".$resultat1["u_nom"]."</td><td class=\"histo\">".$resultat1["u_prenom"]."</td>\n"; 
	}
	echo "</tr>\n";
	echo "</table><br>\n\n";
	echo "<input type=\"submit\" value=\"Supprimer\">\n";
	echo "</form>\n" ;
	
	echo "<form action=\"admin_index.php?session=$session&onglet=admin-users\" method=\"POST\">\n"  ;
	echo "<input type=\"submit\" value=\"Cancel\">\n";
	echo "</form>\n" ;

	mysql_close($mysql_link);
}

function suppression($u_login_to_delete, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	//echo($u_login_to_delete."---".$u_login_to_delete."<br>");

	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	$sql1 = "DELETE FROM conges_users WHERE u_login = '$u_login_to_delete' " ;
	$result = requete_mysql($sql1, $mysql_link, "suppression", $DEBUG);

	$sql2 = "DELETE FROM conges_periode WHERE p_login = '$u_login_to_delete' " ;
	$result2 = requete_mysql($sql2, $mysql_link, "suppression", $DEBUG);
	
	$sql3 = "DELETE FROM conges_artt WHERE a_login = '$u_login_to_delete' " ;
	$result3 = requete_mysql($sql3, $mysql_link, "suppression", $DEBUG);
	
	$sql4 = "DELETE FROM conges_echange_rtt WHERE e_login = '$u_login_to_delete' " ;
	$result4 = requete_mysql($sql4, $mysql_link, "suppression", $DEBUG);
	
	$sql5 = "DELETE FROM conges_groupe_resp WHERE gr_login = '$u_login_to_delete' " ;
	$result5 = requete_mysql($sql5, $mysql_link, "suppression", $DEBUG);
	
	$sql6 = "DELETE FROM conges_groupe_users WHERE gu_login = '$u_login_to_delete' " ;
	$result6 = requete_mysql($sql6, $mysql_link, "suppression", $DEBUG);
	
	$sql7 = "DELETE FROM conges_solde_user WHERE su_login = '$u_login_to_delete' " ;
	$result7 = requete_mysql($sql7, $mysql_link, "suppression", $DEBUG);
	
	mysql_close($mysql_link);

	if($result==TRUE)
		echo " Changements pris en compte avec succes !<br><br> \n" ;
	else
		echo " ERREUR ! Changements NON pris en compte !<br><br> \n";
	
	if($DEBUG==TRUE)
	{
		echo "<a href=\"admin_index.php?session=$session&onglet=admin-users\">Retour</a>\n" ;
	}
	else
	{
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session&onglet=admin-users\">";
	}

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>
