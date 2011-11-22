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

/** MAIN **/
	// TITRE
	printf("<H1>Modification utilisateur : %s .</H1>\n\n", $u_login);

	if(isset($u_login)) {
		modifier($u_login);
	}
	else {
		if(isset($u_login_to_update)) {
			commit_update($u_login_to_update);
		}
		else {
			// renvoit sur la page principale .
			header("Location: admin_index.php?session=$session");
		}
	}

function modifier($u_login) {
	global $PHP_SELF;
	global $session;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	/********************/
	/* Etat utilisateur */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_is_resp, u_resp_login, u_quotite FROM conges_users WHERE u_login = '$u_login' " ;
	// AFFICHAGE TABLEAU
	printf("<form action=$PHP_SELF?session=$session&u_login_to_update=".$u_login." method=\"POST\">\n" ) ;
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td>Nom</td><td>Prenom</td><td>login</td><td>Quotité</td><td>nb_jours_an</td><td>solde</td><td>is_resp</td><td>resp_login</td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	printf("<tr>\n");
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
			printf("<td>%s</td><td>%s</td><td>%s</td><td>%d</td><td>%d</td><td>%d</td><td>%s</td><td>%s</td>\n", 
					$resultat1["u_nom"], $resultat1["u_prenom"], $resultat1["u_login"], $resultat1["u_quotite"], $resultat1["u_nb_jours_an"], $resultat1["u_nb_jours_reste"], $resultat1["u_is_resp"], $resultat1["u_resp_login"]);
			$sql_is_resp=$resultat1["u_is_resp"];
			$sql_resp_login=$resultat1["u_resp_login"];
			$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["u_login"]."\">" ;
			$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["u_nom"]."\">" ;
			$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$resultat1["u_prenom"]."\">" ;
			$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$resultat1["u_quotite"]."\">" ;
			$text_nb_j_an="<input type=\"text\" name=\"new_nb_j_an\" size=\"5\" maxlength=\"5\" value=\"".$resultat1["u_nb_jours_an"]."\">" ;
			$text_nb_j_reste="<input type=\"text\" name=\"new_nb_j_reste\" size=\"5\" maxlength=\"5\" value=\"".$resultat1["u_nb_jours_reste"]."\">" ;
		}
	printf("<tr>\n");

	if($sql_is_resp=="Y")
		$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"Y\">Y</option><option value=\"N\">N</option></select>" ;
	else
		$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" >" ;
	// AFFICHAGE OPTIONS DU SELECT
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom,u_prenom"  ;
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
			if($resultat2["u_login"]==$sql_resp_login )
				$text_resp_login=$text_resp_login."<option value=\"".$resultat2["u_login"]."\" selected>".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
			else
				$text_resp_login=$text_resp_login."<option value=\"".$resultat2["u_login"]."\">".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
		}

	$text_resp_login=$text_resp_login."</select>" ;

	printf("<tr>\n");
	printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
			$text_nom, $text_prenom, $text_login, $text_quotite, $text_nb_j_an, $text_nb_j_reste, $text_is_resp, $text_resp_login);
	printf("</tr>\n");
	printf("</table><br>\n\n");
	
	// saisie des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($u_login,$link);
	
	printf("<input type=\"submit\" value=\"Valider\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"admin_index.php?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;

	mysql_close($link);

	}
	
function commit_update($u_login_to_update) {
	global $PHP_SELF;
	global $session;
	global $new_nom, $new_prenom, $new_quotite, $new_nb_j_an, $new_nb_j_reste, $new_is_resp, $new_resp_login, $new_login;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	echo($u_login_to_update."---".$new_nom."---".$new_prenom."---".$new_quotite."---".$new_nb_j_an."---".$new_nb_j_reste."---".$new_is_resp."---".$new_resp_login."---".$new_login."<br>");

	$sql1 = "UPDATE conges_users  SET u_nom='$new_nom', u_prenom='$new_prenom', u_nb_jours_an='$new_nb_j_an', u_nb_jours_reste='$new_nb_j_reste', u_is_resp='$new_is_resp', u_resp_login='$new_resp_login', u_login='$new_login', u_quotite='$new_quotite' WHERE u_login='$u_login_to_update' " ;
	$result = mysql_query($sql1, $link) or die("ERREUR : commit_update() : ".mysql_error());

	$list_update_columns1="";
	$list_update_columns1=$list_update_columns1."sem_imp_lu_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_lu_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_ma_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_ma_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_me_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_me_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_je_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_je_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_ve_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_imp_ve_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_lu_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_lu_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_ma_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_ma_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_me_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_me_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_je_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_je_pm=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_ve_am=NULL, ";
	$list_update_columns1=$list_update_columns1."sem_p_ve_pm=NULL ";
	
	$sql2 = "UPDATE conges_artt SET $list_update_columns1 WHERE a_login='$u_login_to_update' " ;
	//echo "sql2 = $sql2<br>\n";
	$result = mysql_query($sql2, $link) or die("ERREUR : commit_update() : ".mysql_error());
	
	$list_update_columns2="";
	$i=0;
	if(isset($tab_checkbox_sem_imp)) {
		while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
			//echo "$key => $val<br>\n";
//			$pieces = explode("'", $key);
//			$unquoted_key=$pieces[1];
			if($i!=0) $list_update_columns2=$list_update_columns2.", ";
//			$list_update_columns2=$list_update_columns2." $unquoted_key='$val' ";
			$list_update_columns2=$list_update_columns2." $key='$val' ";
			$i=$i+1;
		}
	}
	if(isset($tab_checkbox_sem_p)) {
		while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
			//echo "$key => $val<br>\n";
//			$pieces = explode("'", $key);
//			$unquoted_key=$pieces[1];
			if($i!=0) $list_update_columns2=$list_update_columns2.", ";
//			$list_update_columns2=$list_update_columns2." $unquoted_key='$val' ";
			$list_update_columns2=$list_update_columns2." $key='$val' ";
			$i=$i+1;
		}
	}
	if($list_update_columns2!="")
	{
		$sql3 = "UPDATE conges_artt SET $list_update_columns2 WHERE a_login='$u_login_to_update' " ;
		//echo "sql3 = $sql3<br>\n";
		$result3 = mysql_query($sql3, $link) or die("ERREUR : commit_update() : \n$sql3\n".mysql_error());
	}
	
	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	mysql_close($link);
	
	/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=admin_index.php?session=$session\">";

}

?>
<hr align="center" size="2" width="90%">

</CENTER>
</body>
</html>
