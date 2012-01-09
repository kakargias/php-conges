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

include_once __DIR__ .'/get_text.php';

//
// MAIN
//

/*** initialisation des variables ***/
$session_username="";
$session_password="";
/************************************/

//
// recup du num  de session (mais on ne sais pas s'il est passé en GET ou POST
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
		$_SESSION['config']=init_config_tab();  // on recrée le tableau de config pour l'url du lien
		
		echo "<center>\n";
		echo  _('session_pas_session_ouverte') ."<br>\n";
		echo  _('divers_veuillez') ." <a href='".$_SESSION['config']['URL_ACCUEIL_CONGES']."/index.php' target='_top'> ". _('divers_vous_authentifier') ."</a>\n";
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

				$comment_log = "Connexion de $session_username";
				log_action(0, "", $usernameCAS, $comment_log, $DEBUG);
		}
		else //dans ce cas l'utilisateur n'a pas encore été enregistré dans la base de données db_conges
		{
		   echo "<center>\n";
			echo  _('session_pas_de_compte_dans_dbconges') ."<br>\n";
			echo  _('session_contactez_admin') ."\n";
		   echo "</center>\n";
           $URL_ACCUEIL_CONGES=$_SESSION['config']['URL_ACCUEIL_CONGES'];
           deconnexion_CAS($URL_ACCUEIL_CONGES);
           exit;
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

			// le user doit etre authentifié dans la table conges (login + passwd) ou dans le ldap.
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
					if (valid_ldap_user($session_username)==TRUE) // LDAP ok, on vérifie ici que le compte existe dans la base de données des congés.
					{
						// on initialise la nouvelle session
						session_create($session_username);

						// on log la connexion du user 
						if(!isset($session))
						{
								
							$comment_log = "Connexion de $session_username";
							log_action(0, "", $session_username, $comment_log,  $DEBUG);
							
							
						}
						else
						{
							$comment_log = "Connexion de $session_username";
							log_action(0, "", $session_username, $comment_log,  $DEBUG);
						}
					}
					else//dans ce cas l'utilisateur n'a pas encore été enregistré dans la base de données db_conges
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
					
					$comment_log = 'Connexion de '.$session_username;
					log_action(0, '', $session_username, $comment_log, $DEBUG);

				}
			}
		}
	}
   
}


