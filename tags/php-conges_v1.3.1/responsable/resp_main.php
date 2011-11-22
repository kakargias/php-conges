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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG = FALSE ;
//$DEBUG = TRUE ;

// verif des droits du user à afficher la page
verif_droits_user($session, "is_resp", $DEBUG);


	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	/*************************************/
	


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";	
echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
include("../fonctions_javascript.php") ;
echo "</head>\n";
	
	$bgimage=$_SESSION['config']['URL_ACCUEIL_CONGES']."/".$_SESSION['config']['bgimage'];
	echo "<body text=\"#000000\" bgcolor=".$_SESSION['config']['bgcolor']." link=\"#000080\" vlink=\"#800080\" alink=\"#FF0000\" background=\"$bgimage\">\n";
	echo "<CENTER>\n";

	/*****************************************************************************/
	// AFFICHAGE DES BOUTONS "deconnexion" et "actualiser page" et "affichage calendrier" :

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"98%\"><tr>\n";
	// bouton deconnexion
	if(($_SESSION['config']['auth']==TRUE)&&($_SESSION['config']['verif_droits']!=TRUE))
	{
		echo "<td width=\"120\" valign=\"middle\">\n";
		bouton_deconnexion();
		echo "</td>\n";
	}
	// bouton actualiser
	echo "<td width=\"150\" valign=\"middle\">\n";
	bouton_actualiser("", $DEBUG);
	echo "</td>\n";
	
	// cellule centrale vide
	echo "<td align=\"center\" valign=\"middle\">\n";
		echo "&nbsp;\n";
	echo "</td>\n";
	
	if($_SESSION['config']['resp_affiche_calendrier']==TRUE)
	{
		// bouton imprim calendrier
		echo "<td width=\"140\" align=\"right\" valign=\"middle\">\n";
			echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../imprim_calendrier.php?session=$session','mapage',200,200);\">" .
			//echo "<a href=\"../calendrier.php?session=$session\">" .
			 "<img src=\"../img/fileprint_4_22x22.png\" width=\"22\" height=\"22\" border=\"0\" title=\"".$_SESSION['lang']['button_imprim_calendar']."\" alt=\"".$_SESSION['lang']['button_imprim_calendar']."\">" .
			 "</a> ".$_SESSION['lang']['button_imprim_calendar']."\n";
		echo "</td>\n";
		
		// bouton calendrier
		echo "<td width=\"140\" align=\"right\" valign=\"middle\">\n";
			echo "<a href=\"javascript:void(0);\" onClick=\"javascript:OpenPopUp('../calendrier.php?session=$session','mapage',850,600);\">" .
			//echo "<a href=\"../calendrier.php?session=$session\">" .
			 "<img src=\"../img/rebuild.png\" width=\"22\" height=\"22\" border=\"0\" title=\"".$_SESSION['lang']['button_calendar']."\" alt=\"".$_SESSION['lang']['button_calendar']."\">" .
			 "</a> ".$_SESSION['lang']['button_calendar']."\n";
		echo "</td>\n";
	}
	echo "</tr></table>\n";
	
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	// recup du tableau des types de conges (seulement les conges)
	$tab_type_cong=recup_tableau_types_conges($mysql_link, $DEBUG);
	
	// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) {
	  $tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels($mysql_link, $DEBUG);
	}

	$sql1 = "SELECT u_nom, u_prenom FROM conges_users where u_login = '".$_SESSION['userlogin']."' ";
	$ReqLog1 = requete_mysql($sql1, $mysql_link, "resp_main", $DEBUG);

	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$NOM=$resultat1["u_nom"];
		$PRENOM=$resultat1["u_prenom"];
	}
	
	/***********************************/
	// TITRE

	if($_SESSION['config']['responsable_virtuel']==FALSE)
		echo "<H1>$PRENOM $NOM</H1>\n\n";
	else
		echo "<H1>responsable</H1>\n\n";
	
	echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";

	
	/***********************************/
	// AFFICHAGE ETAT CONGES TOUS USERS
	
        /***********************************/
	// AFFICHAGE TABLEAU (premiere ligne)
	echo "<H2>Etat des congès:</H2>\n\n";
	
	echo "<TABLE cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
	
	echo "<tr align=\"center\">\n";
	echo "<td class=\"titre\">".$_SESSION['lang']['divers_nom_maj']."</td>\n";
	echo "<td class=\"titre\">".$_SESSION['lang']['divers_prenom_maj']."</td>\n";
	echo "<td class=\"titre\">".$_SESSION['lang']['divers_quotite_maj_1']."</td>" ;
	$nb_colonnes = 3;
	foreach($tab_type_cong as $id_conges => $libelle)
	{
		// cas d'une absence ou d'un congé
		echo "<td class=\"titre\"> $libelle"." / ".$_SESSION['lang']['divers_an_maj']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_solde_maj']." ".$libelle ."</td>";
		$nb_colonnes += 2;
	}
	// conges exceptionnels
	if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE)
	{
		foreach($tab_type_conges_exceptionnels as $id_type_cong => $libelle)
		{
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_solde_maj']." $libelle</td>\n";
			$nb_colonnes += 1;
		}
	}
	echo "<td class=\"titre\"></td>";
    $nb_colonnes += 1;
    if($_SESSION['config']['editions_papier']==TRUE)
    {
		echo "<td class=\"titre\"></td>";
		$nb_colonnes += 1;
	}
	echo "</tr>\n";

	/***********************************/
	// AFFICHAGE USERS
	
	/***********************************/
	// AFFICHAGE DE USERS DIRECTS DU RESP

	// Récup dans un tableau de tableau des informations de tous les users dont $_SESSION['userlogin'] est responsable
	$tab_all_users=recup_infos_all_users_du_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
	if($DEBUG==TRUE) {echo "tab_all_users :<br>\n";  print_r($tab_all_users); echo "<br>\n"; }

	if(count($tab_all_users)==0) // si le tableau est vide (resp sans user !!) on affiche une alerte !
		echo "<tr align=\"center\"><td class=\"histo\" colspan=\"".$nb_colonnes."\">".$_SESSION['lang']['resp_etat_aucun_user']."</td></tr>\n" ;
	else
	{
		foreach($tab_all_users as $current_login => $tab_current_user)
		{		
			//tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
			$tab_conges=$tab_current_user['conges']; 
	
			$text_affich_user="<a href=\"resp_traite_user.php?session=$session&user_login=$current_login\">".$_SESSION['lang']['resp_etat_users_afficher']."</a>" ;
			$text_edit_papier="<a href=\"../edition/edit_user.php?session=$session&user_login=$current_login\" target=\"_blank\">".$_SESSION['lang']['resp_etat_users_imprim']."</a>";
			echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">".$tab_current_user['nom']."</td><td class=\"histo\">".$tab_current_user['prenom']."</td><td class=\"histo\">".$tab_current_user['quotite']."%</td>";
			foreach($tab_type_cong as $id_conges => $libelle)
			{
				echo "<td class=\"histo\">".$tab_conges[$libelle]['nb_an']."</td>\n";
				echo "<td class=\"histo\">".$tab_conges[$libelle]['solde']."</td>";
			}
			if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
			{
				foreach($tab_type_conges_exceptionnels as $id_type_cong => $libelle) 
				{
					echo "<td class=\"histo\">".$tab_conges[$libelle]['solde']."</td>\n";
				}
			}
			echo "<td class=\"histo\">$text_affich_user</td>\n";
			if($_SESSION['config']['editions_papier']==TRUE)
				echo "<td class=\"histo\">$text_edit_papier</td>";
			echo "</tr>\n";
		}
	}

	/***********************************/
	// AFFICHAGE DE USERS DONT LE RESP EST GRAND RESP

	if($_SESSION['config']['double_validation_conges']==TRUE) 
	{
		// Récup dans un tableau de tableau des informations de tous les users dont $_SESSION['userlogin'] est GRAND responsable
		$tab_all_users_2=recup_infos_all_users_du_grand_resp($_SESSION['userlogin'], $mysql_link, $DEBUG);
		if($DEBUG==TRUE) {echo "tab_all_users_2 :<br>\n";  print_r($tab_all_users_2); echo "<br>\n"; }
		
		$compteur=0;  // compteur de ligne a afficher en dessous (dés que passe à 1 : on affiche une ligne de titre)

		foreach($tab_all_users_2 as $current_login_2 => $tab_current_user_2)
		{
//			if(in_array($current_login_2, $tab_all_users_2)==FALSE) // si le user n'est pas déjà dans le tableau précédent (deja affiché)
			if(array_key_exists($current_login_2, $tab_all_users)==FALSE) // si le user n'est pas déjà dans le tableau précédent (deja affiché)
			{
				$compteur++;
				if($compteur==1)  // alors on affiche une ligne de titre
				{
					$nb_colspan=9;
					if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
						$nb_colspan=10;
					
					echo "<tr align=\"center\"><td class=\"histo\" colspan=\"$nb_colspan\"><i>".$_SESSION['lang']['resp_etat_users_titre_double_valid']."</i></td></tr>\n";
				}
					
				//tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
				$tab_conges_2=$tab_current_user_2['conges']; 
		
				$text_affich_user="<a href=\"resp_traite_user.php?session=$session&user_login=$current_login_2\">".$_SESSION['lang']['resp_etat_users_afficher']."</a>" ;
				$text_edit_papier="<a href=\"../edition/edit_user.php?session=$session&user_login=$current_login_2\" target=\"_blank\">".$_SESSION['lang']['resp_etat_users_imprim']."</a>";
				echo "<tr align=\"center\">\n";
				echo "<td class=\"histo\">".$tab_current_user_2['nom']."</td><td class=\"histo\">".$tab_current_user_2['prenom']."</td><td class=\"histo\">".$tab_current_user_2['quotite']."%</td>";
				foreach($tab_type_cong as $id_conges => $libelle)
				{
//					echo "<td class=\"histo\">".$tab_conges_2[$id_conges]['nb_an']."</td><td class=\"histo\">".$tab_conges_2[$id_conges]['solde']."</td>";
					echo "<td class=\"histo\">".$tab_conges_2[$libelle]['nb_an']."</td><td class=\"histo\">".$tab_conges_2[$libelle]['solde']."</td>";
				}
				if ($_SESSION['config']['gestion_conges_exceptionnels']==TRUE) 
				{
					foreach($tab_type_conges_exceptionnels as $id_type_cong => $libelle) 
					{
						echo "<td class=\"histo\">".$tab_conges_2[$libelle]['solde']."</td>\n";
					}
				}
				echo "<td class=\"histo\">$text_affich_user</td>\n";
				if($_SESSION['config']['editions_papier']==TRUE)
					echo "<td class=\"histo\">$text_edit_papier</td>";
				echo "</tr>\n";
			}
		}
			
	}
	
	echo "</TABLE><br><br>\n\n";

	mysql_close($mysql_link);
	
	echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";

	echo "</CENTER>\n";
	
	// affichage URL de deconnexion:
	if($_SESSION['config']['auth']==TRUE) 
	{
		echo "<table width=\"100%\"><tr><td align=\"right\">";
		bouton_deconnexion();
		echo "</td></tr></table>";
	}


echo "</body>\n";
echo "</html>\n";


?>
