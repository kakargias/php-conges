<?
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


/*===============================================================================*/
/*===============================================================================*/

// connexion MySQL + selection de la database sur le serveur
//function  connexion_mysql()

// renvoie le nom de l'utilisateur correspondant a la session
//function session_get_user($session)

// indique (TRUE / FALSE) si une session est valide (par / au temps de connexion)
//function session_is_valid($session)

// cree la session et renvoie son identifiant
//function session_create($username)

// mise a jour d'une session
//function session_update($session)

// destruction d'une session
//function session_delete($session)

// destruction des sessions inactives (c.a.d. dont le temps de connexion est dépassé)
//function delete_expired_session()

// formulaire de saisie du user/password
//function session_saisie_user_password()

// autentifie un user dans le base mysql avec son login et son passwd conges :
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
//function autentification_passwd_conges($username,$password)

// supprime les accents d'une chaine de caracteres
//function supprime_accent($string)

// genère le mot de passe MySQL à partir du mot de passe en clair
//function make_mysql_password($password)


/*===============================================================================*/
/*===============================================================================*/

//
// connexion MySQL + selection de la database sur le serveur
//
function  connexion_mysql()
{
   global $mysql_serveur,$mysql_user,$mysql_pass,$mysql_database;

   $mysql_link = MYSQL_CONNECT($mysql_serveur,$mysql_user,$mysql_pass);
   if (! $mysql_link)
   {
      die("connexion_mysql() : Impossible de se connecter au serveur ".mysql_error());
   }

   $dbselect   = mysql_select_db($mysql_database,$mysql_link);
   if (! $dbselect)
   {
      die("connexion_mysql() : Impossible de se connecter à la base de données ".mysql_error());
   }

   return $mysql_link;
}


//
// renvoie le nom de l'utilisateur correspondant a la session
//
function session_get_user($session)
{
   $found_user="";

   if ($session != "")
   {
      // connexion MySQL + selection de la database sur le serveur
      $mysql_link=connexion_mysql();

      $req_user = "SELECT *   FROM session_appli_conges   WHERE session='$session'";
      $res_user = mysql_query($req_user,$mysql_link) or die("session_get_user() : Erreur ".mysql_error());
      $row_user = mysql_fetch_array($res_user)       or die("session_get_user() : Erreur ".mysql_error());
      if ($row_user)
      {
         $found_user=$row_user['user'];
      }
   }

   return $found_user;
}



//
// indique (TRUE / FALSE) si une session est valide (par / au temps de connexion)
//
function session_is_valid($session)
{
   global $duree_session;

   $is_valid=FALSE;


   if ($session != "")
   {
      // connexion MySQL + selection de la database sur le serveur
      $mysql_link=connexion_mysql();

      $req_temps = "SELECT *   FROM session_appli_conges   WHERE session='$session'";
      $res_temps = mysql_query($req_temps,$mysql_link) or die("session_is_valid()->mysql_query       : Erreur sur '$req_temps' : ".mysql_error());
      if (mysql_num_rows($res_temps))
      {
         $row_temps = mysql_fetch_array($res_temps)       or die("session_is_valid()->mysql_fetch_array : Erreur sur '$res_temps' : ".mysql_error());
      }
      else
      {
         $row_temps="";
      }
      if ($row_temps)
      {
         $found_temps=$row_temps['connexion_last'];
         $maintenant=time();

         if (($maintenant - $found_temps) <= $duree_session)
         {
            $is_valid=TRUE;
         }
      }
   }

   return $is_valid;
}



//
// cree la session et renvoie son identifiant
//
function session_create($username)
{
   if ($username != "")
   {
      // connexion MySQL + selection de la database sur le serveur
      $mysql_link=connexion_mysql();

      // calcul du numero de session
      $session = md5(uniqid(rand()));

      $maintenant=time();

      $req_insert = "INSERT INTO session_appli_conges   (user,session,connexion_start,connexion_last)   VALUES ('$username','$session','$maintenant','$maintenant')";
      $res_insert = mysql_query($req_insert,$mysql_link) or die("session_create() : erreur".mysql_error());
   }
   else
   {
      $session="";
   }

   return   $session;
}



//
// mise a jour d'une session
//
function session_update($session)
{
   if ($session != "")
   {
      // connexion MySQL + selection de la database sur le serveur
      $mysql_link=connexion_mysql();

      $maintenant=time();

      $req_update = "UPDATE session_appli_conges   SET connexion_last='$maintenant'   WHERE session='$session'";
      $res_update = mysql_query($req_update,$mysql_link) or die("session_update() : erreur sur '$req_update' ".mysql_error());
   }
}



//
// destruction d'une session
//
function session_delete($session)
{
   if ($session != "")
   {
      // connexion MySQL + selection de la database sur le serveur
      $mysql_link=connexion_mysql();

      $req_update = "DELETE FROM session_appli_conges   WHERE session='$session'";
      $res_update = mysql_query($req_update,$mysql_link) or die("session_delete() : erreur".mysql_error());
   }
}



//
// destruction des sessions inactives (c.a.d. dont le temps de connexion est dépassé)
//
function delete_expired_session()
{
   global $duree_session;



   // connexion MySQL + selection de la database sur le serveur
   $mysql_link=connexion_mysql();

   $req_temps = "SELECT *   FROM session_appli_conges";
   $res_temps = mysql_query($req_temps,$mysql_link) or die("delete_expired_session()->mysql_query       : Erreur sur '$req_temps' : ".mysql_error());
   if (mysql_num_rows($res_temps))
   {
      while ($row_temps = mysql_fetch_array($res_temps))
      {
         $found_user            = $row_temps['user'];
         $found_session         = $row_temps['session'];
         $found_connexion_last  = $row_temps['connexion_last'];
         $found_connexion_start = $row_temps['connexion_start'];

         $difference = time() - $found_connexion_start;

         if ($difference > $duree_session)
         {
            session_delete($found_session);
         }
      }
   }
}



//
// formulaire de saisie du user/password
//
function session_saisie_user_password()
{
   global   $PHP_SELF;
   global   $session,$session_username,$session_password;

   echo "<CENTER>\n";

   echo "<H2>Bienvenue sur PHP_CONGES</H2><BR>\n";

   echo "<BR>\n";


   echo "<H3>SVP, veuillez saisir votre nom d'utilisateur et votre mot de passe : </H3><BR>\n";

   echo "<FORM METHOD='post' ACTION='$PHP_SELF'>\n";
   echo "<TABLE BGCOLOR='#dcdcdc' BORDER='1'>\n";
   echo "<TR>\n";
   echo "   <TD>Login :</TD>\n";
   echo "   <TD><INPUT TYPE='text'     NAME='session_username' SIZE=32  VALUE='$session_username'></TD>\n";
   echo "</TR>\n";
   echo "<TR>\n";
   echo "   <TD>Mot de Passe :</TD>\n";
   echo "   <TD><INPUT TYPE='password' NAME='session_password' SIZE=32  VALUE='$session_password'></TD>\n";
   echo "</TR>\n";
   echo "<TR>\n";
   echo "   <TD COLSPAN='2'><CENTER><INPUT TYPE='submit' VALUE='Valider'></CENTER></TD>\n";
   echo "</TR>\n";
   echo "</TABLE>\n";
   echo "</FORM>\n";

   echo "</CENTER>\n";
}






//
// autentifie un user dans le base mysql avec son login et son passwd conges :
// - renvoie $username si authentification OK
// - renvoie ""        si authentification FAIL
//
function autentification_passwd_conges($username,$password)
{
   // connexion MySQL + selection de la database sur le serveur
   $mysql_link=connexion_mysql();

   $username_password_ok="";

   $req_conges="SELECT u_passwd   FROM conges_users   WHERE u_login='$username' AND u_passwd=PASSWORD('$password') " ;
   $res_conges = mysql_query($req_conges,$mysql_link) or die("autentification_passwd_conges() : Erreur ".mysql_error());
   $num_row_conges = mysql_num_rows($res_conges);
   if ($num_row_conges !=0)
   {
         $username_password_ok=$username;
   }

   return   $username_password_ok;
}



//
// supprime les accents d'une chaine de caracteres
//
function supprime_accent($string)
{
   global $DEBUG;

   $string_noaccent = $string;
   $string_noaccent = strtr($string_noaccent, "áàâäãéèêëíìîïóòôöõúùûüýÿçñ", "aaaaaeeeeiiiiooooouuuuyycn");
   $string_noaccent = strtr($string_noaccent, "ÁÀÂÄÃÉÈÊËÍÌÎÏÓÒÔÖÕÚÙÛÜÝYÇÑ", "AAAAAEEEEIIIIOOOOOUUUUYYCN");

   return $string_noaccent;
}


//
// genère le mot de passe MySQL à partir du mot de passe en clair
//
function make_mysql_password($password)
{
   // connexion MySQL + selection de la database sur le serveur
   $mysql_link=connexion_mysql();


   $mysqlpassword="";

   $req_password = "SELECT OLD_PASSWORD('$password'),PASSWORD('$password')";
   $res_password = mysql_query($req_password,$mysql_link) or die("make_mysql_password() : Erreur ".mysql_error());

   if ($row_password = mysql_fetch_array($res_password))  /* si un enregistrement deja dans la table unix */
   {
      $mysql_old_password=$row_password["OLD_PASSWORD('$password')"];
      $mysql_new_password=$row_password["PASSWORD('$password')"];

      //
      // ATTENTION : selon la version de MySQL ...
      //
      $mysqlpassword=$mysql_old_password;
   }

   return $mysqlpassword;
}




?>
