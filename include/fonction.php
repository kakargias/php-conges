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

include INCLUDE_PATH .'sql.class.php';
include INCLUDE_PATH .'fonction_hr.php';
include INCLUDE_PATH .'fonction_config.php';
include INCLUDE_PATH .'fonction_admin.php';
include INCLUDE_PATH .'lang_profile.php';
//better to include plugins at the end : see bottom function
//include INCLUDE_PATH .'plugins.php';

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


//Get the name of current php page
function curPage() {
 $local_scripts = array();
 $local_scripts[0] = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
 $local_scripts[1] = $_SERVER["REQUEST_URI"];
 return $local_scripts;
}


function header_popup($title = '' , $additional_head = '' ) {
    global $type_bottom;
    global $session;

    static $last_use = '';
    if ($last_use == '') {
        $last_use = debug_backtrace();
    }else
        throw new Exception('Warning : Ne peux ouvrir deux header !!! previous = '.$last_use['file']);

    $type_bottom = 'popup';

    if (empty($title))
        $title = 'PHP CONGES';

    include TEMPLATE_PATH . 'popup_header.php';
}

function header_menu( $info ,$title = '' , $additional_head = '' ) {
    global $type_bottom;
    global $session;

    static $last_use = '';
    if ($last_use == '') {
        $last_use = debug_backtrace();
    }else
        throw new Exception('Warning : Ne peux ouvrir deux header !!! previous = '.$last_use['file']);

    $type_bottom = 'menu';

    if (empty($title))
        $title = 'PHP CONGES';

    include TEMPLATE_PATH . 'menu_header.php';
}

function bottom() {
    global $type_bottom;


    static $last_use = '';
    if ($last_use == '') {
        $last_use = debug_backtrace();
    }else
        throw new Exception('Warning : Ne peux ouvrir deux header !!!');

    include INCLUDE_PATH .'plugins.php';
    include TEMPLATE_PATH . $type_bottom .'_bottom.php';
}


//manage plugins
function install_plugin($plugin){
    include INCLUDE_PATH . "/plugins/".$plugin."/plugin_install.php";
}
function activate_plugin($plugin){
    include INCLUDE_PATH . "/plugins/".$plugin."/plugin_active.php";
}
function uninstall_plugin($plugin){
    include INCLUDE_PATH . "/plugins/".$plugin."/plugin_uninstall.php";
}
function disable_plugin($plugin){
    include INCLUDE_PATH . "/plugins/".$plugin."/plugin_inactive.php";
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

    if( (isset($_SESSION['timestamp_last'])) && (isset($_SESSION['config'])) )
    {
        $difference = time() - $_SESSION['timestamp_last'];
        if ( ($session==session_id()) && ($difference < $_SESSION['config']['duree_session']) )
            return true;
    }

    return false;
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

        if (isset($_REQUEST['lang']))
            $_SESSION['lang'] = $_REQUEST['lang'];
    }
    else
    {
        $session="";
    }

    $comment_log = 'Connexion de '.$username;
    log_action(0, "", $username, $comment_log);

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
     unset($_SESSION['lang']);
     session_destroy();
   }
}



//
// formulaire de saisie du user/password
//
function session_saisie_user_password($erreur, $session_username, $session_password)
{
   $PHP_SELF=$_SERVER['PHP_SELF'];

    $config_php_conges_version      = $_SESSION['config']['php_conges_version'];
    $config_url_site_web_php_conges = $_SESSION['config']['url_site_web_php_conges'];
    $config_stylesheet_file         = $_SESSION['config']['stylesheet_file'];

    $return_url                     = getpost_variable('return_url', false);

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

    header_popup('', $add);
    include TEMPLATE_PATH . 'login_form.php';

    bottom();
    exit;
}



//
// autentifie un user dans le base mysql avec son login et son passwd conges :
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
//
function autentification_passwd_conges($username,$password)
{
    $password_md5=md5($password);
//  $req_conges="SELECT u_passwd   FROM conges_users   WHERE u_login='$username' AND u_passwd='$password_md5' " ;
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
//  CAS existe en tant que login d'une entrée de la table conges_user, alors
//  l'authentification est réussie et passwCAS renvoi le nom de l'utilisateur, "" sinon.
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
function authentification_passwd_conges_CAS()
{
	// import de la librairie CAS
	include( LIBRARY_PATH .'CAS/CAS.php');
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

	//Ignore le certificat CAS
	phpCAS::setNoCasServerValidation();

	// authentificationCAS (redirection vers la page d'authentification de CAS)
	phpCAS::forceAuthentication();

	//L'utilisateur a été correctement identifié
	$usernameCAS = phpCAS::getUser();

	//On supprime la session créer par phpCAS
	session_destroy();
	//On créé la session php_conges
	session_create($usernameCAS);

	//ON VERIFIE ICI QUE L'UTILISATEUR EST DEJA ENREGISTRE SOUS DBCONGES
	$req_conges = 'SELECT u_login FROM conges_users WHERE u_login=\''.SQL::quote($usernameCAS).'\'';
	$res_conges = SQL::query($req_conges) ;
	$num_row_conges = $res_conges->num_rows;

	if($num_row_conges !=0)
		return $usernameCAS;
	else
		return '';
}


function deconnexion_CAS($url="")
{
    // import de la librairie CAS
    include( LIBRARY_PATH .'CAS/CAS.php');
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


