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

//
// destruction des sessions inactives (c.a.d. dont le temps de connexion est d�pass�)
//
delete_expired_session();


//
//
//
if ($session == "")
{
   
   if (($session_username == "") || ($session_password == ""))
   {
      session_saisie_user_password();
	 exit;
   }
   else
   {
      //  AUTHENTIFICATION :

      // le user doit etre authentifi� dans la table conges (login + passwd) .
      // si on a trouve personne qui correspond au couple user/password


      if (autentification_passwd_conges($session_username,$session_password) != $session_username)
      {
         $session="";
         $session_username="";
         $session_password="";

         echo "<CENTER>\n";
         echo "<H3>Nom d'utilisateur et/ou mot de passe incorrect !!!</H3><BR>\n";
         echo "</CENTER>\n";

         session_saisie_user_password();
	    exit;
      }

      if ((autentification_passwd_conges($session_username,$session_password) == $session_username) && ($session_username != ""))
      {
         $session=session_create($session_username);
         $session_username=session_get_user($session);

         //update_connexion($session_username,$CONNEXION_SERVICENAME);

/*
         if (($REQUEST_URI == "") || (ereg(".*index\.php.*",$REQUEST_URI)))
         {
         }
         else
         {
            echo "<frameset rows=\"111,1*\">\n";
            echo "   <frame name=\"page_haut\" src=\"/menu_haut.php\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\">\n";
            echo "   <frameset cols=\"170,1*\">\n";
            echo "      <frame name=\"bas_page\"    src=\"/menu_bleu.php?session=$session\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\">\n";
            echo "      <frame name=\"centre_page\" src=\"$REQUEST_URI?session=$session\"  frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\">\n";
            echo "   </frameset>\n";
            echo "<noframes>\n";
         }
*/
      }

   }
   
}



if (($session != "") && (session_is_valid($session) == TRUE))
{
   session_update($session);
   $session_username=session_get_user($session);
}



if (($session != "") && (session_is_valid($session) != TRUE))
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




?>
