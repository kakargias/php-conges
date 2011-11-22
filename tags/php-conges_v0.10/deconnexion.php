<?php
include("config.php") ;
include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
include("INCLUDE.PHP/session.php");



session_delete($session);

$session="";
$session_username="";
$session_password="";


//Dans le cas ou le système d'authentification CAS est utilisé, lorsque l'utilisateur se deconnecte,
// on détruit le ticket qui a permis d'authentifier l'utilisateur.
if($config_how_to_connect_user=="CAS")
{
	$logoutCas=1;
}

echo "<HTML>\n";
echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$URL_ACCUEIL_CONGES/index.php\">";
echo "</HTML>\n";

?>
