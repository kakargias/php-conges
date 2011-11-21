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

define('_PHP_CONGES', 1);
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

include("fonctions_conges.php") ;
$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
include("INCLUDE.PHP/fonction.php");


//
// MAIN
//

if(isset($_POST['username'])) $username=$_POST['username'];
if(isset($_POST['password'])) $password=$_POST['password'];

if (($username == "") || ($password == ""))
{
	echo '<html><head>';
	echo '<link href="'.$_SESSION['config']['stylesheet_file'].'\" rel="stylesheet" type="text/css">';
	echo '<TITLE> PHP_CONGES : Validation de votre Mot de Passe : </TITLE></head>';
	echo '<body class="login">';
	saisie_user_password($username,$password);
	echo '</body></html>';
	exit;
}
else
{
   //  AUTHENTIFICATION :

   // le user doit etre authentifié dans la table conges (login + passwd) 
  
   // si on a trouve personne qui correspond au couple user/password
   if (old_autentification_passwd_conges($username,$password) != $username)
   {
		echo '<html><head>';
		echo '<link href="'.$_SESSION['config']['stylesheet_file'].'" rel="stylesheet" type="text/css">';
		echo '<TITLE> PHP_CONGES : Validation de votre Mot de Passe :</TITLE></head>';
		echo '<body class="login">';
		echo '<CENTER>';
		echo '<H3>ERREUR : Nom d\'utilisateur et/ou mot de passe incorrect !!!</H3><BR>\n';
		echo '</CENTER>';
		saisie_user_password($username,$password);
		echo '</body></html>';
		exit;
   }

   if ((old_autentification_passwd_conges($username,$password) == $username) && ($username != ""))
		update_password($username,$password);
}


/********************************************************************************************************/
/***   FONCTIONS   ***/
/********************************************************************************************************/

//
// formulaire de saisie du user/password
//
function saisie_user_password($username,$password)
{
	
   echo '<CENTER>';

	echo '<table>';
	echo '<tr><td align="center">';
		echo '<a href="'.str_replace('"','\"',$_SESSION['config']['lien_img_login']).'" target="_parent">';
		echo '<img src="'.str_replace('"','\"',$_SESSION['config']['img_login']).'" alt="'.str_replace('"','\"',$_SESSION['config']['texte_img_login']).'" title="'.str_replace('"','\"',$_SESSION['config']['texte_img_login']).'"/>';
		echo '</a>';
		echo '<br><br><br>';
	echo '</td></tr>';
	
	echo '<tr><td align="center">';
		echo '<FORM METHOD="post" ACTION="'.str_replace('"','\"',$PHP_SELF).'">';
		
		echo '<fieldset class="boxlogin">';
		echo '<legend class="boxlogin">PHP_CONGES : Validation de votre Mot de Passe :</legend>';
//		echo '<TABLE BGCOLOR='#dcdcdc' BORDER='1'>';
		echo '<TABLE class="ident">';
		echo '<TR>';
		echo '	<TD class="login">Login :</TD>';
		echo '	<TD><INPUT TYPE="text"     NAME="username" SIZE=32  VALUE="'.str_replace('"','\"',$username).'"></TD>';
		echo '</TR>';
		echo '<TR>';
		echo '	<TD class="login">Mot de Passe :</TD>';
		echo '	<TD><INPUT TYPE="password" NAME="password" SIZE=32  VALUE="'.str_replace('"','\"',$password).'"></TD>';
		echo '</TR>';
		echo '</TABLE>';
		echo '</fieldset>';
		
		echo '<TABLE BORDER="0">';
		echo '<TR>';
		echo '	<TD COLSPAN="2"><CENTER><INPUT TYPE="submit" VALUE="Valider"></CENTER></TD>';
		echo '</TR>';
		echo '</TABLE>';
		echo '</FORM>';
	echo '</td></tr>';
	echo '</table>';
	
	echo '<table width="100%">';
	echo '<tr><td align="right">';
		echo '<br><br>';
		echo '<a href="'.str_replace('"','\"',$_SESSION['config']['url_site_web_php_conges']).'>PHP_CONGES v '.schars($_SESSION['config']['php_conges_version']).'</a>';
	echo '</td></tr>';
	echo '</table>';

   echo '</CENTER>';
	
	
	
	
	
	
	
	
	
	
	
   $PHP_SELF=$_SERVER['PHP_SELF'];

   echo "<CENTER>\n";

	echo "<table>\n";
	echo "<tr><td align=\"center\">\n";
		echo "<a href=\"".$_SESSION['config']['lien_img_login']."\" target=\"_parent\">";
		echo "<img src=\"".$_SESSION['config']['img_login']."\" alt=\"".$_SESSION['config']['texte_img_login']."\" title=\"".$_SESSION['config']['texte_img_login']."\"/>";
		echo "</a>";
		echo "<br><br><br>\n";
	echo "</td></tr>\n";
	
	echo "<tr><td align=\"center\">\n";
		echo "<FORM METHOD='post' ACTION='$PHP_SELF'>\n";
		
		echo "<fieldset class=\"boxlogin\">\n";
		echo "<legend class=\"boxlogin\">PHP_CONGES : Validation de votre Mot de Passe :</legend>\n";
//		echo "<TABLE BGCOLOR='#dcdcdc' BORDER='1'>\n";
		echo "<TABLE class=\"ident\">\n";
		echo "<TR>\n";
		echo "	<TD class=\"login\">Login :</TD>\n";
		echo "	<TD><INPUT TYPE='text'     NAME='username' SIZE=32  VALUE='$username'></TD>\n";
		echo "</TR>\n";
		echo "<TR>\n";
		echo "	<TD class=\"login\">Mot de Passe :</TD>\n";
		echo "	<TD><INPUT TYPE='password' NAME='password' SIZE=32  VALUE='$password'></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		echo "</fieldset>\n";
		
		echo "<TABLE BORDER='0'>\n";
		echo "<TR>\n";
		echo "	<TD COLSPAN='2'><CENTER><INPUT TYPE='submit' VALUE='Valider'></CENTER></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		echo "</FORM>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	
	echo "<table width=\"100%\">\n";
	echo "<tr><td align=\"right\">\n";
		echo "<br><br>";
		echo "<a href=\"".$_SESSION['config']['url_site_web_php_conges']."/\">PHP_CONGES v ".$_SESSION['config']['php_conges_version']."</a>\n";
	echo "</td></tr>\n";
	echo "</table>\n";

   echo "</CENTER>\n";
}

//
// autentifie un user dans le base mysql avec son login et son passwd (avec cryptage mysql)
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
//
function old_autentification_passwd_conges($username,$password)
{
	$username_password_ok='';	
	$req_conges='SELECT u_passwd   FROM conges_users   WHERE u_login=\''.$sql->escape($username).'\' AND u_passwd=password(\''.$sql->escape($password).'\') ';
	$res_conges = $sql->query($req_conges) or die('autentification_passwd_conges() : Erreur '.$sql->error);
	$num_row_conges = $res_conges->num_rows;
	if ($num_row_conges !=0)
		$username_password_ok=$username;
	
	return   $username_password_ok;
}


// change dans la database db_conges le mot de passe du user (passe du cryptage mysql au crptage md5)
function update_password($username,$password)
{
	$passwd_md5=md5($password);
	$sql1 = 'UPDATE conges_users  SET u_passwd=\''.$passwd_md5.'\' WHERE u_login=\''.$sql->escape($username).'\';' ;
	$result = $sql->query($sql1) or die("ERREUR : query : ".$sql1." --> ".$sql->error);
	
	if($result==TRUE)
		echo '<center> Changements pris en compte avec succes !<br><br> </center>';
	else
		echo '<center> ERREUR ! Changements NON pris en compte !<br><br> </center>';
	
	
	/* APPEL D'UNE AUTRE PAGE */
	echo '<center><a href="'.$_SESSION['config']['URL_ACCUEIL_CONGES'].'/">aller à PHP_CONGES ...</a><center>';
}


?>
