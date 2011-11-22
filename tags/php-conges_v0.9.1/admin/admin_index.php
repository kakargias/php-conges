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
include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<html>
<head>
<?php 
	echo "<link href=\"../$config_stylesheet_file\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<TITLE> $config_titre_admin_index </TITLE>\n";
	echo "</head>\n";
	
	echo "<body text=#000000 bgcolor=$config_bgcolor link=#000080 vlink=#800080 alink=#FF0000 background=\"$URL_ACCUEIL_CONGES/$config_bgimage\">\n";
	echo "<CENTER>\n";
	
	/*** initialisation des variables ***/
	$onglet="admin-users";
	$saisie_user="";
	$saisie_group="";
	$new_password1="";
	$new_password2="";
	$change_group_users="";
	$change_user_groups="";
	$change_group_responsables="";
	$change_responsable_group="";
	/************************************/

	/*************************************/
	/* recup des parametres reçus :  */
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['onglet'])) { $onglet=$_GET['onglet']; }
	if(isset($_GET['choix_group'])) { $choix_group=$_GET['choix_group']; }
	if(isset($_GET['choix_resp'])) { $choix_resp=$_GET['choix_resp']; }
	if(isset($_GET['choix_user'])) { $choix_user=$_GET['choix_user']; }
	if(isset($_GET['choix_gestion_groupes_responsables'])) { $choix_gestion_groupes_responsables=$_GET['choix_gestion_groupes_responsables']; }
	if(isset($_GET['choix_gestion_groupes_users'])) { $choix_gestion_groupes_users=$_GET['choix_gestion_groupes_users']; }
	// POST
	if(isset($_POST['saisie_user'])) { $saisie_user=$_POST['saisie_user']; }
	if(isset($_POST['saisie_group'])) { $saisie_group=$_POST['saisie_group']; }
	// si on recupere les users dan sldap et qu'on vient d'en créer un depuis la liste déroulante
	if ($config_how_to_connect_user == "ldap" && isset($_POST['new_ldap_user']))
	{
		$new_login=$_POST['new_ldap_user'];
		// cnx à l'annuaire ldap :
		$ds = ldap_connect($config_ldap_server);
		//ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) ;
		if ($config_ldap_user == "")
			$bound = ldap_bind($ds);
		else 
			$bound = ldap_bind($ds, $config_ldap_user, $config_ldap_pass);

		// recherche des entrées :
		$filter = "(".$config_ldap_login."=".$new_login.")";   

		$sr   = ldap_search($ds, $config_searchdn, $filter);
		$data = ldap_get_entries($ds,$sr);

		foreach ($data as $info)
		{
			$new_prenom = utf8_decode($info[$config_ldap_prenom][0]);
			$new_nom = utf8_decode($info[$config_ldap_nom][0]);
			//print_r($info);
		}
	}
	else
	{
		if(isset($_POST['new_login'])) { $new_login=$_POST['new_login']; }
		if(isset($_POST['new_nom'])) { $new_nom=$_POST['new_nom']; }
		if(isset($_POST['new_prenom'])) { $new_prenom=$_POST['new_prenom']; }
	}
	if(isset($_POST['new_quotite'])) { $new_quotite=$_POST['new_quotite']; }
	if(isset($_POST['new_jours_an'])) { $new_jours_an=$_POST['new_jours_an']; }
	if(isset($_POST['new_solde_jours'])) { $new_solde_jours=$_POST['new_solde_jours']; }
	if(isset($_POST['new_rtt_an'])) { $new_rtt_an=$_POST['new_rtt_an']; }
	if(isset($_POST['new_solde_rtt'])) { $new_solde_rtt=$_POST['new_solde_rtt']; }
	if(isset($_POST['new_is_resp'])) { $new_is_resp=$_POST['new_is_resp']; }
	if(isset($_POST['new_resp_login'])) { $new_resp_login=$_POST['new_resp_login']; }
	if(isset($_POST['new_is_admin'])) { $new_is_admin=$_POST['new_is_admin']; }
	if ($config_how_to_connect_user == "dbconges")
	{
		if(isset($_POST['new_password1'])) { $new_password1=$_POST['new_password1']; }
		if(isset($_POST['new_password2'])) { $new_password2=$_POST['new_password2']; }
	}
	if(isset($_POST['new_email'])) { $new_email=$_POST['new_email']; }
	if(isset($_POST['tab_checkbox_sem_imp'])) { $tab_checkbox_sem_imp=$_POST['tab_checkbox_sem_imp']; }
	if(isset($_POST['tab_checkbox_sem_p'])) { $tab_checkbox_sem_p=$_POST['tab_checkbox_sem_p']; }
	if(isset($_POST['new_jour'])) { $new_jour=$_POST['new_jour']; }
	if(isset($_POST['new_mois'])) { $new_mois=$_POST['new_mois']; }
	if(isset($_POST['new_year'])) { $new_year=$_POST['new_year']; }
	if(isset($_POST['new_group_name'])) { $new_group_name=addslashes($_POST['new_group_name']); }
	if(isset($_POST['new_group_libelle'])) { $new_group_libelle=addslashes($_POST['new_group_libelle']); }
	if(isset($_POST['change_group_users'])) { $change_group_users=$_POST['change_group_users']; }
	if(isset($_POST['checkbox_group_users'])) { $checkbox_group_users=$_POST['checkbox_group_users']; }
	if(isset($_POST['change_user_groups'])) { $change_user_groups=$_POST['change_user_groups']; }
	if(isset($_POST['checkbox_user_groups'])) { $checkbox_user_groups=$_POST['checkbox_user_groups']; }
	if(isset($_POST['change_group_responsables'])) { $change_group_responsables=$_POST['change_group_responsables']; }
	if(isset($_POST['checkbox_group_resp'])) { $checkbox_group_resp=$_POST['checkbox_group_resp']; }
	if(isset($_POST['change_responsable_group'])) { $change_responsable_group=$_POST['change_responsable_group']; }
	if(isset($_POST['checkbox_resp_group'])) { $checkbox_resp_group=$_POST['checkbox_resp_group']; }
	if( (!isset($onglet)) || ($onglet=="") )
		if(isset($_POST['onglet'])) { $onglet=$_POST['onglet']; }
	if( (!isset($choix_group)) || ($choix_group=="") )
		if(isset($_POST['choix_group'])) { $choix_group=$_POST['choix_group']; }
	if( (!isset($choix_resp)) || ($choix_resp=="") )
		if(isset($_POST['choix_resp'])) { $choix_resp=$_POST['choix_resp']; }
	if( (!isset($choix_user)) || ($choix_user=="") )
		if(isset($_POST['choix_user'])) { $choix_user=$_POST['choix_user']; }
	if( (!isset($choix_gestion_groupes_responsables)) || ($choix_gestion_groupes_responsables=="") )
		if(isset($_POST['choix_gestion_groupes_responsables'])) { $choix_gestion_groupes_responsables=$_POST['choix_gestion_groupes_responsables']; }
	if( (!isset($choix_gestion_groupes_users)) || ($choix_gestion_groupes_users=="") )
		if(isset($_POST['choix_gestion_groupes_users'])) { $choix_gestion_groupes_users=$_POST['choix_gestion_groupes_users']; }
	
	/* FIN de la recup des parametres    */
	/*************************************/
	
	
	/*******************************************************/
	/*** affichage des boutons  et titre en haut de page ***/
	/*******************************************************/
	echo "<!-- affichage des boutons  et titre en haut de page -->\n";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
	echo "<tr>\n";
	/*** cellule vide  ***/
	echo "<td width=\"220\" valign=\"middle\">&nbsp;</td>\n";
	
	/*** cellule centrale Titre  ***/
	echo "<td valign=\"bottom\" align=\"center\">\n";
	echo "<H2>Administration de la DataBase : </H2>\n";
	echo "</td>\n";
	
	/*** bouton db_sauvegarde  ***/
	echo "<td width=\"220\" align=\"right\" valign=\"top\">\n";
	echo " <a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('admin_db_sauve.php?session=$session','mapage',400,300);\"><img src=\"../img/floppy_22x22.png\" width=\"22\" height=\"22\" border=\"0\" title=\"Sauvegarde/Restauration Database\" alt=\"Sauvegarde/Restauration Database\"></a> Sauvegarde/Restauration Database\n";
	echo "</td>\n";

	echo "</tr>\n";
	echo "</table>\n";


	
	/*************************************/
	/***  suite de la page             ***/
	/*************************************/
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	if($saisie_user=="ok") {
		ajout_user();
	}
	elseif($saisie_group=="ok") {
		ajout_groupe();
	}
	elseif($change_group_users=="ok")
	{
		modif_group_users();
	}
	elseif($change_user_groups=="ok")
	{
		modif_user_groups();
	}
	elseif($change_group_responsables=="ok")
	{
		modif_group_responsables();
	}
	elseif($change_responsable_group=="ok")
	{
		modif_resp_groupes();
	}
	else {
		affichage($onglet);  /* affichage normal */
	}
	
	mysql_close($link);
	
?>
<hr align="center" size="2" width="90%">
</CENTER>
<SCRIPT LANGUAGE="JavaScript1.2">
<!--
function OpenPopUp(MyFile,MyWindow,MyWidth,MyHeight)
//MyFile :	  nom du fichier contenant le code HTML du pop-up
//MyWindow :	  nom de la fenêtre (ne pas mettre d'espace)
//MyWidth :	  entier indiquant la largeur de la fenêtre en pixels
//MyHeight :	  entier indiquant la hauteur de la fenêtre en pixels

{	  
var ns4 = (document.layers)? true:false; 	  //NS 4
var ie4 = (document.all)? true:false;	  //IE 4
var dom = (document.getElementById)? true:false;	  //DOM
var xMax, yMax, xOffset, yOffset;;	  

    if (ie4 || dom)
        {
        xMax = screen.width;
        yMax = screen.height;
        }
    else if (ns4)
        {
        xMax = window.outerWidth;
        yMax = window.outerHeight;
        }
    else
        {
        xMax = 800;
        yMax = 600;
        }
    xOffset = (xMax - MyWidth)/2;
    yOffset = (yMax - MyHeight)/2;
    window.open(MyFile,MyWindow,'width='+MyWidth
+',height='+MyHeight
+',screenX='+xOffset
+',screenY='+yOffset
+',top='+yOffset
+',left='+xOffset
+',scrollbars=yes,resizable=yes');
    }
//-->
</SCRIPT>
</body>
</html>


<?php
/*********************************************************************************/
/*  FONCTIONS   */
/*********************************************************************************/

function affichage($onglet) {
	global $PHP_SELF, $link;
	global $config_admin_see_all , $config_responsable_virtuel, $config_rtt_comme_conges, $config_where_to_find_user_email;
	global $config_gestion_groupes;
	global $session;
	global $session_username ;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_email ;
	global $new_rtt_an, $new_solde_rtt, $new_is_resp, $new_resp_login, $new_password1, $new_password2 ;
	global $new_group_name, $new_group_libelle ;

	
	
	/*** AFFICHAGE DES ONGLETS...  ***/
	// on affiche les onglets seulement si la gestion de groupe est activée
	if($config_gestion_groupes==TRUE)
	{
		echo "</center>\n" ;
		echo "<!-- affichage des onglets -->\n";
		
		echo "<table cellpadding=\"1\" cellspacing=\"2\" border=\"1\">\n" ;
		echo "<tr align=\"center\">\n";
			if($onglet!="admin-users")
				echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-users\" class=\"bouton-onglet\"> Gestion des Utilisateurs </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-users\" class=\"bouton-current-onglet\"> Gestion des Utilisateurs </a></td>\n";
			
			if($onglet!="admin-group")
				echo "<td class=\"onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group\" class=\"bouton-onglet\"> Gestion des Groupes </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"170\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group\" class=\"bouton-current-onglet\"> Gestion des Groupes </a></td>\n";
			
			if($onglet!="admin-group-users")
				echo "<td class=\"onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-users\" class=\"bouton-onglet\"> Gestion Groupes <-> Utilisateurs </a></td>\n";
			else
				echo "<td class=\"current-onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-users\" class=\"bouton-current-onglet\"> Gestion Groupes <-> Utilisateurs </a></td>\n";
			
			if($config_responsable_virtuel==FALSE)
			{
				if($onglet!="admin-group-responsables")
					echo "<td class=\"onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables\" class=\"bouton-onglet\"> Gestion Groupes <-> Responsables </a></td>\n";
				else
					echo "<td class=\"current-onglet\" width=\"250\"><a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables\" class=\"bouton-current-onglet\"> Gestion Groupes <-> Responsables </a></td>\n";
			}
		echo "</tr>\n";
		echo "</table>\n" ;
	}
	
	
	
	echo "<!-- AFFICHAGE DE LA PAGE DEMANDéE -->\n";
	echo "<center>\n" ;
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\">\n" ;
	/**************************************/
	/*** AFFICHAGE DE LA PAGE DEMANDéE  ***/
	echo "<tr align=\"center\">\n";
	echo "	<td>\n";

	/**********************/
	/* ADMIN Utilisateurs */
	/**********************/
	if($onglet=="admin-users")
	{
		affiche_gestion_utilistaeurs();
	}
	/**********************/
	/* ADMIN Groupes */
	/**********************/
	elseif($onglet=="admin-group")
	{
		affiche_gestion_groupes();	
	}
	/********************************/
	/* ADMIN Groupes<->Utilisateurs */
	/********************************/
	elseif($onglet=="admin-group-users")
	{
		affiche_choix_gestion_groupes_users();	
	}
	/********************************/
	/* ADMIN Groupes<->Responsables */
	/********************************/
	elseif($onglet=="admin-group-responsables")
	{
		affiche_choix_gestion_groupes_responsables();
	}
	
	echo "	</td>\n";
	echo "</tr>\n";
	/*** FIN AFFICHAGE DE LA PAGE DEMANDéE  ***/
	/******************************************/
	echo "</table>\n";
	echo "</CENTER>\n";
	
}


function ajout_user() {
	global $PHP_SELF, $link;
	global $session;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt ;
	global $new_is_resp, $new_resp_login, $new_is_admin, $new_password1, $new_password2, $new_email ;
	global $tab_checkbox_sem_imp, $tab_checkbox_sem_p ;
	global $new_jour, $new_mois, $new_year ;
	
	if(verif_new_param()==0) 
	{
		echo "$new_login---$new_nom---$new_prenom---$new_quotite---$new_jours_an---$new_solde_jours---$new_rtt_an---$new_solde_rtt---$new_is_resp---$new_resp_login---$new_is_admin---$new_email<br>\n";
		$new_date_deb_grille="$new_year-$new_mois-$new_jour";
		echo "$new_date_deb_grille<br>\n" ;

		$motdepasse = md5($new_password1);
		$sql1 = "INSERT INTO conges_users SET ";
		$sql1=$sql1."u_login='$new_login', ";
		$sql1=$sql1."u_nom='$new_nom', ";
		$sql1=$sql1."u_prenom='$new_prenom', ";
		$sql1=$sql1."u_nb_jours_an='$new_jours_an', ";
		$sql1=$sql1."u_solde_jours='$new_solde_jours', ";
		$sql1=$sql1."u_nb_rtt_an='$new_rtt_an', ";
		$sql1=$sql1."u_solde_rtt='$new_solde_rtt', ";
		$sql1=$sql1."u_is_resp='$new_is_resp', ";
		$sql1=$sql1."u_resp_login='$new_resp_login', ";
		$sql1=$sql1."u_is_admin='$new_is_admin', ";
		$sql1=$sql1."u_passwd='$motdepasse', ";
		$sql1=$sql1."u_quotite=$new_quotite,";
		$sql1=$sql1." u_email='$new_email' "; 
		$result = mysql_query($sql1, $link) or die("ERREUR : ajout_user() : ".$sql1." --> ".mysql_error());

		$list_colums_to_insert="a_login";
		$list_values_to_insert="'$new_login'";
		// on parcours le tableau des jours d'absence semaine impaire
		if(isset($tab_checkbox_sem_imp)) {
			while (list ($key, $val) = each ($tab_checkbox_sem_imp)) {
				//echo "$key => $val<br>\n";
				$list_colums_to_insert="$list_colums_to_insert, $key";
				$list_values_to_insert="$list_values_to_insert, '$val'";
			}
		}
		if(isset($tab_checkbox_sem_p)) {
			while (list ($key, $val) = each ($tab_checkbox_sem_p)) {
				//echo "$key => $val<br>\n";
				$list_colums_to_insert="$list_colums_to_insert, $key";
				$list_values_to_insert="$list_values_to_insert, '$val'";
			}
		}


		$sql2 = "INSERT INTO conges_artt ($list_colums_to_insert, a_date_debut_grille) VALUES ($list_values_to_insert, '$new_date_deb_grille')" ;
		$result = mysql_query($sql2, $link) or die("ERREUR : admin_index.php : ajout_user() : \n$sql2\n".mysql_error());

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

		/* APPEL D'UNE AUTRE PAGE */
		printf(" <form action=\"$PHP_SELF?session=$session&onglet=admin-users\" method=\"POST\"> \n");
		printf("<input type=\"submit\" value=\"Retour\">\n");
		printf(" </form> \n");
	}
}


function verif_new_param() {
	global $PHP_SELF, $link;
	global $session;
	global $config_rtt_comme_conges, $config_how_to_connect_user, $config_where_to_find_user_email;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_rtt_an, $new_solde_rtt ;
	global $new_is_resp, $new_resp_login, $new_is_admin, $new_password1, $new_password2, $new_email ;

	if($config_rtt_comme_conges==FALSE)
		$new_rtt_an=0;
	else
		$valid=verif_saisie_decimal($new_rtt_an);   //verif la bonne saisie du nombre décimal
		
	if($config_rtt_comme_conges==FALSE)
		$new_solde_rtt=0;
	else
		$valid=verif_saisie_decimal($new_solde_rtt);   //verif la bonne saisie du nombre décimal

	$valid=verif_saisie_decimal($new_jours_an);       //verif la bonne saisie du nombre décimal
	$valid=verif_saisie_decimal($new_solde_jours);    //verif la bonne saisie du nombre décimal
		
	// verif des parametres reçus :
	// si on travaille avec la base dbconges, on teste tout, mais si on travaille avec ldap, on ne teste pas les champs qui viennent de ldap ...
	if(	($config_how_to_connect_user == "dbconges" &&
		(strlen($new_nom)==0 || strlen($new_prenom)==0 || strlen($new_jours_an)==0 || strlen($new_solde_jours)==0 || strlen($new_password1)==0 || strlen($new_password2)==0 || strcmp($new_password1, $new_password2)!=0 || strlen($new_login)==0 || $new_quotite>100))
		|| ($config_how_to_connect_user == "ldap" &&
		(strlen($new_jours_an)==0 || strlen($new_solde_jours)==0 || strlen($new_login)==0 || $new_quotite>100)))
	{
		printf("<H3> ATTENTION : certain champs saisis ne sont pas valides ...... </H3>\n" ) ;
		echo "$new_login---$new_nom---$new_prenom---$new_quotite---$new_jours_an---$new_solde_jours---$new_rtt_an---$new_solde_rtt---$new_is_resp---$new_resp_login<br>\n";
		printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
		printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
		printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
		printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
		printf("<input type=\"hidden\" name=\"new_solde_jours\" value=\"$new_solde_jours\">\n");
		printf("<input type=\"hidden\" name=\"new_rtt_an\" value=\"$new_rtt_an\">\n");
		printf("<input type=\"hidden\" name=\"new_solde_rtt\" value=\"$new_solde_rtt\">\n");
		printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
		printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
		printf("<input type=\"hidden\" name=\"new_is_admin\" value=\"$new_is_admin\">\n");
		printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
		printf("<input type=\"hidden\" name=\"new_email\" value=\"$new_email\">\n");
		
		printf("<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n");
		printf("<input type=\"submit\" value=\"Recommencer\">\n");
		printf("</form>\n" ) ;
		
		return 1;
	}
	else {
		// verif si le login demandé n'existe pas déjà ....
		$sql_verif="select u_login from conges_users where u_login='$new_login' ";
		$ReqLog_verif = mysql_query($sql_verif, $link) or die("ERREUR : mysql_query : \n".$sql_verif."\n --> ".mysql_error());
		$num_verif = mysql_num_rows($ReqLog_verif);
		if ($num_verif!=0)
		{
			printf("<H3> ATTENTION : login déjà utilisé, veuillez en changer ...... </H3>\n" ) ;
			printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
			printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
			printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
			printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
			printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_jours\" value=\"$new_solde_jours\">\n");
			printf("<input type=\"hidden\" name=\"new_rtt_an\" value=\"$new_rtt_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_rtt\" value=\"$new_solde_rtt\">\n");
			printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
			printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
			printf("<input type=\"hidden\" name=\"new_is_admin\" value=\"$new_is_admin\">\n");
			printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
			printf("<input type=\"hidden\" name=\"new_email\" value=\"$new_email\">\n");

			printf("<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n");
			printf("<input type=\"submit\" value=\"Recommencer\">\n");
			printf("</form>\n" ) ;

			return 1;	
		}
		elseif($config_where_to_find_user_email == "dbconges" && strrchr($new_email, "@")==FALSE)
		{
			printf("<H3> ATTENTION : adresse mail éronnée ...... </H3>\n" ) ;
			printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
			printf("<input type=\"hidden\" name=\"new_login\" value=\"$new_login\">\n");
			printf("<input type=\"hidden\" name=\"new_nom\" value=\"$new_nom\">\n");
			printf("<input type=\"hidden\" name=\"new_prenom\" value=\"$new_prenom\">\n");
			printf("<input type=\"hidden\" name=\"new_jours_an\" value=\"$new_jours_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_jours\" value=\"$new_solde_jours\">\n");
			printf("<input type=\"hidden\" name=\"new_rtt_an\" value=\"$new_rtt_an\">\n");
			printf("<input type=\"hidden\" name=\"new_solde_rtt\" value=\"$new_solde_rtt\">\n");
			printf("<input type=\"hidden\" name=\"new_is_resp\" value=\"$new_is_resp\">\n");
			printf("<input type=\"hidden\" name=\"new_resp_login\" value=\"$new_resp_login\">\n");
			printf("<input type=\"hidden\" name=\"new_is_admin\" value=\"$new_is_admin\">\n");
			printf("<input type=\"hidden\" name=\"new_quotite\" value=\"$new_quotite\">\n");
			printf("<input type=\"hidden\" name=\"new_email\" value=\"$new_email\">\n");

			printf("<input type=\"hidden\" name=\"saisie_user\" value=\"faux\">\n");
			printf("<input type=\"submit\" value=\"Recommencer\">\n");
			printf("</form>\n" ) ;

			return 1;	
		}
		else
			return 0;
	}
}



function affiche_gestion_utilistaeurs()
{
	global $PHP_SELF, $link;
	global $config_admin_see_all, $config_admin_change_passwd , $config_responsable_virtuel, $config_gestion_groupes;
	global $config_rtt_comme_conges, $config_where_to_find_user_email, $config_how_to_connect_user ;
	global $session;
	global $session_username ;
	global $new_login, $new_nom, $new_prenom, $new_quotite, $new_jours_an, $new_solde_jours, $new_email ;
	global $new_rtt_an, $new_solde_rtt, $new_is_resp, $new_resp_login, $new_is_admin, $new_password1, $new_password2 ;
 
	printf("<H3>Gestion des Utilisateurs :</H3>\n\n");
	
	/*********************/
	/* Etat Utilisateurs */
	/*********************/
	// Récuperation des informations des users:
	// si l'admin peut voir tous les users  OU si on est en mode "responsble virtuel" OU si l'admin n'est responsable d'aucun user
	if(($config_admin_see_all==TRUE) || ($config_responsable_virtuel==TRUE) || (admin_is_responsable($session_username, $link)==FALSE))   
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_is_admin, u_passwd, u_quotite, u_email FROM conges_users ORDER BY u_nom"  ;
	else
	{
		$sql3 = "SELECT u_login, u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_is_resp, u_resp_login, u_is_admin, u_passwd, u_quotite, u_email FROM conges_users WHERE ";
		$sql3 = $sql3." u_resp_login = '$session_username' "  ;
		if($config_gestion_groupes == TRUE)
		{
			$list_users_group=get_list_users_des_groupes_du_resp($session_username);
			if($list_users_group!="")  //si la liste n'est pas vide ( serait le cas si n'est responsable d'aucun groupe)
				$sql3 = $sql3." OR u_login IN ($list_users_group) ";
		}
		$sql3 = $sql3." ORDER BY u_nom, u_prenom"  ;
	}

	// AFFICHAGE TABLEAU
	printf("<h3>Etat des Utilisateurs :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"titre\">Nom</td>\n";
	echo "<td class=\"titre\">Prenom</td>\n";
	echo "<td class=\"titre\">login</td>\n";
	echo "<td class=\"titre\">Quotité</td>\n";
	echo "<td class=\"titre\">nb congés / an</td>\n";
	echo "<td class=\"titre\">solde congés</td>\n";
	if($config_rtt_comme_conges==TRUE)
		echo "<td class=\"titre\">nb rtt / an</td>\n<td class=\"titre\">solde rtt</td>\n";
	echo "<td class=\"titre\">is_resp</td>\n";
	echo "<td class=\"titre\">resp_login</td>\n";
	echo "<td class=\"titre\">is_admin</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"titre\">email</td>\n";
	echo "<td></td>\n";
	echo "<td></td>\n";
	if($config_admin_change_passwd==TRUE)
		echo "<td></td>\n";
	echo "</tr>\n";

	$ReqLog3 = mysql_query($sql3, $link) or die("ERREUR : mysql_query : ".$sql3." --> ".mysql_error());
	while ($resultat3 = mysql_fetch_array($ReqLog3))
	{

		$sql_login=$resultat3["u_login"] ;
		$sql_nom=$resultat3["u_nom"] ;
		$sql_prenom=$resultat3["u_prenom"] ;
		$sql_quotite=affiche_decimal($resultat3["u_quotite"]) ;
		$sql_nb_jours_an=$resultat3["u_nb_jours_an"] ;
		$sql_solde_jours=$resultat3["u_solde_jours"] ;
		$sql_nb_rtt_an=$resultat3["u_nb_rtt_an"] ;
		$sql_solde_rtt=$resultat3["u_solde_rtt"] ;
		$sql_is_resp=$resultat3["u_is_resp"] ;
		$sql_resp_login=$resultat3["u_resp_login"] ;
		$sql_is_admin=$resultat3["u_is_admin"] ;
		$sql_email=$resultat3["u_email"] ;

		$admin_modif_user="<a href=\"admin_modif_user.php?session=$session&u_login=$sql_login\">Modifier</a>" ;
		$admin_suppr_user="<a href=\"admin_suppr_user.php?session=$session&u_login=$sql_login\">Supprimer</a>" ;
		$admin_chg_pwd_user="<a href=\"admin_chg_pwd_user.php?session=$session&u_login=$sql_login\">Password</a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\"><b>$sql_nom</b></td>\n";
		echo "<td class=\"histo\"><b>$sql_prenom</b></td>\n";
		echo "<td class=\"histo\">$sql_login</td>\n";
		echo "<td class=\"histo\">$sql_quotite%</td>\n";
		echo "<td class=\"histo\">$sql_nb_jours_an</td>\n";
		echo "<td class=\"histo\">$sql_solde_jours</td>\n";
		if($config_rtt_comme_conges==TRUE)
		{
			echo "<td class=\"histo\">$sql_nb_rtt_an</td>\n";
			echo "<td class=\"histo\">$sql_solde_rtt</td>\n";
		}
		echo "<td class=\"histo\">$sql_is_resp</td>\n";
		echo "<td class=\"histo\">$sql_resp_login</td>\n";
		echo "<td class=\"histo\">$sql_is_admin</td>\n";
		if($config_where_to_find_user_email=="dbconges")
			echo "<td class=\"histo\">$sql_email</td>\n";
		echo "<td class=\"histo\">$admin_modif_user</td>\n";
		echo "<td class=\"histo\">$admin_suppr_user</td>\n";
		if(($config_admin_change_passwd==TRUE) && ($config_how_to_connect_user == "dbconges"))
			echo "<td class=\"histo\">$admin_chg_pwd_user</td>\n";
		echo "</tr>\n";
	}
	printf("</table>\n\n");


	/*********************/
	/* Ajout Utilisateur */
	/*********************/
	
	
	printf("<br><br><br><hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	// TITRE
	printf("<H3><u>Nouvel Utilisateur :</u></H3>\n\n");

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;

	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr align=\"center\">\n";
	if ($config_how_to_connect_user == "ldap")
	   	echo "<td class=\"histo\">Nom Prénom</td>\n";
	else
	{
		echo "<td class=\"histo\">login</td>\n";
		echo "<td class=\"histo\">Nom</td>\n";
		echo "<td class=\"histo\">Prenom</td>\n";
	}
	echo "<td class=\"histo\">Quotité</td>\n";
	echo "<td class=\"histo\">nb congés / an</td>\n";
	echo "<td class=\"histo\">solde congés</td>\n";
	if($config_rtt_comme_conges==TRUE)
		echo "<td class=\"histo\">nb rtt / an</td>\n<td class=\"histo\">solde rtt</td>";
	echo "<td class=\"histo\">est_responsable</td>\n";
	echo "<td class=\"histo\">responsable</td>\n";
	echo "<td class=\"histo\">est_administrateur</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">email</td>\n";
	if ($config_how_to_connect_user == "dbconges")
	{
		echo "<td class=\"histo\">password</td>\n";
		echo "<td class=\"histo\">password</td>\n";
	}
	echo "</tr>\n";

	$text_nom="<input type=\"text\" name=\"new_nom\" size=\"10\" maxlength=\"30\" value=\"".$new_nom."\">" ;
	$text_prenom="<input type=\"text\" name=\"new_prenom\" size=\"10\" maxlength=\"30\" value=\"".$new_prenom."\">" ;
	$text_quotite="<input type=\"text\" name=\"new_quotite\" size=\"3\" maxlength=\"3\" value=\"".$new_quotite."\">" ;
	$text_jours_an="<input type=\"text\" name=\"new_jours_an\" size=\"5\" maxlength=\"5\" value=\"".$new_jours_an."\">" ;
	$text_solde_jours="<input type=\"text\" name=\"new_solde_jours\" size=\"5\" maxlength=\"5\" value=\"".$new_solde_jours."\">" ;

	$text_rtt_an="<input type=\"text\" name=\"new_rtt_an\" size=\"5\" maxlength=\"5\" value=\"".$new_rtt_an."\">" ;
	$text_solde_rtt="<input type=\"text\" name=\"new_solde_rtt\" size=\"5\" maxlength=\"5\" value=\"".$new_solde_rtt."\">" ;

	$text_is_resp="<select name=\"new_is_resp\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;

	// PREPARATION DES OPTIONS DU SELECT du resp_login
	$text_resp_login="<select name=\"new_resp_login\" id=\"resp_login_id\" >" ;
	$sql2 = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp = \"Y\" ORDER BY u_nom, u_prenom"  ;
	$ReqLog2 = mysql_query($sql2, $link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());
	while ($resultat2 = mysql_fetch_array($ReqLog2)) {
		$current_resp_login=$resultat2["u_login"];
		if($new_resp_login==$current_resp_login)
			$text_resp_login=$text_resp_login."<option value=\"$current_resp_login\" selected>".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
		else
			$text_resp_login=$text_resp_login."<option value=\"$current_resp_login\">".$resultat2["u_nom"]." ".$resultat2["u_prenom"]."</option>";
	}
	$text_resp_login=$text_resp_login."</select>" ;

	$text_is_admin="<select name=\"new_is_admin\" ><option value=\"N\">N</option><option value=\"Y\">Y</option></select>" ;

	$text_email="<input type=\"text\" name=\"new_email\" size=\"10\" maxlength=\"99\" value=\"".$new_email."\">" ;

	$text_password1="<input type=\"password\" name=\"new_password1\" size=\"10\" maxlength=\"15\" value=\"\">" ;
	$text_password2="<input type=\"password\" name=\"new_password2\" size=\"10\" maxlength=\"15\" value=\"\">" ;
	$text_login="<input type=\"text\" name=\"new_login\" size=\"10\" maxlength=\"10\" value=\"".$new_login."\">" ;

	
	// AFFICHAGE DE LA LIGNE DE SAISIE D'UN NOUVEAU USER
	
	echo "<tr>\n";
	// Aj. D.Chabaud - Université d'Auvergne - Sept. 2005
	if ($config_how_to_connect_user == "ldap")	
	{
		// Récupération de la liste des utilisateurs via un ldap :
		
		// on crée 2 tableaux (1 avec les noms + prénoms, 1 avec les login)
		// afin de pouvoir construire une liste déroulante dans le formulaire qui suit...
		$tab_ldap  = array();
		$tab_login = array();
		recup_users_from_ldap($tab_ldap, $tab_login);
		
		// construction de la liste des users récupérés du ldap ...
		array_multisort($tab_ldap, $tab_login); // on trie les utilisateurs par le nom
		
		$lst_users = "<select name=new_ldap_user><option>------------------</option>\n";	
		$i = 0;
		
		foreach ($tab_login as $login)
		{
			$lst_users .= "<option value=$tab_login[$i]>$tab_ldap[$i]</option>\n";
			$i++;
		}
		$lst_users .= "</select>\n";
		echo "<td class=\"histo\">$lst_users</td>\n";
	}
	else
	{
		echo "<td class=\"histo\">$text_login</td>\n";
		echo "<td class=\"histo\">$text_nom</td>\n";
		echo "<td class=\"histo\">$text_prenom</td>\n";
	}
	echo "<td class=\"histo\">$text_quotite</td>\n";
	echo "<td class=\"histo\">$text_jours_an</td>\n";
	echo "<td class=\"histo\">$text_solde_jours</td>\n";
	if($config_rtt_comme_conges==TRUE)
	{
		echo "<td class=\"histo\">$text_rtt_an</td>\n";
		echo "<td class=\"histo\">$text_solde_rtt</td>\n";
	}
	echo "<td class=\"histo\">$text_is_resp</td>\n";
	echo "<td class=\"histo\">$text_resp_login</td>\n";
	echo "<td class=\"histo\">$text_is_admin</td>\n";
	if($config_where_to_find_user_email=="dbconges")
		echo "<td class=\"histo\">$text_email</td>\n";
	if ($config_how_to_connect_user == "dbconges")
	{
		echo "<td class=\"histo\">$text_password1</td>\n";
		echo "<td class=\"histo\">$text_password2</td>\n";
	}
	echo "</tr>\n";
	printf("</table><br>\n\n");

	// saisie de la grille des jours d'abscence ARTT ou temps partiel:
	saisie_jours_absence_temps_partiel($new_login, $link);

	echo "<br>\n";
	printf("<input type=\"hidden\" name=\"saisie_user\" value=\"ok\">\n");
	printf("<input type=\"submit\" value=\"Valider Nouvel Utilisateur\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Annuler\">\n");
	printf("</form>\n" ) ;
}

/******************************************************************************************************/

function affiche_gestion_groupes()
{
	global $PHP_SELF, $link;
	global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $new_group_name, $new_group_libelle ;
 
	printf("<H3>Gestion des Groupes :</H3>\n\n");
	
	/*********************/
	/* Etat Groupes      */
	/*********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Etat des Groupes :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%%\">\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">Groupe</td>\n";
	echo "	<td class=\"titre\">libellé</td>\n";
	echo "	<td></td>\n";
	echo "	<td></td>\n";
	echo "</tr>\n";

	$ReqLog_gr = mysql_query($sql_gr, $link) or die("ERREUR : mysql_query : ".$sql_gr." --> ".mysql_error());
	while ($resultat_gr = mysql_fetch_array($ReqLog_gr))
	{

		$sql_gid=$resultat_gr["g_gid"] ;
		$sql_group=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$admin_modif_group="<a href=\"admin_modif_group.php?session=$session&group=$sql_gid\">Modifier</a>" ;
		$admin_suppr_group="<a href=\"admin_suppr_group.php?session=$session&group=$sql_gid\">Supprimer</a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\"><b>$sql_group</b></td>\n";
		echo "<td class=\"histo\">$sql_comment</td>\n";
		echo "<td class=\"histo\">$admin_modif_group</td>\n";
		echo "<td class=\"histo\">$admin_suppr_group</td>\n";
		echo "</tr>\n";
	}
	printf("</table>\n\n");


	/*********************/
	/* Ajout Groupe      */
	/*********************/

	printf("<br><br><br><hr align=\"center\" size=\"2\" width=\"90%%\"> \n");
	// TITRE
	printf("<H3><u>Nouveau Groupe :</u></H3>\n\n");

	printf("<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n" ) ;

	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "<td class=\"histo\"><b>Groupe</b></td>\n";
	echo "<td class=\"histo\">Libellé / Commentaire</td>\n";
	echo "</tr>\n";

	$text_groupname="<input type=\"text\" name=\"new_group_name\" size=\"30\" maxlength=\"50\" value=\"".$new_group_name."\">" ;
	$text_libelle="<input type=\"text\" name=\"new_group_libelle\" size=\"50\" maxlength=\"250\" value=\"".$new_group_libelle."\">" ;

	echo "<tr>\n";
	echo "<td class=\"histo\">$text_groupname</td>\n";
	echo "<td class=\"histo\">$text_libelle</td>\n";
	echo "</tr>\n";
	printf("</table><br>\n\n");

	echo "<br>\n";
	printf("<input type=\"hidden\" name=\"saisie_group\" value=\"ok\">\n");
	printf("<input type=\"submit\" value=\"Valider Nouveau Groupe\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Cancel\">\n");
	printf("</form>\n" ) ;
}


function ajout_groupe() {
	global $PHP_SELF, $link;
	global $session;
	global $new_group_name, $new_group_libelle ;
	
	if(verif_new_param_group()==0)  // verif si les nouvelles valeurs sont coohérentes et n'existe pas déjà 
	{
		$ngm=stripslashes($new_group_name);
		echo "$ngm --- $new_group_libelle<br>\n";

		$sql1 = "INSERT INTO conges_groupe SET g_groupename='$new_group_name', g_comment='$new_group_libelle' " ;
		$result = mysql_query($sql1, $link) or die("ERREUR : ajout_group() : ".$sql1." --> ".mysql_error());

		$new_gid=mysql_insert_id($link);
		// par défaut le responsable virtuel est resp de tous les groupes !!!
		$sql2 = "INSERT INTO conges_groupe_resp SET gr_gid=$new_gid, gr_login='conges' " ;
		$result = mysql_query($sql2, $link) or die("ERREUR : ajout_group() : ".$sql2." --> ".mysql_error());

		if($result==TRUE)
			printf(" Changements pris en compte avec succes !<br><br> \n");
		else
			printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

		/* APPEL D'UNE AUTRE PAGE */
		printf(" <form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\"> \n");
		printf("<input type=\"submit\" value=\"Retour\">\n");
		printf(" </form> \n");
	}
}


function verif_new_param_group() {
	global $PHP_SELF, $link;
	global $session;
	global $new_group_name, $new_group_libelle ;

	// verif des parametres reçus :
	if(strlen($new_group_name)==0) {
		printf("<H3> ATTENTION : certain champs saisis ne sont pas valides ...... </H3>\n" ) ;
		echo "$new_group_name --- $new_group_libelle<br>\n";
		printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\">\n" ) ;
		printf("<input type=\"hidden\" name=\"new_group_name\" value=\"$new_group_name\">\n");
		printf("<input type=\"hidden\" name=\"new_group_libelle\" value=\"$new_group_libelle\">\n");
		
		printf("<input type=\"hidden\" name=\"saisie_group\" value=\"faux\">\n");
		printf("<input type=\"submit\" value=\"Recommencer\">\n");
		printf("</form>\n" ) ;
		
		return 1;
	}
	else {
		// verif si le groupe demandé n'existe pas déjà ....
		$sql_verif="select g_groupename from conges_groupe where g_groupename='$new_group_name' ";
		$ReqLog_verif = mysql_query($sql_verif, $link) or die("ERREUR : mysql_query : \n".$sql_verif."\n --> ".mysql_error());
		$num_verif = mysql_num_rows($ReqLog_verif);
		if ($num_verif!=0)
		{
			printf("<H3> ATTENTION : nom de groupe déjà utilisé, veuillez en changer ...... </H3>\n" ) ;
			printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group\" method=\"POST\">\n" ) ;
			printf("<input type=\"hidden\" name=\"new_group_name\" value=\"$new_group_name\">\n");
			printf("<input type=\"hidden\" name=\"new_group_libelle\" value=\"$new_group_libelle\">\n");
		
			printf("<input type=\"hidden\" name=\"saisie_group\" value=\"faux\">\n");
			printf("<input type=\"submit\" value=\"Recommencer\">\n");
			printf("</form>\n" ) ;

			return 1;	
		}
		else
			return 0;
	}
}

/***************************************************************************************************/

function affiche_choix_gestion_groupes_users()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $choix_group ;
	global $choix_user ;
	//global $choix_gestion_groupes_users;
	
	
	if(isset($choix_group))     // si un groupe choisi : on affiche la gestion par groupe
	{
		affiche_gestion_groupes_users($choix_group);
	}
	elseif(isset($choix_user))     // si un user choisi : on affiche la gestion par user
	{
		affiche_gestion_user_groupes($choix_user);
	}
	else    // si pas de groupe ou de user choisi : on affiche les choix
	{
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_groupes_users();
		echo "</td>\n";
		echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_user_groupes();
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

}



function affiche_choix_groupes_users()
{
	global $PHP_SELF, $link;
	global $session;
	global $session_username ;
 
	printf("<H3>Gestion Groupes <-> Utilisateurs:</H3>\n\n");
	
	
	/********************/
	/* Choix Groupe     */
	/********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Choix d'un Groupe :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;Groupe&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;libellé&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_gr = mysql_query($sql_gr, $link) or die("ERREUR : mysql_query : ".$sql_gr." --> ".mysql_error());
	while ($resultat_gr = mysql_fetch_array($ReqLog_gr))
	{

		$sql_gid=$resultat_gr["g_gid"] ;
		$sql_group=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$choix_group="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-users&choix_group=$sql_gid\"><b>&nbsp;$sql_group&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\"><b>&nbsp;$choix_group&nbsp;</b></td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_comment&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


function affiche_gestion_groupes_users($choix_group)
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
 
	printf("<H3>Gestion Groupes <-> Utilisateurs:</H3>\n\n");
	
	
	/************************/
	/* Affichage Groupes    */
	/************************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_groupename, g_comment FROM conges_groupe WHERE g_gid=$choix_group "  ;
	$ReqLog_gr = mysql_query($sql_gr, $link) or die("ERREUR : mysql_query : ".$sql_gr." --> ".mysql_error());
	$resultat_gr = mysql_fetch_array($ReqLog_gr);
	$sql_group=$resultat_gr["g_groupename"] ;
	$sql_comment=$resultat_gr["g_comment"] ;
	
	
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	
	//AFFICHAGE DU TABLEAU DES USERS DU GROUPE
	echo "<table class=\"tablo\">\n";
	
	// affichage TITRE
	echo "<tr align=\"center\">\n";
	echo "	<td colspan=3><h3>Membres du Groupe &nbsp;<b>$sql_group&nbsp;:</b>&nbsp;$sql_comment&nbsp;</h3></td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;Personne&nbsp;:</td>\n";
	echo "	<td class=\"titre\">&nbsp;login&nbsp;:</td>\n";
	echo "</tr>\n";
	
	// affichage des users
	
	//on rempli un tableau de tous les users avec le login, le nom, le prenom (tableau de tableau à 3 cellules
	// Récuperation des utilisateurs :
	$tab_users=array();
	$sql_users = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' ORDER BY u_nom, u_prenom "  ;
	$ReqLog_users = mysql_query($sql_users, $link) or die("ERREUR : mysql_query : ".$sql_users." --> ".mysql_error());
	while($resultat_users=mysql_fetch_array($ReqLog_users))
	{
		$tab_u=array();
		$tab_u["login"]=$resultat_users["u_login"];
		$tab_u["nom"]=$resultat_users["u_nom"];
		$tab_u["prenom"]=$resultat_users["u_prenom"];
		$tab_users[]=$tab_u;
	}
	// on rempli un autre tableau des users du groupe
	$tab_group=array();
	$sql_gu = "SELECT gu_login FROM conges_groupe_users WHERE gu_gid='$choix_group' ORDER BY gu_login "  ;
	$ReqLog_gu = mysql_query($sql_gu, $link) or die("ERREUR : mysql_query : ".$sql_gu." --> ".mysql_error());
	while($resultat_gu=mysql_fetch_array($ReqLog_gu))
	{
		$tab_group[]=$resultat_gu["gu_login"];
	}
	
	// ensuite on affiche tous les users avec une case cochée si exist login dans le 2ieme tableau
	$count = count($tab_users);
	for ($i = 0; $i < $count; $i++) 
	{
		$login=$tab_users[$i]["login"] ;
		$nom=$tab_users[$i]["nom"] ;
		$prenom=$tab_users[$i]["prenom"] ;
		
		if (in_array ($login, $tab_group)) 
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_users[$login]\" value=\"$login\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_users[$login]\" value=\"$login\">";
			$class="histo";
		}
		
		echo "<tr align=\"center\">\n";
		echo "	<td class=\"histo\">$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
		echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
		echo "</tr>\n";
	}
	
	echo "</table>\n\n";
	
	printf("<input type=\"hidden\" name=\"change_group_users\" value=\"ok\">\n");
	printf("<input type=\"hidden\" name=\"choix_group\" value=\"$choix_group\">\n");
	printf("<input type=\"submit\" value=\"Valider les modifications\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Annuler\">\n");
	printf("</form>\n" ) ;

}


function modif_group_users()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $choix_group, $checkbox_group_users ;

	// on supprime tous les anciens users du groupe puis on ajoute tous ceux qui sont dans le tableau checkbox (si il n'est pas vide)
	$sql_del = "DELETE FROM conges_groupe_users WHERE gu_gid=$choix_group "  ;
	$ReqLog_del = mysql_query($sql_del, $link) or die("ERREUR : mysql_query : ".$sql_del." --> ".mysql_error());
	
	
	if(count ($checkbox_group_users)!=0)
	{
		foreach($checkbox_group_users as $login => $value) 
		{
			//$login=$checkbox_group_users[$i] ;
			$sql_insert = "INSERT INTO conges_groupe_users SET gu_gid=$choix_group, gu_login='$login' "  ;
			$result_insert = mysql_query($sql_insert, $link) or die("ERREUR : mysql_query : ".$sql_insert." --> ".mysql_error());
		}
	}
	else
		$result_insert=TRUE;
	
	if($result_insert==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");

}



function affiche_choix_user_groupes()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
 
	printf("<H3>Gestion Utilisateurs <-> Groupes:</H3>\n\n");
	
	
	/********************/
	/* Choix User       */
	/********************/
	// Récuperation des informations :
	$sql_user = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Choix d'un Utilisateur :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr>\n";
	echo "<td class=\"titre\">&nbsp;Nom  Prénom&nbsp;</td>\n";
	echo "<td class=\"titre\">&nbsp;login&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_user = mysql_query($sql_user, $link) or die("ERREUR : mysql_query : ".$sql_user." --> ".mysql_error());
	while ($resultat_user = mysql_fetch_array($ReqLog_user))
	{

		$sql_login=$resultat_user["u_login"] ;
		$sql_nom=$resultat_user["u_nom"] ;
		$sql_prenom=$resultat_user["u_prenom"] ;

		$choix="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-users&choix_user=$sql_login\"><b>&nbsp;$sql_nom $sql_prenom&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\">&nbsp;$choix&nbsp;</td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_login&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


function affiche_gestion_user_groupes($choix_user)
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
//	global $choix_group ;
 
	printf("<H3>Gestion Utilisateurs <-> Groupes:</H3>\n\n");
	
	
	/************************/
	/* Affichage Groupes    */
	/************************/
	// Récuperation des informations :
	$sql_u = "SELECT u_nom, u_prenom FROM conges_users WHERE u_login='$choix_user'"  ;
	$ReqLog_u = mysql_query($sql_u, $link) or die("ERREUR : mysql_query : ".$sql_u." --> ".mysql_error());
	$resultat_u = mysql_fetch_array($ReqLog_u);
	$sql_nom=$resultat_u["u_nom"] ;
	$sql_prenom=$resultat_u["u_prenom"] ;
	
	
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	
	//AFFICHAGE DU TABLEAU DES GROUPES DU USER
	echo "<table class=\"tablo\">\n";
	
	// affichage TITRE
	echo "<tr align=\"center\">\n";
	echo "	<td colspan=3><h3>Groupes auxquels <b> $choix_user </b> appartient :</h3></td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;Groupe&nbsp;:</td>\n";
	echo "	<td class=\"titre\">&nbsp;libellé&nbsp;:</td>\n";
	echo "</tr>\n";
	
	// affichage des groupes
	
	//on rempli un tableau de tous les groupes avec le nom et libellé (tableau de tableau à 3 cellules)
	$tab_groups=array();
	$sql_g = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename "  ;
	$ReqLog_g = mysql_query($sql_g, $link) or die("ERREUR : mysql_query : ".$sql_g." --> ".mysql_error());
	while($resultat_g=mysql_fetch_array($ReqLog_g))
	{
		$tab_gg=array();
		$tab_gg["gid"]=$resultat_g["g_gid"];
		$tab_gg["groupename"]=$resultat_g["g_groupename"];
		$tab_gg["comment"]=$resultat_g["g_comment"];
		$tab_groups[]=$tab_gg;
	}
	// on rempli un autre tableau des groupes du user
	$tab_user=array();
	$sql_gu = "SELECT gu_gid FROM conges_groupe_users WHERE gu_login='$choix_user' ORDER BY gu_gid "  ;
	$ReqLog_gu = mysql_query($sql_gu, $link) or die("ERREUR : mysql_query : ".$sql_gu." --> ".mysql_error());
	while($resultat_gu=mysql_fetch_array($ReqLog_gu))
	{
		$tab_user[]=$resultat_gu["gu_gid"];
	}
	
	// ensuite on affiche tous les groupes avec une case cochée si existe le gid dans le 2ieme tableau
	$count = count($tab_groups);
	for ($i = 0; $i < $count; $i++) 
	{
		$gid=$tab_groups[$i]["gid"] ;
		$group=$tab_groups[$i]["groupename"] ;
		$libelle=$tab_groups[$i]["comment"] ;
		
		if (in_array ($gid, $tab_user)) 
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_user_groups[$gid]\" value=\"$gid\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_user_groups[$gid]\" value=\"$gid\">";
			$class="histo";
		}
		
		echo "<tr align=\"center\">\n";
		echo "	<td class=\"histo\">$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$group&nbsp</td>\n";
		echo "	<td class=\"$class\">&nbsp;$libelle&nbsp;</td>\n";
		echo "</tr>\n";
	}
	
	echo "</table>\n\n";
	
	printf("<input type=\"hidden\" name=\"change_user_groups\" value=\"ok\">\n");
	printf("<input type=\"hidden\" name=\"choix_user\" value=\"$choix_user\">\n");
	printf("<input type=\"submit\" value=\"Valider les modifications\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Annuler\">\n");
	printf("</form>\n" ) ;

}


function modif_user_groups()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $choix_user, $checkbox_user_groups ;

	// on supprime tous les anciens groupes du user, puis on ajoute tous ceux qui sont dans la tableau checkbox (si il n'est pas vide)
	$sql_del = "DELETE FROM conges_groupe_users WHERE gu_login='$choix_user' "  ;
	$ReqLog_del = mysql_query($sql_del, $link) or die("ERREUR : mysql_query : ".$sql_del." --> ".mysql_error());
	
	if(count ($checkbox_user_groups)!=0)
	{
		foreach($checkbox_user_groups as $gid => $value) 
		{
			$sql_insert = "INSERT INTO conges_groupe_users SET gu_gid='$gid', gu_login='$choix_user' "  ;
			$result_insert = mysql_query($sql_insert, $link) or die("ERREUR : mysql_query : ".$sql_insert." --> ".mysql_error());
		}
	}
	else
		$result_insert=TRUE;
	
	if($result_insert==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"$PHP_SELF?session=$session&onglet=admin-group-users\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");

}



/*****************************************************************************************/

function affiche_choix_gestion_groupes_responsables()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $choix_group ;
	global $choix_resp ;
	//global $choix_gestion_groupes_users;
	
	
	if(isset($choix_group))     // si un groupe choisi : on affiche la gestion par groupe
	{
		affiche_gestion_groupes_responsables($choix_group);
	}
	elseif(isset($choix_resp))     // si un resp choisi : on affiche la gestion par resp
	{
		affiche_gestion_responsable_groupes($choix_resp);
	}
	else    // si pas de groupe ou de resp choisi : on affiche les choix
	{
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_groupes_responsables();
		echo "</td>\n";
		echo "<td valign=\"top\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
		echo "<td valign=\"top\">\n";
		affiche_choix_responsable_groupes();
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

}


function affiche_choix_groupes_responsables()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
//	global $choix_group ;
 
	printf("<H3>Gestion Groupes <-> Responsables:</H3>\n\n");
	
	/********************/
	/* Choix Groupe     */
	/********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Choix d'un Groupe :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;Groupe&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;libellé&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_gr = mysql_query($sql_gr, $link) or die("ERREUR : mysql_query : ".$sql_gr." --> ".mysql_error());
	while ($resultat_gr = mysql_fetch_array($ReqLog_gr))
	{
		$sql_gid=$resultat_gr["g_gid"] ;
		$sql_groupename=$resultat_gr["g_groupename"] ;
		$sql_comment=$resultat_gr["g_comment"] ;

		$text_choix_group="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_group=$sql_gid\"><b>&nbsp;$sql_groupename&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\">&nbsp;$text_choix_group&nbsp;</td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_comment&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


function affiche_gestion_groupes_responsables($choix_group)
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
//	global $choix_group ;
 
	printf("<H3>Gestion Groupes <-> Responsables:</H3>\n\n");
	
	
	/***********************/
	/* Affichage Groupe    */
	/***********************/
	// Récuperation des informations :
	$sql_gr = "SELECT g_groupename, g_comment FROM conges_groupe WHERE g_gid='$choix_group'"  ;
	$ReqLog_gr = mysql_query($sql_gr, $link) or die("ERREUR : mysql_query : ".$sql_gr." --> ".mysql_error());
	$resultat_gr = mysql_fetch_array($ReqLog_gr);
	$sql_groupename=$resultat_gr["g_groupename"] ;
	$sql_comment=$resultat_gr["g_comment"] ;
	
	
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	
	//AFFICHAGE DU TABLEAU DES RESPONSBLES DU GROUPE
	echo "<table class=\"tablo\">\n";
	
	// affichage TITRE
	echo "<tr align=\"center\">\n";
	echo "	<td colspan=3><h3>Responsables du Groupe</h3></td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;Personne&nbsp;:</td>\n";
	echo "	<td class=\"titre\">&nbsp;login&nbsp;:</td>\n";
	echo "</tr>\n";
	
	// affichage des users
	
	//on rempli un tableau de tous les responsables avec le login, le nom, le prenom (tableau de tableau à 3 cellules
	// Récuperation des responsables :
	$tab_resp=array();
	$sql_resp = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_login!='conges' AND u_is_resp='Y' ORDER BY u_nom, u_prenom "  ;
	$ReqLog_resp = mysql_query($sql_resp, $link) or die("ERREUR : mysql_query : ".$sql_resp." --> ".mysql_error());
	while($resultat_resp=mysql_fetch_array($ReqLog_resp))
	{
		$tab_r=array();
		$tab_r["login"]=$resultat_resp["u_login"];
		$tab_r["nom"]=$resultat_resp["u_nom"];
		$tab_r["prenom"]=$resultat_resp["u_prenom"];
		$tab_resp[]=$tab_r;
	}
	// on rempli un autre tableau des responsables du groupe
	$tab_group=array();
	$sql_gr = "SELECT gr_login FROM conges_groupe_resp WHERE gr_gid='$choix_group' ORDER BY gr_login "  ;
	$ReqLog_gr = mysql_query($sql_gr, $link) or die("ERREUR : mysql_query : ".$sql_gr." --> ".mysql_error());
	while($resultat_gr=mysql_fetch_array($ReqLog_gr))
	{
		$tab_group[]=$resultat_gr["gr_login"];
	}
	
	// ensuite on affiche tous les responsables avec une case cochée si exist login dans le 2ieme tableau
	$count = count($tab_resp);
	for ($i = 0; $i < $count; $i++) 
	{
		$login=$tab_resp[$i]["login"] ;
		$nom=$tab_resp[$i]["nom"] ;
		$prenom=$tab_resp[$i]["prenom"] ;
		
		if (in_array ($login, $tab_group)) 
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_resp[$login]\" value=\"$login\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_group_resp[$login]\" value=\"$login\">";
			$class="histo";
		}
		
		echo "<tr align=\"center\">\n";
		echo "	<td class=\"histo\">$case_a_cocher</td>\n";
		echo "	<td class=\"$class\">&nbsp;$nom&nbsp;&nbsp;$prenom&nbsp;</td>\n";
		echo "	<td class=\"$class\">&nbsp;$login&nbsp;</td>\n";
		echo "</tr>\n";
	}
	
	echo "</table>\n\n";
	
	printf("<input type=\"hidden\" name=\"change_group_responsables\" value=\"ok\">\n");
	printf("<input type=\"hidden\" name=\"choix_group\" value=\"$choix_group\">\n");
	printf("<input type=\"submit\" value=\"Valider les modifications\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=group-resp\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Annuler\">\n");
	printf("</form>\n" ) ;

}


function modif_group_responsables()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $choix_group, $checkbox_group_resp ;

	$result_insert=TRUE;
	
	//echo "groupe : $choix_group<br>\n";
	// on supprime tous les anciens resps du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del = "DELETE FROM conges_groupe_resp WHERE gr_gid='$choix_group' "  ;
	$ReqLog_del = mysql_query($sql_del, $link) or die("ERREUR : mysql_query : ".$sql_del." --> ".mysql_error());
	
	
	if(isset($checkbox_group_resp)) // si la checkbox contient qq chose
	{
		foreach($checkbox_group_resp as $login => $value) 
		{
			$sql_insert = "INSERT INTO conges_groupe_resp SET gr_gid='$choix_group', gr_login='$login' "  ;
			$result_insert = mysql_query($sql_insert, $link) or die("ERREUR : mysql_query : ".$sql_insert." --> ".mysql_error());
		}
	}
	
	if($result_insert==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=group-resp\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");

}


function affiche_choix_responsable_groupes()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
 
	printf("<H3>Gestion Responsables <-> Groupes:</H3>\n\n");
	
	
	/*************************/
	/* Choix Responsable     */
	/*************************/
	// Récuperation des informations :
	$sql_resp = "SELECT u_login, u_nom, u_prenom FROM conges_users WHERE u_is_resp='Y' AND u_login!='conges' AND u_login!='admin' ORDER BY u_nom, u_prenom"  ;

	// AFFICHAGE TABLEAU
	printf("<h3>Choix d'un Responsable :</h3>\n");
	echo "<table cellpadding=\"2\" class=\"tablo\">\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;Responasble&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;login&nbsp;</td>\n";
	echo "</tr>\n";

	$ReqLog_resp = mysql_query($sql_resp, $link) or die("ERREUR : mysql_query : ".$sql_resp." --> ".mysql_error());
	while ($resultat_resp = mysql_fetch_array($ReqLog_resp))
	{

		$sql_login=$resultat_resp["u_login"] ;
		$sql_nom=$resultat_resp["u_nom"] ;
		$sql_prenom=$resultat_resp["u_prenom"] ;

		$text_choix_resp="<a href=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_resp=$sql_login\"><b>&nbsp;$sql_nom&nbsp;$sql_prenom&nbsp;</b></a>" ;

		echo "<tr>\n";
		echo "<td class=\"histo\">&nbsp;$text_choix_resp&nbsp;</td>\n";
		echo "<td class=\"histo\">&nbsp;$sql_login&nbsp;</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n\n";

}


function affiche_gestion_responsable_groupes($choix_resp)
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
 
	printf("<H3>Gestion Responsables <-> Groupes:</H3>\n\n");
	
	//echo "resp = $choix_resp<br>\n";
	/****************************/
	/* Affichage Responsable    */
	/****************************/
	// Récuperation des informations :
	$sql_r = "SELECT u_nom, u_prenom FROM conges_users WHERE u_login='$choix_resp'"  ;
	$ReqLog_r = mysql_query($sql_r, $link) or die("ERREUR : mysql_query : ".$sql_r." --> ".mysql_error());
	$resultat_r = mysql_fetch_array($ReqLog_r);
	$sql_nom=$resultat_r["u_nom"] ;
	$sql_prenom=$resultat_r["u_prenom"] ;
	
	
	printf(" <form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n");
	
	//AFFICHAGE DU TABLEAU DES GROUPES DU RESPONSABLE
	echo "<table class=\"tablo\">\n";
	
	// affichage TITRE
	echo "<tr align=\"center\">\n";
	echo "	<td colspan=3><h3>Groupes du Responsable $sql_prenom $sql_nom</h3></td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\">\n";
	echo "	<td class=\"titre\">&nbsp;</td>\n";
	echo "	<td class=\"titre\">&nbsp;Groupe&nbsp;:</td>\n";
	echo "	<td class=\"titre\">&nbsp;Libellé&nbsp;:</td>\n";
	echo "</tr>\n";
	
	// affichage des users
	
	//on rempli un tableau de tous les groupe avec le groupename, le commentatire (tableau de tableaux à 3 cellules)
	// Récuperation des groupes :
	$tab_groupe=array();
	$sql_groupe = "SELECT g_gid, g_groupename, g_comment FROM conges_groupe ORDER BY g_groupename "  ;
	$ReqLog_groupe = mysql_query($sql_groupe, $link) or die("ERREUR : mysql_query : ".$sql_groupe." --> ".mysql_error());
	while($resultat_groupe=mysql_fetch_array($ReqLog_groupe))
	{
		$tab_g=array();
		$tab_g["gid"]=$resultat_groupe["g_gid"];
		$tab_g["group"]=$resultat_groupe["g_groupename"];
		$tab_g["comment"]=$resultat_groupe["g_comment"];
		$tab_groupe[]=$tab_g;
	}
	// on rempli un autre tableau des groupes du responsables
	$tab_resp=array();
	$sql_r = "SELECT gr_gid FROM conges_groupe_resp WHERE gr_login='$choix_resp' ORDER BY gr_gid "  ;
	$ReqLog_r = mysql_query($sql_r, $link) or die("ERREUR : mysql_query : ".$sql_r." --> ".mysql_error());
	while($resultat_r=mysql_fetch_array($ReqLog_r))
	{
		$tab_resp[]=$resultat_r["gr_gid"];
	}
	
	// ensuite on affiche tous les groupes avec une case cochée si exist groupename dans le 2ieme tableau
	$count = count($tab_groupe);
	for ($i = 0; $i < $count; $i++) 
	{
		$gid=$tab_groupe[$i]["gid"] ;
		$group=$tab_groupe[$i]["group"] ;
		$comment=$tab_groupe[$i]["comment"] ;
		
		if (in_array ($gid, $tab_resp)) 
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_resp_group[$gid]\" value=\"$gid\" checked>";
			$class="histo-big";
		}
		else
		{
			$case_a_cocher="<input type=\"checkbox\" name=\"checkbox_resp_group[$gid]\" value=\"$gid\">";
			$class="histo";
		}
		
		echo "<tr align=\"center\">\n";
		echo "	<td class=\"histo\">$case_a_cocher</td>\n";
		echo "	<td class=\"$class\"> $group </td>\n";
		echo "	<td class=\"$class\"> $comment </td>\n";
		echo "</tr>\n";
	}
	
	echo "</table>\n\n";
	
	printf("<input type=\"hidden\" name=\"change_responsable_group\" value=\"ok\">\n");
	printf("<input type=\"hidden\" name=\"choix_resp\" value=\"$choix_resp\">\n");
	printf("<input type=\"submit\" value=\"Valider les modifications\">\n");
	printf("</form>\n" ) ;

	printf("<form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=resp-group\" method=\"POST\">\n" ) ;
	printf("<input type=\"submit\" value=\"Annuler\">\n");
	printf("</form>\n" ) ;

}


function modif_resp_groupes()
{
	global $PHP_SELF, $link;
	//global $config_admin_see_all , $config_responsable_virtuel;
	global $session;
	global $session_username ;
	global $choix_resp, $checkbox_resp_group ;

	$result_insert=TRUE;
	
	//echo "responsable : $choix_resp<br>\n";
	// on supprime tous les anciens resps du groupe puis on ajoute tous ceux qui sont dans le tableau de la checkbox
	$sql_del = "DELETE FROM conges_groupe_resp WHERE gr_login='$choix_resp' "  ;
	$ReqLog_del = mysql_query($sql_del, $link) or die("ERREUR : mysql_query : ".$sql_del." --> ".mysql_error());
	
	if(isset($checkbox_resp_group)) // si la checkbox contient qq chose
	{
		foreach($checkbox_resp_group as $gid => $value) 
		{
			$sql_insert = "INSERT INTO conges_groupe_resp SET gr_gid='$gid', gr_login='$choix_resp' "  ;
			$result_insert = mysql_query($sql_insert, $link) or die("ERREUR : mysql_query : ".$sql_insert." --> ".mysql_error());
		}
	}
	
	if($result_insert==TRUE)
		printf(" Changements pris en compte avec succes !<br><br> \n");
	else
		printf(" ERREUR ! Changements NON pris en compte !<br><br> \n");

	/* APPEL D'UNE AUTRE PAGE */
	printf(" <form action=\"$PHP_SELF?session=$session&onglet=admin-group-responsables&choix_gestion_groupes_responsables=resp-group\" method=\"POST\"> \n");
	printf("<input type=\"submit\" value=\"Retour\">\n");
	printf(" </form> \n");

}


// on a créé 2 tableaux (1 avec les noms + prénoms, 1 avec les login) passés en parametre
// recup_users_from_ldap interroge le ldap et rempli les 2 tableaux (passés en variable globale)
function recup_users_from_ldap(&$tab_ldap, &$tab_login)
{
	include "../config_ldap.php";
	// paramètres de cnx ldap :
	$basedn      = $config_basedn;
	$ldap_server = $config_ldap_server;
	$ldap_user   = $config_ldap_user;
	$ldap_pass   = $config_ldap_pass;
	$searchdn    = $config_searchdn;

	// cnx à l'annuaire ldap :
	$ds = ldap_connect($ldap_server);
	//ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) ;
	if ($ldap_user == "")
		$bound = ldap_bind($ds);  // connexion anonyme au serveur	
	else 
		$bound = ldap_bind($ds, $ldap_user, $ldap_pass);

	// recherche des entrées :
	//$filter = "(&(".$config_ldap_nomaff."=*)(".$config_ldap_filtre."=".$config_ldap_filrech."))";            
	if ($config_ldap_filtre_complet != "")
		$filter = $config_ldap_filtre_complet;
	else   
		$filter = "(&(".$config_ldap_nomaff."=*)(".$config_ldap_filtre."=".$config_ldap_filrech."))";            

	$sr   = ldap_search($ds, $searchdn, $filter);
	$data = ldap_get_entries($ds,$sr);

	foreach ($data as $info)
	{
		$login = $info[$config_ldap_login][0];
		$nom = strtoupper(utf8_decode($info[$config_ldap_nom][0]))." ".utf8_decode($info[$config_ldap_prenom][0]);
		// concaténation NOM Prénom
		// utf8_decode permet de supprimer les caractères accentués mal interprêtés...
		array_push($tab_ldap, $nom);
		array_push($tab_login, $login);
	}
}

?>
