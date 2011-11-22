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

//
// MAIN
//

/*** initialisation des variables ***/
$session_username="";
$session_password="";
/************************************/

//
// recup du num  de session (mais on ne sais pas s'il est pass� en GET ou POST
$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : "") ) ;

$DEBUG=FALSE;
//$DEBUG=TRUE;

if($DEBUG==TRUE) { print_r($_SESSION); echo "<br><br>\n"; }




if ($session != "") //  UNE SESSION EXISTE
{
	if($DEBUG==TRUE) { echo "session = $session<br><br>\n"; }
	
	if(session_is_valid($session) == TRUE)
	{
		session_update($session);
	}
	else
	{
		session_delete($session);
		$session="";
		$session_username="";
		$session_password="";
		$_SESSION['config']=init_config_tab();  // on recr�e le tableau de config pour l'url du lien
		
//		echo "<center>\n";
//		echo "Pas de session ouverte<br>\n";
//		echo "Veuillez <a href='".$_SESSION['config']['URL_ACCUEIL_CONGES']."/index.php' target='_top'> vous authentifier</a>\n";
//		echo "</center>\n";
		echo "<center>\n";
		echo $_SESSION['lang']['session_pas_session_ouverte']."<br>\n";
		echo $_SESSION['lang']['divers_veuillez']." <a href='".$_SESSION['config']['URL_ACCUEIL_CONGES']."/index.php' target='_top'> ".$_SESSION['lang']['divers_vous_authentifier']."</a>\n";
		echo "</center>\n";

		exit;
	}
}
else    //  PAS DE SESSION   ($session == "")
{
	if($DEBUG==TRUE) { echo "session = chaine VIDE!<br><br>\n"; }

	if(isset($_POST['session_username'])) { $session_username=$_POST['session_username']; }
	if(isset($_POST['session_password'])) { $session_password=$_POST['session_password']; }

	if ( ($_SESSION['config']['how_to_connect_user'] == "CAS") && ($session_username != "admin") )
	{
		if($DEBUG==TRUE) { echo "autentification CAS !<br><br>\n"; }
		
		$usernameCAS = authentification_passwd_conges_CAS();
		if($usernameCAS != "")
		{
			if(session_id()!="")
				session_destroy();
			
			// on initialise la nouvelle session
			session_create($usernameCAS);

			// on log la connexion du user 
			if(!isset($mysql_link))
			{
				//connexion mysql
				$mysql_link = connexion_mysql() ;
					
				$comment_log = "Connexion de $session_username";
				log_action(0, "", $usernameCAS, $comment_log, $mysql_link, $DEBUG);
						
				mysql_close();
			}
			else
			{
				$comment_log = "Connexion de $session_username";
				log_action(0, "", $usernameCAS, $comment_log, $mysql_link, $DEBUG);
			}
		}
		else //dans ce cas l'utilisateur n'a pas encore �t� enregistr� dans la base de donn�es db_conges
		{
		   echo "<center>\n";
		   echo "Il n'existe pas de compte correspondant � votre login dans la base de donn�es de PHP_CONGES<br>\n";
		   echo "Contactez l'administrateur de php_conges";
		   echo "</center>\n";
		}
	}
	else
	{
		if (($session_username == "") || ($session_password == "")) // si login et passwd non saisis
		{
			//  SAISIE LOGIN / PASSWORD :
			session_saisie_user_password("", "", ""); // appel du formulaire d'intentification (login/password)
			exit;
		}
		else
		{
			//  AUTHENTIFICATION :

			// le user doit etre authentifi� dans la table conges (login + passwd) ou dans le ldap.
			// si on a trouve personne qui correspond au couple user/password

			if ( ($_SESSION['config']['how_to_connect_user'] == "ldap") && ($session_username != "admin") )
			{
				if($DEBUG==TRUE) { echo "autentification LDAP !<br><br>\n"; }

				if(session_id()!="")
					session_destroy();
					
				if (authentification_ldap_conges($session_username,$session_password) != $session_username)
				{
					$session="";
					$session_username="";
					$session_password="";

					$erreur="login_passwd_incorrect";
					// appel du formulaire d'intentification (login/password)
					session_saisie_user_password($erreur, $session_username, $session_password);
					exit;
				}

				if ((authentification_ldap_conges($session_username,$session_password) == $session_username) && ($session_username != ""))
				{
					if (valid_ldap_user($session_username)==TRUE) // LDAP ok, on v�rifie ici que le compte existe dans la base de donn�es des cong�s.
					{
						// on initialise la nouvelle session
						session_create($session_username);

						// on log la connexion du user 
						if(!isset($mysql_link))
						{
							//connexion mysql
							$mysql_link = connexion_mysql() ;
								
							$comment_log = "Connexion de $session_username";
							log_action(0, "", $session_username, $comment_log, $mysql_link, $DEBUG);
							
							mysql_close();
						}
						else
						{
							$comment_log = "Connexion de $session_username";
							log_action(0, "", $session_username, $comment_log, $mysql_link, $DEBUG);
						}
					}
					else//dans ce cas l'utilisateur n'a pas encore �t� enregistr� dans la base de donn�es db_conges
					{
						$erreur="login_non_connu";
						session_saisie_user_password($erreur, $session_username,$session_password); // appel du formulaire d'intentification (login/password)
						exit;	         	         	
					}
				}
			} // fin du if test avec ldap
			elseif ( ($_SESSION['config']['how_to_connect_user'] == "dbconges") || ($session_username == "admin") )
			{
				if($DEBUG==TRUE) { echo "autentification DBCONGES !<br><br>\n"; }

				if(session_id()!="")
					session_destroy();
					
				if (autentification_passwd_conges($session_username,$session_password) != $session_username)
				{
					$session="";
					$session_username="";
					$session_password="";

					$erreur="login_passwd_incorrect";
					session_saisie_user_password($erreur, $session_username,$session_password); // appel du formulaire d'intentification (login/password)
					exit;
				}

				if ((autentification_passwd_conges($session_username,$session_password) == $session_username) && ($session_username != ""))
				{
					// on initialise la nouvelle session
					session_create($session_username);
					
					// on log la connexion du user 
					if(!isset($mysql_link))
					{
						//connexion mysql
						$mysql_link = connexion_mysql() ;
							
						$comment_log = "Connexion de $session_username";
						log_action(0, "", $session_username, $comment_log, $mysql_link, $DEBUG);
						
						mysql_close();
					}
					else
					{
						$comment_log = "Connexion de $session_username";
						log_action(0, "", $session_username, $comment_log, $mysql_link, $DEBUG);
					}
				}
			}
		}
	}
   
}





?>
