<?php


define('_PHP_CONGES', 1);
define('ROOT_PATH', '');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include ROOT_PATH .'fonctions_conges.php';
include INCLUDE_PATH .'fonction.php';
include INCLUDE_PATH .'session.php';

$DEBUG=FALSE;
	
$how_to_connect_user=$_SESSION['config']['how_to_connect_user'];
$URL_ACCUEIL_CONGES=$_SESSION['config']['URL_ACCUEIL_CONGES'];
	

	$comment_log = "Deconnexion de ".$_SESSION['userlogin'];
	log_action(0, "", $_SESSION['userlogin'], $comment_log, $DEBUG);


session_delete($session);

$session="";
$session_username="";
$session_password="";
	
	
//Dans le cas ou le système d'authentification CAS est utilisé, lorsque l'utilisateur se deconnecte,
// on détruit le ticket qui a permis d'authentifier l'utilisateur.
if($how_to_connect_user=="CAS")
{
    //$logoutCas=1;
    deconnexion_CAS($URL_ACCUEIL_CONGES);
}


session_delete($session);

$session="";
$session_username="";
$session_password="";
    
    
	// => html refresh
	
echo "<HTML>\n";
echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$URL_ACCUEIL_CONGES\">";
echo "</HTML>\n";


