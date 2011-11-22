<?php
//session_start();
include("../config.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>

<head>
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<?php
	echo "<body text=\"#000000\" bgcolor=$config_bgcolor link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$URL_ACCUEIL/$config_bgimage\">\n";
	echo "<CENTER>\n";

	// si le user peut saisir ses demandes et qu'il vient d'en saisir une ...
	if(($new_demande_conges==1) && ($config_user_saisie_demande==1)) {
		new_demande($new_debut, $new_fin, $new_nb_jours, $new_comment);
	}
	elseif($change_passwd==1) {
		change_passwd();
	}
	else {
		affichage($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);
	}

function affichage($year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin) {
	global $PHP_SELF;
	global $session, $session_username ;
	global $config_auth, $config_user_saisie_demande, $config_user_ch_passwd;

	if(!isset($year_calendrier_saisie_debut))
		$year_calendrier_saisie_debut=date("Y");
	if(!isset($mois_calendrier_saisie_debut))
		$mois_calendrier_saisie_debut=date("m");
	if(!isset($year_calendrier_saisie_fin))
		$year_calendrier_saisie_fin=date("Y");
	if(!isset($mois_calendrier_saisie_fin))
		$mois_calendrier_saisie_fin=date("m");	
	//echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n";
	
	//connexion mysql
	$link = connexion_mysql();
	
	$sql1 = "SELECT u_nom, u_prenom FROM conges_users where u_login = '$session_username' ";
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : user_main.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
		$NOM=$resultat1["u_nom"];
		$PRENOM=$resultat1["u_prenom"];
	}
	
	// TITRE
	printf("<H1>%s : %s %s</H1>\n\n", $session_username, $PRENOM, $NOM);

	/********************/
	/* Bilan des Conges */
	/********************/
	// Récupération des informations
	$sql1 = "SELECT u_nb_jours_an, u_nb_jours_reste, u_quotite FROM conges_users where u_login = '".$session_username."' ";
	// AFFICHAGE TABLEAU
	printf("<h3>Bilan :</h3>\n");
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"300\">\n");
	printf("<tr align=\"center\"><td class=\"titre\">quotité</td><td class=\"titre\">NB JOURS PAR AN</td><td class=\"titre\">SOLDE</td></tr>\n");
	$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) {
			printf("<tr align=\"center\">\n");
			printf("<td>%d%%</td><td><b>%s</b></td><td bgcolor=\"#FF9191\"><b>%s</b></tr>\n", 
					$resultat1["u_quotite"],  affiche_decimal($resultat1["u_nb_jours_an"]), affiche_decimal($resultat1["u_nb_jours_reste"]) );
			printf("</tr>\n");
		}
	printf("</table>\n");
	printf("<br><br>\n");

	/****************/
	/* calendrier */
	/****************/
	printf("<form action=\"../calendrier.php?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Afficher Calendrier\">\n");
	printf("</form>\n" ) ;
	printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	
	/**************************/
	/* Nouvelle Demande */
	/**************************/
	if($config_user_saisie_demande==1) {
		printf("<H3>Nouvelle Demande :</H3>\n\n");
		
		//affiche le formulaire de saisie d'une nouvelle demande de conges
		saisie_nouveau_conges($session_username, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin);

		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	
			
		/**************************/
		/* Etat demandes en cours */
		/**************************/
		// Récupération des informations
		$sql3 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_num FROM conges_periode WHERE p_login = '".$session_username."' and p_etat = \"demande\" ORDER BY p_date_deb"  ;

		// AFFICHAGE TABLEAU
		printf("<h3>Etat des demandes en cours :</h3>\n");
		printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
		printf("<tr align=\"center\"><td>Debut</td><td>Fin</td><td>NB_Jours_Pris</td><td>Commentaire</td><td></td><td></td></tr>\n");
		$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());
		while ($resultat3 = mysql_fetch_array($ReqLog3)) {
				$user_modif_demande="<a href=\"user_modif_demande.php?session=$session&p_num=".$resultat3["p_num"]."\">Modifier</a>" ;
				$user_suppr_demande="<a href=\"user_suppr_demande.php?session=$session&p_num=".$resultat3["p_num"]."\">Supprimer</a>" ;
				printf("<tr align=\"center\">\n");
				echo "<td>".$resultat3["p_date_deb"]."</td><td>".$resultat3["p_date_fin"]."</td><td>".affiche_decimal($resultat3["p_nb_jours"])."</td><td>".$resultat3["p_commentaire"]."</td><td>".$user_modif_demande."</td><td>".$user_suppr_demande."</td>\n";
//				printf("<td>%s</td><td>%s</td><td>%d</td><td>%s</td><td>%s</td><td>%s</td>\n", $resultat3["p_date_deb"], $resultat3["p_date_fin"], $resultat3["p_nb_jours"], $resultat3["p_commentaire"], $user_modif_demande, $user_suppr_demande);
				printf("</tr>\n");
			}
		printf("</table>\n");
		printf("<br><br>\n\n");
	}
	
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	// Récupération des informations
	$sql2 = "SELECT p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat FROM conges_periode WHERE p_login = '".$session_username."' and p_etat != \"demande\"ORDER BY p_date_deb";
	// AFFICHAGE TABLEAU
	printf("<h3>Etat des congès :</h3>\n");
	printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"80%%\">\n");
	printf("<tr align=\"center\"><td>Debut</td><td>Fin</td><td>nb Jours</td><td>Commentaire</td><td>Etat</td></tr>\n");
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
			printf("<tr align=\"center\">\n");
			printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>\n", 
					$resultat2["p_date_deb"], $resultat2["p_date_fin"], affiche_decimal($resultat2["p_nb_jours"]), $resultat2["p_commentaire"], $resultat2["p_etat"]);
			printf("</tr>\n");
	}
	printf("</table>\n\n");
	printf("<br><br>\n");

	/**************************/
	/* Changer Password */
	/**************************/
	// si  autentification demandée dans config.php et user peut changer son password
	if(($config_auth==1) && ($config_user_ch_passwd==1)) {  
		printf("<hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
		printf("<H3>Changer votre mot de passe :</H3>\n\n", $num_new_demande);

		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<table cellpadding=\"2\" cellspacing=\"3\" border=\"2\" width=\"500\">\n");
		printf("<tr align=\"center\"><td>1iere saisie</td><td>2eme saisie</td></tr>\n");

		$text_passwd1="<input type=\"password\" name=\"new_passwd1\" size=\"10\" maxlength=\"20\" value=\"".$pwd1."\">" ;
		$text_passwd2="<input type=\"password\" name=\"new_passwd2\" size=\"10\" maxlength=\"20\" value=\"".$pwd2."\">" ;
		printf("<tr align=\"center\">\n");
		printf("<td>%s</td><td>%s</td>\n", $text_passwd1, $text_passwd2);
		printf("</tr>\n");

		printf("</table><br>\n");
		printf("<input type=\"hidden\" name=\"change_passwd\" value=1>\n");
		printf("<input type=\"submit\" value=\"Valider\">   <input value=\"cancel\" type=\"reset\">\n");
		printf("</form>\n" ) ;
	}
	
	mysql_close($link);
}

function new_demande($new_debut, $new_fin, $new_nb_jours, $new_comment) {
	global $PHP_SELF;
	//global $MYSQL_HOST, $MYSQL_USER ,$MYSQL_PASSWD, $CONGES_DATABASE;
	global $session, $session_username;
	//global $new_debut, $new_fin, $new_nb_jours, $new_comment ;
	
	// verif validité des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_fin, &$new_nb_jours, $new_comment);
	
	if($valid==TRUE)
	{
		//connexion mysql
		$link = connexion_mysql() ;

		/**********************/
		/* calcul num demande */
		/**********************/
		// Récupération du + grand p_num (+ grand numero identifiant de conges)
		$sql1 = "SELECT max(p_num) FROM conges_periode" ;
		$ReqLog1 = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());
		$num_new_demande = mysql_result($ReqLog1, p_num)+1;

		echo($session_username."---".$new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."---".$num_new_demande."<br>");
		//echo($new_debut."---".$new_fin."---".$new_nb_jours."---".$new_comment."<br>");

		$etat="demande" ;
		$sql1 = "INSERT into conges_periode (p_login, p_date_deb, p_date_fin, p_nb_jours, p_commentaire, p_etat, p_num)
				VALUES ('$session_username','$new_debut','$new_fin','$new_nb_jours','$new_comment','$etat','$num_new_demande')" ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		mysql_close($link);

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	}
	else
	{
			printf(" ERREUR ! Les valeurs saisies sont invalides ou manquantes  !!!<br><br> \n");
	}

		/* RETOUR PAGE PRINCIPALE */
		printf(" <form action=\"user_main.php?session=$session\" method=\"POST\"> \n");
		printf("<input type=\"submit\" value=\"Retour\">\n");
		printf(" </form> \n");

}

function change_passwd() {
	global $PHP_SELF;
	global $session, $session_username;
	global $new_passwd1, $new_passwd2 ;
	
	if((strlen($new_passwd1)==0) || (strlen($new_passwd2)==0) || ($new_passwd1!=$new_passwd2)) {         // si les 2 passwd sont vides ou diffï¿½entes
		echo "ERREUR ! les 2 saisies sont différentes ou vides !!<br>\n" ;
	}
	else {
		//connexion mysql
		$link = connexion_mysql() ;
		
		$sql1 = "UPDATE conges_users SET  u_passwd=password('".$new_passwd1."') WHERE u_login='".$session_username."' " ;

		$result = mysql_query($sql1, $link) or die("ERREUR : mysql_query : ".$sql1." --> ".mysql_error());

		mysql_close($link);

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");
	}
	
	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"user_main.php?session=$session\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");

}


?>
<hr align="center" size="2" width="90%">
<br>
</CENTER>
</body>
</html>
