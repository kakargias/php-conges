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


define('_PHP_CONGES', 1);
define('ROOT_PATH', '../');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : "") ) ;

include CONFIG_PATH .'config_ldap.php';
include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';
if(!isset($_SESSION['config']))
	$_SESSION['config']=init_config_tab();      // on initialise le tableau des variables de config
include INCLUDE_PATH .'session.php';


//$DEBUG = TRUE ;
$DEBUG = FALSE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_admin", $DEBUG);



	/*** initialisation des variables ***/
	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$action         = getpost_variable("action") ;
	$tab_new_values = getpost_variable("tab_new_values");
	$id_to_update   = getpost_variable("id_to_update");

	/*************************************/

	if($DEBUG)
	{
		print_r($tab_new_values); echo "<br>\n";
		echo "$action<br>\n";
		echo "$id_to_update<br>\n";
	}


	// => html sans menu
	
	// affichage début page
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
		echo "<link href=\"". TEMPLATE_PATH .$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
		echo "<TITLE> CONGES : Configuration </TITLE>\n";
	echo "</head>\n";

	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\">\n";

	/*********************************/
	/*********************************/

	if($action=="new")
		commit_ajout($tab_new_values,$session, $DEBUG);
	elseif($action=="modif")
		modifier($tab_new_values, $session, $id_to_update, $DEBUG);
	elseif($action=="commit_modif")
		commit_modif($tab_new_values, $session, $id_to_update, $DEBUG);
	elseif($action=="suppr")
		supprimer($session, $id_to_update, $DEBUG);
	elseif($action=="commit_suppr")
		commit_suppr($session, $id_to_update, $DEBUG);
	else
		affichage($tab_new_values, $session, $DEBUG);

	/*********************************/
	/*********************************/

	// affichage fin page
	echo "</body>";
	echo "</html>";



/**************************************************************************************/
/**********  FONCTIONS  ***************************************************************/


function affichage($tab_new_values,$session, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];

	if($session=="")
		$URL = "$PHP_SELF";
	else
		$URL = "$PHP_SELF?session=$session";

	/**************************************/
	// affichage du titre
	echo "<br><center><H1> ". _('config_abs_titre') ."</H1></center>\n";
	echo "<br>\n";
	/**************************************/

	affiche_bouton_retour($session);


	// affichage de la liste des type d'absence existants

	$tab_enum = get_tab_from_mysql_enum_field("conges_type_absence", "ta_type", $DEBUG);

	foreach($tab_enum as $ta_type)
	{
		if( ($ta_type=="conges_exceptionnels") &&  ($_SESSION['config']['gestion_conges_exceptionnels']==FALSE))
		{
		}
		else
		{
			$divers_maj_1 = 'divers_' . $ta_type . '_maj_1';
			$config_abs_comment = 'config_abs_comment_' . $ta_type;

			$legend= _($divers_maj_1)  ;
			$comment= _($config_abs_comment)  ;

			echo "<br>\n";
			echo "<table width=\"100%\">\n";
			echo "<tr><td>\n";
			echo "    <fieldset class=\"cal_saisie\">\n";
			echo "    <legend class=\"boxlogin\">$legend</legend>\n";
			echo "    <i>$comment</i><br><br>\n";

			//requête qui récupère les informations de la table conges_type_absence
			$sql1 = 'SELECT * FROM conges_type_absence WHERE ta_type = \''.SQL::quote($ta_type).'\'';
			$ReqLog1 = SQL::query($sql1);

			if($ReqLog1->num_rows !=0)
			{
				echo "    <table cellpadding=\"2\" class=\"tablo\" >\n";
				echo "    <tr>\n";
				echo "    <td class=\"titre\"><b><u>". _('config_abs_libelle') ."</b></u></td>\n";
				echo "    <td class=\"titre\"><b><u>". _('config_abs_libelle_short') ."</b></u></td>\n";
				echo "    <td></td>\n";
				echo "    <td></td>\n";
				echo "    </tr>\n";

				while ($data = $ReqLog1->fetch_array())
				{
				 	$ta_id = $data['ta_id'];
					$ta_libelle = $data['ta_libelle'];
					$ta_short_libelle = $data['ta_short_libelle'];

					if($session=="")
					{
						$text_modif="<a href=\"$PHP_SELF?action=modif&id_to_update=$ta_id\">". _('form_modif') ."</a>";
						$text_suppr="<a href=\"$PHP_SELF?action=suppr&id_to_update=$ta_id\">". _('form_supprim') ."</a>";
					}
					else
					{
						$text_modif="<a href=\"$PHP_SELF?session=$session&action=modif&id_to_update=$ta_id\">". _('form_modif') ."</a>";
						$text_suppr="<a href=\"$PHP_SELF?session=$session&action=suppr&id_to_update=$ta_id\">". _('form_supprim') ."</a>";
					}

					echo "    <tr><td class=\"histo\"><b>$ta_libelle</b></td><td class=\"histo\">$ta_short_libelle</td><td class=\"histo\">$text_modif</td><td class=\"histo\">$text_suppr</td></tr>\n";
				}

				echo "    </table>\n";
				echo "</td></tr>\n";
				echo "</table>\n";
			}

			echo "    </table>\n";
			echo "</td></tr>\n";
			echo "</table>\n";
		}
	}


	/**************************************/
	// saisie de nouveaux type d'absence

	echo "<br>\n";
	echo "<table width=\"100%\">\n";
	echo "<tr><td>\n";
	echo "    <form action=\"$URL\" method=\"POST\"> \n";
	echo "    <fieldset class=\"cal_saisie\">\n";
	echo "    <legend class=\"boxlogin\">". _('config_abs_add_type_abs') ."</legend>\n";
	echo "    ". _('config_abs_add_type_abs_comment') ."\n";
	echo "    <table cellpadding=\"2\" >\n";
	echo "    <tr>\n";
	echo "    <td class=\"titre\">". _('config_abs_libelle') ."</td>\n";
	echo "    <td class=\"titre\">". _('config_abs_libelle_short') ."</td>\n";
	echo "    <td class=\"titre\">". _('divers_type') ."</td>\n";
	echo "    </tr>\n";

	echo "    <tr>\n";

	$new_libelle = ( isset($tab_new_values['libelle']) ? $tab_new_values['libelle'] : "" );
	$new_short_libelle = ( isset($tab_new_values['short_libelle']) ? $tab_new_values['short_libelle'] : "" ) ;
	$new_type = ( isset($tab_new_values['type']) ? $tab_new_values['type'] : "" ) ;
	echo "    <td class=\"histo\"><input type=\"text\" name=\"tab_new_values[libelle]\" size=\"20\" maxlength=\"20\" value=\"$new_libelle\" ></td>\n";
	echo "    <td class=\"histo\"><input type=\"text\" name=\"tab_new_values[short_libelle]\" size=\"3\" maxlength=\"3\" value=\"$new_short_libelle\" ></td>\n";
	echo "    <td class=\"histo\">\n";

      echo "<SELECT NAME=tab_new_values[type]>\n";

	   foreach($tab_enum as $option)
	   {
			if( ($option=="conges_exceptionnels") &&  ($_SESSION['config']['gestion_conges_exceptionnels']==FALSE))
	   		{
	   		}
	   		else
	   		{
		         if($option==$new_type)
		            echo "<OPTION selected>$option</OPTION>\n";
		         else
		            echo "<OPTION>$option</OPTION>\n";
	   		}
	   }

	   echo "</SELECT>\n";

	echo "    </td></tr>\n";
	echo "    </table>\n";

	echo "    <input type=\"hidden\" name=\"action\" value=\"new\">\n";
	echo "    <input type=\"submit\"  value=\"". _('form_ajout') ."\"><br>\n";
	echo "    </form>\n";
	echo "</td></tr>\n";

	echo "</table>\n";

	// Bouton de retour : différent suivant si on vient des pages d'install ou de l'appli
	echo "<br><br>\n";
	affiche_bouton_retour($session);
	echo "<br><br>\n";
}


function modifier(&$tab_new_values, $session, $id_to_update, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];

	if($session=="")
		$URL = "$PHP_SELF";
	else
		$URL = "$PHP_SELF?session=$session";

	/**************************************/
	// affichage du titre
	echo "<br><center><H1> ". _('config_abs_titre') ."</H1></center>\n";
	echo "<br>\n";

	// recup des infos du type de conges / absences
	$sql_cong='SELECT ta_type, ta_libelle, ta_short_libelle FROM conges_type_absence WHERE ta_id = '.SQL::quote($id_to_update);

	$ReqLog_cong = SQL::query($sql_cong);

	if($resultat_cong = $ReqLog_cong->fetch_array())
	{
		$sql_type=$resultat_cong['ta_type'];
		$sql_libelle= $resultat_cong['ta_libelle'];
		$sql_short_libelle= $resultat_cong['ta_short_libelle'];
	}

	// mise en place du formulaire
	echo "<form action=\"$URL\" method=\"POST\"> \n";

	$text_libelle ="<input type=\"text\" name=\"tab_new_values[libelle]\" size=\"20\" maxlength=\"20\" value=\"$sql_libelle\" >";
	$text_short_libelle ="<input type=\"text\" name=\"tab_new_values[short_libelle]\" size=\"3\" maxlength=\"3\" value=\"$sql_short_libelle\" >";

	// affichage
	echo "<table cellpadding=\"2\" class=\"tablo\" >\n";
	echo "    <tr>\n";
	echo "    <td class=\"titre\"><b><u>". _('config_abs_libelle') ."</b></u></td>\n";
	echo "    <td class=\"titre\"><b><u>". _('config_abs_libelle_short') ."</b></u></td>\n";
	echo "    <td class=\"titre\">". _('divers_type') ."</td>\n";
	echo "    </tr>\n";
	echo "    <tr><td class=\"histo\"><b>$sql_libelle</b></td><td class=\"histo\">$sql_short_libelle</td><td class=\"histo\">$sql_type</td></tr>\n";
	echo "    <tr><td class=\"histo\"><b>$text_libelle</b></td><td class=\"histo\">$text_short_libelle</td><td class=\"histo\"></td></tr>\n";

	echo "</table>\n";
	echo "<br>\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"commit_modif\">\n";
	echo "<input type=\"hidden\" name=\"id_to_update\" value=\"$id_to_update\">\n";
	echo "<input type=\"submit\"  value=\"". _('form_modif') ."\">\n";
	echo "</form>\n";

	echo "<br>\n";
	echo "<form action=\"$URL\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"". _('form_annul') ."\" >\n";
	echo "</form>\n";
	echo "<br><br>\n";

}


function commit_modif(&$tab_new_values, $session, $id_to_update, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];

	if($session=="")
		$URL = "$PHP_SELF";
	else
		$URL = "$PHP_SELF?session=$session";


	// verif de la saisie
	$erreur=FALSE ;
	// verif si pas de " ' , . ; % ?
	if( (preg_match('/.*[?%;.,"\'].*$/', $tab_new_values['libelle'])) || (preg_match('/.*[?%;.,"\'].*$/', $tab_new_values['short_libelle'])) )
	{
		echo "<br> ". _('config_abs_saisie_not_ok') ." : ". _('config_abs_bad_caracteres') ."  \" \' , . ; % ? <br>\n";
		$erreur=TRUE;
	}
	// verif si les champs sont vides
	if( (strlen($tab_new_values['libelle'])==0) || (strlen($tab_new_values['short_libelle'])==0) )
	{
		echo "<br> ". _('config_abs_saisie_not_ok') ." : ". _('config_abs_champs_vides') ." <br>\n";
		$erreur=TRUE;
	}


	if($erreur)
	{
		echo "<br>\n";
		if($session=="")
			echo "<form action=\"$PHP_SELF\" method=\"POST\"> \n";
		else
			echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\"> \n";
		echo "<input type=\"hidden\" name=\"action\" value=\"modif\">\n";
		echo "<input type=\"hidden\" name=\"id_to_update\" value=\"$id_to_update\">\n";
		echo "<input type=\"hidden\" name=\"tab_new_values[libelle]\" value=\"".$tab_new_values['libelle']."\">\n";
		echo "<input type=\"hidden\" name=\"tab_new_values[short_libelle]\" value=\"".$tab_new_values['short_libelle']."\">\n";
		echo "<input type=\"submit\" value=\"". _('form_redo') ."\" >\n";
		echo "</form>\n";
		echo "<br><br>\n";
	}
	else
	{
		// update de la table
		$req_update='UPDATE conges_type_absence SET ta_libelle=\''.$tab_new_values['libelle'].'\', ta_short_libelle=\''.$tab_new_values['short_libelle'].'\' WHERE ta_id=\''.SQL::quote($id_to_update).'\' ';
		$result1 = SQL::query($req_update);

		echo "<span class = \"messages\">". _('form_modif_ok') ."</span><br>";

		$comment_log = "config : modif_type_absence ($id_to_update): ".$tab_new_values['libelle']."  (".$tab_new_values['short_libelle'].") ";
		log_action(0, "", "", $comment_log, $DEBUG);
	
		if($DEBUG==TRUE)
				echo "<a href=\"$URL\" method=\"POST\">". _('form_retour') ."</a><br>\n" ;
			else
				echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$URL\">";
	}
}


function supprimer($session, $id_to_update, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];

	if($session=="")
		$URL = "$PHP_SELF";
	else
		$URL = "$PHP_SELF?session=$session";


	// verif si pas de periode de ce type de conges !!!
	//requête qui récupère les informations de la table conges_periode
	$sql1 = 'SELECT p_num FROM conges_periode WHERE p_type=\''.SQL::quote($id_to_update).'\'';
	$ReqLog1 = SQL::query($sql1);

	$count= ($ReqLog1->num_rows) ;

	if( $count!=0 )
	{
		echo "<center>\n";
		echo "<br> ". _('config_abs_suppr_impossible') ."<br>\n". _('config_abs_already_used') ." <br>\n";

		echo "<br>\n";
		echo "<form action=\"$URL\" method=\"POST\"> \n";
		echo "<input type=\"submit\" value=\"". _('form_redo') ."\" >\n";
		echo "</form>\n";
		echo "<br><br>\n";
		echo "</center>\n";
	}
	else
	{
		// recup dans un tableau de tableau les infos des types de conges et absences
		$tab_type_abs = recup_tableau_tout_types_abs($DEBUG);

		echo "<center>\n";
		echo "<br>\n";
		echo  _('config_abs_confirm_suppr_of') ."  <b>\" ".$tab_type_abs[$id_to_update]['libelle']." \"</b>\n";
		echo "<br>\n";
		echo "<form action=\"$URL\" method=\"POST\"> \n";
		echo "<input type=\"hidden\" name=\"action\" value=\"commit_suppr\">\n";
		echo "<input type=\"hidden\" name=\"id_to_update\" value=\"$id_to_update\">\n";
		echo "<input type=\"submit\"  value=\"". _('form_supprim') ."\">\n";
		echo "</form>\n";

		echo "<br>\n";
		echo "<form action=\"$URL\" method=\"POST\"> \n";
		echo "<input type=\"submit\" value=\"". _('form_annul') ."\" >\n";
		echo "</form>\n";
		echo "<br><br>\n";
		echo "</center>\n";

	}
}



function commit_suppr($session, $id_to_update, $DEBUG=FALSE)
{

	$PHP_SELF=$_SERVER['PHP_SELF'];

	if($session=="")
		$URL = "$PHP_SELF";
	else
		$URL = "$PHP_SELF?session=$session";
	if($DEBUG==TRUE) { echo "URL = $URL<br>\n"; }

	// delete dans la table conges_type_absence
	$req_delete1='DELETE FROM conges_type_absence WHERE ta_id='.SQL::quote($id_to_update);
	$result1 = SQL::query($req_delete1);

	// delete dans la table conges_solde_user
	$req_delete2='DELETE FROM conges_solde_user WHERE su_abs_id='.SQL::quote($id_to_update);
	$result2 = SQL::query($req_delete2);

	echo "<span class = \"messages\">". _('form_modif_ok') ."</span><br>";

	$comment_log = "config : supprime_type_absence ($id_to_update) ";
	log_action(0, "", "", $comment_log,$DEBUG);

	if($DEBUG==TRUE)
		echo "<a href=\"$URL\" method=\"POST\">". _('form_retour') ."</a><br>\n" ;
	else
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$URL\">";
}



function commit_ajout(&$tab_new_values, $session, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	if($session=="")
		$URL = "$PHP_SELF";
	else
		$URL = "$PHP_SELF?session=$session";
	if($DEBUG==TRUE) { echo "URL = $URL<br>\n"; }

	// verif de la saisie
	$erreur=FALSE ;
	// verif si pas de " ' , . ; % ?
	if( (preg_match('/.*[?%;.,"\'].*$/', $tab_new_values['libelle'])) || (preg_match('/.*[?%;.,"\'].*$/', $tab_new_values['short_libelle'])) )
	{
		echo "<br> ". _('config_abs_saisie_not_ok') ." : ". _('config_abs_bad_caracteres') ."  \" \' , . ; % ? <br>\n";
		$erreur=TRUE;
	}
	// verif si les champs sont vides
	if( (strlen($tab_new_values['libelle'])==0) || (strlen($tab_new_values['short_libelle'])==0) || (strlen($tab_new_values['type'])==0) )
	{
		echo "<br> ". _('config_abs_saisie_not_ok') ." : ". _('config_abs_champs_vides') ." <br>\n";
		$erreur=TRUE;
	}


	if($erreur)
	{
		echo "<br>\n";
		echo "<form action=\"$URL\" method=\"POST\"> \n";
		echo "<input type=\"hidden\" name=\"id_to_update\" value=\"$id_to_update\">\n";
		echo "<input type=\"hidden\" name=\"tab_new_values[libelle]\" value=\"".$tab_new_values['libelle']."\">\n";
		echo "<input type=\"hidden\" name=\"tab_new_values[short_libelle]\" value=\"".$tab_new_values['short_libelle']."\">\n";
		echo "<input type=\"hidden\" name=\"tab_new_values[type]\" value=\"".$tab_new_values['type']."\">\n";
		echo "<input type=\"submit\" value=\"". _('form_redo') ."\" >\n";
		echo "</form>\n";
		echo "<br><br>\n";
	}
	else
	{
		// ajout dans la table conges_type_absence
		$req_insert1="INSERT INTO conges_type_absence (ta_libelle, ta_short_libelle, ta_type) " .
				"VALUES ('".$tab_new_values['libelle']."', '".$tab_new_values['short_libelle']."', '".$tab_new_values['type']."') ";
		$result1 = SQL::query($req_insert1);

	    // on recup l'id de l'absence qu'on vient de créer
	    $new_abs_id = get_last_absence_id($DEBUG);

		if($new_abs_id!=0)
		{
			// ajout dans la table conges_solde_user (pour chaque user !!)(si c'est un conges, pas si c'est une absence)
			if( ($tab_new_values['type']=="conges") || ($tab_new_values['type']=="conges_exceptionnels") )
			{
				// recup de users :
			    $sql_users="SELECT DISTINCT(u_login) FROM conges_users WHERE u_login!='conges' AND u_login!='admin' " ;

				$ReqLog_users = SQL::query($sql_users);

				while ($resultat1 = $ReqLog_users->fetch_array())
				{
					$current_login=$resultat1["u_login"];

					$req_insert2="INSERT INTO conges_solde_user (su_login, su_abs_id, su_nb_an, su_solde, su_reliquat) " .
							"VALUES ('$current_login', $new_abs_id, 0, 0, 0) ";
					$result2 = SQL::query($req_insert2);
				}
			}
			echo "<span class = \"messages\">". _('form_modif_ok') ."</span><br>";
		}

	$comment_log = "config : ajout_type_absence : ".$tab_new_values['libelle']."  (".$tab_new_values['short_libelle'].") (type : ".$tab_new_values['type'].") ";
	log_action(0, "", "", $comment_log, $DEBUG);

	if($DEBUG==TRUE)
			echo "<a href=\"$URL\" method=\"POST\">". _('form_retour') ."</a><br>\n" ;
		else
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$URL\">";
	}

}


