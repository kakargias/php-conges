<?php
/*************************************************************************************************
PHP_CONGES : Gestion Interactive des Cong�s
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les 
termes de la Licence Publique G�n�rale GNU publi�e par la Free Software Foundation.
Ce programme est distribu� car potentiellement utile, mais SANS AUCUNE GARANTIE, 
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation 
dans un but sp�cifique. Reportez-vous � la Licence Publique G�n�rale GNU pour plus de d�tails.
Vous devez avoir re�u une copie de la Licence Publique G�n�rale GNU en m�me temps 
que ce programme ; si ce n'est pas le cas, �crivez � la Free Software Foundation, 
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, �tats-Unis.
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

//appel de PHP-IDS que si version de php > 5.1.2
if(phpversion() > "5.1.2") { include("controle_ids.php") ;}

// test si dbconnect.php est pr�sent !
if (!is_readable("dbconnect.php"))
{
	echo "connexion a la database impossible, consultez le fichier INSTALL.txt !<br>\n"; 
	exit;
}


include("fonctions_conges.php") ;
$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
include("INCLUDE.PHP/fonction.php");


/***** DEBUT DU PROG *****/

/*** initialisation des variables ***/
/************************************/

// DEBUG
//print_r($_SESSION); echo "<br><br>\n";echo "session= $session<br><br>\n";

// connexion database :
$mysql_link=connexion_mysql();

if($_SESSION['config']['auth']==FALSE)    // si pas d'autentification (cf config de php_conges)
{
     $login=getpost_variable("login");
	if($login=="") 
	{
		header("Location: erreur.php?error_num=1");
	}
	else 
	{
		if(session_id()!="")
			session_destroy();
		
		// on initialise la nouvelle session
		ini_set ( "session.gc_maxlifetime", $_SESSION['config']['duree_session'] );
		session_create($login);
	}
}
else 
{
	include("INCLUDE.PHP/session.php");  // qui va appeler la fenetre d'authentificatioon si besoin
}

/*****************************************************************/

if(isset($_SESSION['userlogin']))
{
	$request= "SELECT u_nom, u_passwd, u_prenom, u_is_resp FROM conges_users where u_login = '".$_SESSION['userlogin']."' " ;
	$rs = mysql_query($request , $mysql_link) or die("Erreur : index.php : ".mysql_error());
	if(mysql_num_rows($rs) <= 0)
	{
		header("Location: index.php");
	}
	else
	{
		$session=session_id();
		$row = mysql_fetch_array($rs);

		$NOM=$row["u_nom"];
		$PRENOM=$row["u_prenom"];
		$is_resp=$row["u_is_resp"]; 

		// si le login est celui d'un responsable ET on est pas en mode "responsable virtuel"
		// OU on est en mode "responsable virtuel" avec login= celui du resp virtuel
		if ( (($is_resp=="Y")&&($_SESSION['config']['responsable_virtuel']==FALSE)) || (($_SESSION['config']['responsable_virtuel']==TRUE)&&($session_username=="conges")) )
		{
			// redirection vers responsable/resp_index.php
			header("Location: responsable/resp_index.php?session=$session");
			exit;
		}
		else
		{
			// redirection vers utilisateur/user_index.php
			header("Location: utilisateur/user_index.php?session=$session");
			exit;
		}

	}
}

?>
