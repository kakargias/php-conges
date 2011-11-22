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

//session_start();

include("config.php") ;
include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
//include("INCLUDE.PHP/session.php");


/***** DEBUT DU PROG *****/

// connexion database :
$link=connexion_mysql();

if($config_auth==0) {             // si pas d'autentification demandée dans config.php
	if(!isset($login)) {
		header("Location: erreur.php?error_num=1");
	}
	else {
		$session_username=$login ;
	}
}
else {
	include("INCLUDE.PHP/session.php");	
}

if(isset($session_username))
{
	$request= "SELECT u_login, u_nom, u_passwd, u_prenom, u_is_resp FROM conges_users where u_login = '$session_username' " ;
	$rs = mysql_query($request , $link) or die("Erreur : index.php : ".mysql_error());
	if(@mysql_numrows($rs) <= 0)
	{
		header("Location: index.php");
	}
	else
	{
	//session_username=$login;

		$row = mysql_fetch_array($rs);

		$session_username=$row["u_login"];
		$NOM=$row["u_nom"];
		$PRENOM=$row["u_prenom"];


		if ($row["u_is_resp"] == "Y")
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
