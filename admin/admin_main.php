<?php
include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
<link href="../style.css" rel="stylesheet" type="text/css">
<TITLE> CONGES : Administration de la database</TITLE>
</head>
<body text=#000000 bgcolor=#FFFFFF link=#000080 vlink=#800080 alink=#FF0000 background="../img/watback.jpg">
<CENTER>

<?php
	// titre
	printf("<H2>Administration de la DataBase :</H2>\n\n");
	//connexion mysql
	$link = connexion_mysql() ;
	
	if(isset($login_new_user)) {
		ajout_user();
	}
	else {
		affichage();  /* affichage normal */
	}
	
	mysql_close($link);
	
/*** FONCTIONS ***/

function affichage() {
	global $PHP_SELF, $link;
	global $config_admin_see_all ;
	global $session;
	global $session_username ;
	global $new_login, $new_nom, $new_prenom, $new_jours_an, $new_jours_reste, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;

	/*********************/
	/* Etat Utilisateurs */
	/*********************/
	// R�cuperation des informations
	if($config_admin_see_all==1) {   # si l'admin peut voir tous les users (cf config.php)
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_is_resp, u_resp_login, u_passwd, u_quotite FROM conges_users ORDER BY u_nom"  ;
	}
	else {
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_is_resp, u_resp_login, u_passwd, u_quotite FROM conges_users WHERE u_resp_login = '$session_username' ORDER BY u_nom, u_prenom"  ;
	}
	// AFFICHAGE TABLEAU
	printf("<h3>Etat des Utilisateurs :</h3>\n");
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td>Nom</td><td>Prenom</td><td>login</td><td>Quotit�</td><td>nb_jours_an</td><td>solde</td><td>is_resp</td><td>resp_login</td><td></td><td></td><td></td></tr>\n");
	$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());
	while ($resultat3 = mysql_fetch_array($ReqLog3)) {
			$admin_modif_user="<a href=\"admin_modif_user.php?session=$session&u_login=".$resultat3["u_login"]."\">Modifier</a>" ;
			$admin_suppr_user="<a href=\"admin_suppr_user.php?session=$session&u_login=".$resultat3["u_login"]."\">Supprimer</a>" ;
			$admin_chg_pwd_user="<a href=\"admin_chg_pwd_user.php?session=$session&u_login=".$resultat3["u_login"]."\">Password</a>" ;
			printf("<tr>\n");
			printf("<td><b>%s</b></td><td><b>%s</b></td><td>%s</td><td>%d%%</td><td>%d</td><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", $resultat3["u_nom"], $resultat3["u_prenom"], $resultat3["u_login"], $resultat3["u_quotite"], $resultat3["u_nb_jours_an"], $resultat3["u_nb_jours_reste"], $resultat3["u_is_resp"], $resultat3["u_resp_login"], $admin_modif_user, $admin_suppr_user, $admin_chg_pwd_user);
			printf("</tr>\n");
		}
	printf("</table>\n\n");

	
	/*********************/
	/* Ajout Utilisateur */
	/*********************/
	
	printf("<br><br><br><hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	// TITRE
	printf("<H3><u>Nouvel Utilisateur : %s</u></H3>\n\n", $login_new_user);

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td>login</td><td>Nom</td><td>Prenom</td><td>Quotit�</td><td>nb_jours_an</td><td>solde</td><td>est_responsable</td><td>responsable</td><td>password</td><td>password</td></tr>\n");

	$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$new_nom."\">" ;
	$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$new_prenom."\">" ;
	$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$new_quotite."\">" ;
	$text_jours_an="<input type=\"text\" name=\"new_jours_an\" size=\"3\" maxlength=\"3\" value=\"".$new_jours_an."\">" ;
	$text_jours_reste="<input type=\"text\" name=\"new_jours_reste\" size=\"3\" maxlength=\"3\" value=\"".$new_jours_reste."\">" ;

	$text_is_resp="<select name=\"new_is_resp\" id=\"is_resp_id\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;
	
	// AFFICHAGE OPTIONS DU SELECT
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" >" ;
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom, u_prenom"  ;
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
			$text_resp_login=$text_resp_login."<option value=\"".$resultat2["u_login"]."\">".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
		}
	$text_resp_login=$text_resp_login."</select>" ;

	$text_password1="<input type=\"password\" name=\"new_password1\" size=\"10\" maxlength=\"15\" value=\"".$password1."\">" ;
	$text_password2="<input type=\"password\" name=\"new_password2\" size=\"10\" maxlength=\"15\" value=\"".$password2."\">" ;
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"10\" value=\"".$new_login."\">" ;
	
	printf("<tr>\n");
	printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n",
	          $text_login, $text_nom, $text_prenom, $text_quotite, $text_jours_an, $text_jours_reste, $text_is_resp, $text_resp_login, $text_password1, $text_password2);
	printf("</tr>\n");
	printf("</table><br>\n\n");
	
	// saisie des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($login_new_user, $link);
	
	echo "<br>\n";
	printf("<input type=\"hidden\" name=\"login_new_user\" value=\"$login_new_user\">\n");
	printf("<input type=\"submit\" value=\"Valider Nouvel Utilisateur\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
}

function ajout_user() {
	global $PHP_SELF, $link;
	global $session;
	global  $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_jours_reste, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	
	if(verif_new_param()==0) {	
	echo "$new_login---$new_nom---$new_prenom---$new_quotite---$new_jours_an---$new_jours_reste---$new_is_resp---$new_resp_login<br>\n";

	$sql1 = "INSERT INTO conges_users (u_login, u_nom, u_prenom, u_nb_jours_an, u_nb_jours_reste, u_is_resp, u_resp_login, u_passwd, u_quotite ) 
			VALUES ('$new_login','$new_nom','$new_prenom', '$new_jours_an',
			'$new_jours_reste','$new_is_resp','$new_resp_login',password('$new_password1'), 
			$new_quotite)" ;
	$result = mysql_query($sql1, $link) or die("ERREUR : ajout_user() : ".$sql1." --> ".mysql_error());

	$list_colums_to_insert="a_login";
	$list_values_to_insert="'$new_login'";
	// on parcours le tableau des jours d'absence semaine impaire
	while($elem_tableau = each($tab_checkbox_sem_imp))
	{
		$champs = $elem_tableau['value'];
		$key=$elem_tableau['key'];          // retourne la key entre quotes (il faut enlever les quotes)
		$pieces = explode("'", $key);
		$unquoted_key=$pieces[1];
		
		$list_colums_to_insert = "$list_colums_to_insert, $unquoted_key";
		$list_values_to_insert = "$list_values_to_insert, '$champs'";
	} 
	// on parcours le tableau des jours d'absence semaine paire
	while($elem_tableau = each($tab_checkbox_sem_p))
	{
		$champs = $elem_tableau['value'];
		$key=$elem_tableau['key'];          // retourne la key entre quotes (il faut enlever les quotes)
		$pieces = explode("'", $key);
		$unquoted_key=$pieces[1];
		
		$list_colums_to_insert = "$list_colums_to_insert, $unquoted_key";
		$list_values_to_insert = "$list_values_to_insert, '$champs'";
	} 
		
	
	$sql2 = "INSERT INTO conges_artt ($list_colums_to_insert) VALUES ($list_values_to_insert)" ;
	$result = mysql_query($sql2, $link) or die("ERREUR : admin_main.php : ".mysql_error());
	
	if($result==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");
	}
}

function verif_new_param() {
	global $PHP_SELF;
	global $session;
	global  $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_jours_reste, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;

	// verif des parametres re�us :
	if((strlen($new_nom)==0)||(strlen($new_prenom)==0)||(strlen($new_jours_an)==0)||(strlen($new_jours_reste)==0)||(strlen($new_password1)==0)||(strlen($new_password2)==0)||(strcmp($new_password1, $new_password2)!=0)||(strlen($new_login)==0)||($new_quotite>100)) {
		printf("<H3> ATTENTION : certain champs saisis ne sont pas valides ...... </H3>\n" ) ;
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
		printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
		printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
		printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
		printf("<input type=\"hidden\" name=\"new_jours_reste\" value=\"$new_jours_reste\">\n");
		printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
		printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
		printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
		
		printf("<input type=\"submit\" value=\"Recommencer\">\n");
		printf("</form>\n" ) ;
		
		return 1;
	}
	else {
		return 0;
	}
}



?>
<hr align="center" size="2" width="90%">
</CENTER>
</body>
</html>
