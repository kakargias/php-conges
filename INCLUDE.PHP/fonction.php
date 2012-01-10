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


defined( '_PHP_CONGES' ) or die( 'Restricted access' );

include_once   INCLUDE_PATH .'sql.class.php';
include_once   INCLUDE_PATH .'get_text.php';


function schars( $htmlspec ) {
	return htmlspecialchars( $htmlspec );
}

function redirect($url , $auto_exit = true) {
	// $url = urlencode($url);
	if (headers_sent()) {	
		echo '<html>';
			echo '<head>';
				echo '<meta HTTP-EQUIV="REFRESH" CONTENT="0; URL='.$url.'">';
				echo '<script language=javascript>
						function redirection(page){
							window.location=page;
						}
						setTimeout(\'redirection("'.$url.'")\',100);
					</script>';
			echo '</head>';
		echo '</html>';
	}
	else {
	    header('Location: '.$url);
	}
	if ($auto_exit)
		exit;
}

function header_popup($title = 'PHP CONGES' , $additional_head = '' ) {
	global $type_bottom;
	global $session;
	$type_bottom = 'popup';
	
	include TEMPLATE_PATH . 'popup_header.php';
}

function header_menu( $info ,$title = 'PHP CONGES' , $additional_head = '' ) {
	global $type_bottom;
	global $session;
	$type_bottom = 'menu';
	
	include TEMPLATE_PATH . 'menu_header.php';
}

function bottom() {
	global $type_bottom;
	
	include TEMPLATE_PATH . $type_bottom .'_bottom.php';
}









//
// indique (TRUE / FALSE) si une session est valide (par / au temps de connexion)
//
function session_is_valid($session)
{
   // ATTENTION:  on fixe l'id de session comme nom de session pour que , sur un meme pc, on puisse se loguer sous 2 users à la fois
   if (session_id() == "")
   {
      session_name($session);
      session_start();
   }

	$is_valid=FALSE;

	if( (isset($_SESSION['timestamp_last'])) && (isset($_SESSION['config'])) )
	{
		$difference = time() - $_SESSION['timestamp_last'];
		if ( ($session==session_id()) && ($difference < $_SESSION['config']['duree_session']) )
		{
			$is_valid=TRUE;
		}
	}

	return $is_valid;
}

//
// cree la session et renvoie son identifiant
//
function session_create($username)
{
	if ($username != "")
	{
		$session = "phpconges".md5(uniqid(rand()));
		session_name($session);
		session_id($session);
		
		session_start();
		$_SESSION['userlogin']=$username;
		$maintenant=time();
		$_SESSION['timestamp_start']=$maintenant;
		$_SESSION['timestamp_last']=$maintenant;
		if (function_exists('init_config_tab'))
			$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
		//$session=session_id();

	}
	else
	{
		$session="";
	}

	return   $session;
}



//
// mise a jour d'une session
//
function session_update($session)
{
   if ($session != "")
   {
		$maintenant=time();
		$_SESSION['timestamp_last']=$maintenant;
   }
}



//
// destruction d'une session
//
function session_delete($session)
{
   if ($session != "")
   {
	 unset($_SESSION['userlogin']);
	 unset($_SESSION['timestamp_start']);
	 unset($_SESSION['timestamp_last']);
	 unset($_SESSION['tab_j_feries']);
	 unset($_SESSION['config']);
	 session_destroy();
   }
}



//
// formulaire de saisie du user/password
//
function session_saisie_user_password($erreur, $session_username, $session_password)
{
   $PHP_SELF=$_SERVER['PHP_SELF'];
   
	$config_lien_img_login          =$_SESSION['config']['lien_img_login'];
	$config_img_login               =$_SESSION['config']['img_login'];
	$config_texte_img_login         =$_SESSION['config']['texte_img_login'];
	$config_texte_page_login        =$_SESSION['config']['texte_page_login'];
//	$config_php_conges_version      =$_SESSION['config']['php_conges_version'];
	$config_php_conges_version      =$_SESSION['config']['installed_version'];
	$config_url_site_web_php_conges =$_SESSION['config']['url_site_web_php_conges'];
	$config_stylesheet_file         =$_SESSION['config']['stylesheet_file'];

	// verif si on est dans le repertoire install
	if(substr(dirname ($_SERVER["SCRIPT_FILENAME"]), -6, 6) == "config")   // verif si on est dans le repertoire install
		$config_dir=TRUE;
	else
		$config_dir=FALSE;

	$add = '<script language="JavaScript" type="text/javascript">
<!--
// Les cookies sont obligatoires
if (! navigator.cookieEnabled) {
	document.write("<font color=\'#FF0000\'><br><br><center>'. _('cookies_obligatoires') .'</center></font><br><br>");
}
//-->
</script>
<noscript>
		<font color="#FF0000"><br><br><center>'. _('javascript_obligatoires') .'</center></font><br><br>
</noscript>';
		
	header_popup('PHP CONGES', $add);
	
	echo "<CENTER>\n";
	if($erreur=="login_passwd_incorrect")
		echo "<H3>". _('login_passwd_incorrect') ."</H3><BR>\n";
	elseif($erreur=="login_non_connu")
		echo "<H3>". _('login_non_connu') ."</H3><BR>\n";
	echo "</CENTER>\n";
	
	echo "<CENTER>\n";
	echo "<table>\n";
	
	if(!$config_dir) // si on est dans le repertoire config on affiche pas les liens
	{
		echo "<tr><td align=\"center\">\n";
			echo "<a href=\"$config_lien_img_login\" target=\"_parent\">";
			echo "<img src=\"$config_img_login\" alt=\"$config_texte_img_login\" title=\"$config_texte_img_login\"/>";
			echo "</a>";
			if($config_texte_page_login != "")
			{
				echo "<br><br>\n";
				echo "</td></tr>\n";
				echo "<tr><td align=\"center\">\n";
					echo "$config_texte_page_login";
			}
			echo "<br><br><br>\n";
		echo "</td></tr>\n";
	}
	
	echo "<tr><td align=\"center\">\n";
		echo "<FORM METHOD='post' ACTION='$PHP_SELF'>\n";
		
		echo "<fieldset class=\"boxlogin\">\n";
		echo "<legend class=\"boxlogin\">". _('login_fieldset') ."</legend>\n";
		echo "<TABLE class=\"ident\">\n";
		echo "<TR>\n";
		echo "	<TD class=\"login\">". _('divers_login_maj_1') ." :</TD>\n";
		echo "	<TD><INPUT TYPE='text'     NAME='session_username' SIZE=32 maxlength=99  VALUE='$session_username'></TD>\n";
		echo "</TR>\n";
		echo "<TR>\n";
		echo "	<TD class=\"login\">". _('password') ." :</TD>\n";
		echo "	<TD><INPUT TYPE='password' NAME='session_password' SIZE=32 maxlength=32 VALUE='$session_password'></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		echo "</fieldset>\n";
		
		echo "<TABLE BORDER='0'>\n";
		echo "<TR>\n";
		echo "	<TD COLSPAN='2'><CENTER><INPUT TYPE='submit' VALUE='". _('form_submit') ."'></CENTER></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		echo "</FORM>\n";
	echo "</td></tr>\n";
	
	if( (!$config_dir) && ($_SESSION['config']['consult_calendrier_sans_auth']==TRUE) ) // si on est pas dans le repertoire config ET acces calendrier sans login actif
	{
		echo "<tr><td align=\"center\">\n";
		echo "<a href=\"calendrier.php\">" .
				"<img src=\"". TEMPLATE_PATH . "img/1day.png\" width=\"24\" height=\"24\" border=\"0\" title=\"". _('button_calendar') ."\" alt=\"". _('button_calendar') ."\">" .
				" ". _('button_calendar') ."</a>\n";
		echo "</td></tr>\n";
	}
	echo "</table>\n";
	
	if(!$config_dir) // si on est dans le repertoire config on affiche pas les liens
	{
		echo "<table width=\"100%\">\n";
		if( (isset($_SERVER["HTTP_USER_AGENT"])) && (stristr($_SERVER["HTTP_USER_AGENT"], "MSIE")!=FALSE) )
		{
			echo "<tr><td align=\"center\">";
			echo "<img src=\"". TEMPLATE_PATH . "img/attention.png\" width=\"22\" height=\"22\" border=\"0\"/>";
			echo "&nbsp;". _('msie_alert')  ;
			echo "</td></tr>\n";
		}
		echo "<tr><td align=\"right\">\n";
			echo "<br><br>";
			echo "<a href=\"$config_url_site_web_php_conges/\">PHP_CONGES v $config_php_conges_version</a>\n";
		echo "</td></tr>\n";
		echo "</table>\n";
	}

	bottom();
}



//
// autentifie un user dans le base mysql avec son login et son passwd conges :
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
//
function autentification_passwd_conges($username,$password)
{
	$password_md5=md5($password);
//	$req_conges="SELECT u_passwd   FROM conges_users   WHERE u_login='$username' AND u_passwd='$password_md5' " ;
	// on conserve le double mode d'autentificatio (nouveau cryptage (md5) ou ancien cryptage (mysql))
	$req_conges='SELECT u_passwd   FROM conges_users   WHERE u_login=\''. SQL::quote( $username ) .'\' AND ( u_passwd=\''. md5($password) .'\' OR u_passwd=PASSWORD(\''.SQL::quote( $password ).'\') ) ' ;
	$res_conges = SQL::query($req_conges) ;
	$num_row_conges = $res_conges->num_rows;
	if ($num_row_conges !=0)
		return $username;
	return '';
}


// authentification du login/passwd sur un serveur LDAP
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
//
function authentification_ldap_conges($username,$password)
{
	require_once ( LIBRARY_PATH .'authLDAP.php');

	$a = new authLDAP();
	//$a->DEBUG = 1;
	//$a->bind($username,$password);
	$a->bind($username,stripslashes($password)); 
	if ($a->is_authentificated())
		return $username;

	return '';
}


// Authentifie l'utilisateur auprès du serveur CAS, puis auprès de la base de donnée.
// Si le login qui a permis d'authentifier l'utilisateur auprès du serveur
//	CAS existe en tant que login d'une entrée de la table conges_user, alors 
//	l'authentification est réussie et passwCAS renvoi le nom de l'utilisateur, "" sinon.
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
function authentification_passwd_conges_CAS()
{
	// import de la librairie CAS
	include_once( LIBRARY_PATH .'CAS/CAS.php');
	// import des paramètres du serveur CAS
	
	$config_CAS_host       =$_SESSION['config']['CAS_host'];
	$config_CAS_portNumber =$_SESSION['config']['CAS_portNumber'];
	$config_CAS_URI        =$_SESSION['config']['CAS_URI'];
	
	global $connexionCAS;
	global $logoutCas;
		
	phpCAS::setDebug();
			
	// initialisation phpCAS
	if($connexionCAS!="active")
	{
		$CASCnx = phpCAS::client(CAS_VERSION_2_0,$config_CAS_host,$config_CAS_portNumber,$config_CAS_URI);
		$connexionCAS = "active";

	}
	
	if($logoutCas==1)
	{
		phpCAS::logout();
	}
	
	// authentificationCAS (redirection vers la page d'authentification de CAS)
	phpCAS::forceAuthentication();

	//L'utilisateur a été correctement identifié		

	$usernameCAS = phpCAS::getUser();
	session_create($usernameCAS);
	
	//ON VERIFIE ICI QUE L'UTILISATEUR EST DEJA ENREGISTRE SOUS DBCONGES
	$req_conges = 'SELECT u_login FROM conges_users WHERE u_login=\''.SQL::quote($usernameCAS);
	$res_conges = SQL::query($req_conges) ;
	$num_row_conges = $res_conges->num_rows;
	if($num_row_conges !=0)
		return $usernameCAS;
	
	return '';
}


function deconnexion_CAS($url="")
{
    // import de la librairie CAS
    include_once( LIBRARY_PATH .'CAS/CAS.php');
    // import des parametres du serveur CAS
    
    $config_CAS_host       =$_SESSION['config']['CAS_host'];
    $config_CAS_portNumber =$_SESSION['config']['CAS_portNumber'];
    $config_CAS_URI        =$_SESSION['config']['CAS_URI'];
    
    global $connexionCAS;
           
    // initialisation phpCAS
    if($connexionCAS!="active")
    {
        $CASCnx = phpCAS::client(CAS_VERSION_2_0,$config_CAS_host,$config_CAS_portNumber,$config_CAS_URI);
        $connexionCAS = "active";

    }

    phpCAS::logout($url);
}

