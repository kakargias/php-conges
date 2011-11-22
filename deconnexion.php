<?
include("config.php") ;
include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
include("INCLUDE.PHP/session.php");



session_delete($session);

$session="";
$session_username="";
$session_password="";



echo "<HTML>\n";
echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=$URL_ACCUEIL_CONGES/index.php\">";
echo "</HTML>\n";

?>
