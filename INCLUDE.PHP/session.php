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




//
// MAIN
//

/*** initialisation des variables ***/
$session="";
$session_username="";
$session_password="";
/************************************/

//
// destruction des sessions inactives (c.a.d. dont le temps de connexion est dépassé)
//
delete_expired_session();


//
//
// recup du num  de session (mais on ne sais pas s'il est passé en GET ou POST
if(isset($_GET['session'])) { $session=$_GET['session']; }
if ($session == "")
	if(isset($_POST['session'])) { $session=$_POST['session']; }

	
if ($session != "")
{
	if(session_is_valid($session) == TRUE)
	{
		session_update($session);
		$session_username=session_get_user($session);
	}
	else
	{
		$session="";
		$session_username="";
		$session_password="";

		echo "<center>\n";
		echo "Pas de session ouverte<br>\n";
		echo "Veuillez <a href='$URL_ACCUEIL_CONGES/index.php' target='_top'> vous authentifier</a>\n";
		echo "</center>\n";

		exit;
	}
}
else    //  $session == ""
{
	if(isset($_POST['session_username'])) { $session_username=$_POST['session_username']; }
	if(isset($_POST['session_password'])) { $session_password=$_POST['session_password']; }

	if ( ($config_how_to_connect_user == "CAS") && ($session_username != "admin") )
	{
		$usernameCAS = authentification_passwd_conges_CAS();
		if($usernameCAS != "")
		{
			$session=session_create($usernameCAS);
			$session_username=session_get_user($session);
		}
		else //dans ce cas l'utilisateur n'a pas encore été enregistré dans la base de données db_conges
		{
		   echo "<center>\n";
		   echo "Il n'existe pas de compte correspondant à votre login dans la base de données de PHP_CONGES<br>\n";
		   echo "Contactez l'administrateur de php_conges";
		   echo "</center>\n";
		}
	}
	else
	{
		if (($session_username == "") || ($session_password == "")) // si login et passwd non saisis
		{
			session_saisie_user_password(""); // appel du formulaire d'intentification (login/password)
			exit;
		}
		else
		{
			//  AUTHENTIFICATION :

			// le user doit etre authentifié dans la table conges (login + passwd) ou dans le ldap.
			// si on a trouve personne qui correspond au couple user/password

			if ( ($config_how_to_connect_user == "ldap") && ($session_username != "admin") )
			{
				if (authentification_ldap_conges($session_username,$session_password) != $session_username)
				{
					$session="";
					$session_username="";
					$session_password="";

					$erreur="login_passwd_incorrect";
					session_saisie_user_password($erreur); // appel du formulaire d'intentification (login/password)
					exit;
				}

				if ((authentification_ldap_conges($session_username,$session_password) == $session_username) && ($session_username != ""))
				{
					if (valid_ldap_user($session_username)==TRUE) // LDAP ok, on vérifie ici que le compte existe dans la base de données des congés.
					{
						$session=session_create($session_username);
						$session_username=session_get_user($session);
					}
					else//dans ce cas l'utilisateur n'a pas encore été enregistré dans la base de données db_conges
					{
						$erreur="login_non_connu";
						session_saisie_user_password($erreur); // appel du formulaire d'intentification (login/password)
						exit;	         	         	
					}
				}
			} // fin du if test avec ldap
			elseif ($config_how_to_connect_user == "dbconges")
			{
				if (autentification_passwd_conges($session_username,$session_password) != $session_username)
				{
					$session="";
					$session_username="";
					$session_password="";

					$erreur="login_passwd_incorrect";
					session_saisie_user_password($erreur); // appel du formulaire d'intentification (login/password)
					exit;
				}

				if ((autentification_passwd_conges($session_username,$session_password) == $session_username) && ($session_username != ""))
				{
					$session=session_create($session_username);
					$session_username=session_get_user($session);
				}
			}
		}
	}
   
}







?>
