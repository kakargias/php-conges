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

if($_SESSION['config']['where_to_find_user_email']=="ldap"){ include CONFIG_PATH .'config_ldap.php';}


	// on initialise le tableau global des jours fériés s'il ne l'est pas déjà :
	init_tab_jours_feries($DEBUG);

	echo "<h3>". _('user_etat_demandes') ." :</h3>\n" ;

	$tri_date = getpost_variable("tri_date", "ascendant");

	//affiche le tableau des demandes en cours
	affichage_demandes_en_cours($tri_date, $onglet, $DEBUG);

		
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/


//affiche le tableau des demandes en cours
function affichage_demandes_en_cours($tri_date, $onglet,  $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	


	// Récupération des informations
	// on ne recup QUE les periodes de type "conges"(cf table conges_type_absence) ET QUE les demandes
	$sql3 = 'SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_motif_refus, p_date_demande, p_date_traitement, p_num, ta_libelle
			FROM conges_periode as a, conges_type_absence as b
			WHERE a.p_login = \''.SQL::quote($_SESSION['userlogin']).'\'
			AND (a.p_type=b.ta_id)
			AND ( (b.ta_type=\'conges\') OR (b.ta_type=\'conges_exceptionnels\') )
			AND ((p_etat=\'demande\') OR (p_etat=\'valid\')) ';
	if($tri_date=='descendant')
		$sql3=$sql3.' ORDER BY p_date_deb DESC ;';
	else
		$sql3=$sql3.' ORDER BY p_date_deb ASC ;';
	$ReqLog3 = SQL::query($sql3) ;

	$count3=$ReqLog3->num_rows;
	if($count3==0) {
		echo "<b>". _('user_demandes_aucune_demande') ."</b><br>\n";
	}
	else {
		// AFFICHAGE TABLEAU
		echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n" ;
		echo "<tr>\n";
		echo "<td class=\"titre\">";
		echo " <a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=descendant\"><img src=\"". TEMPLATE_PATH ."img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>";
		echo  _('divers_debut_maj_1')  ;
		echo " <a href=\"$PHP_SELF?session=$session&onglet=$onglet&tri_date=ascendant\"><img src=\"". TEMPLATE_PATH ."img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>";
		echo "</td>\n";
		echo "<td class=\"titre\">". _('divers_fin_maj_1') ."</td>" ;
		echo "<td class=\"titre\">". _('divers_type_maj_1') ."</td>" ;
		echo "<td class=\"titre\">". _('divers_nb_jours_pris_maj_1') ."</td>" ;
		echo "<td class=\"titre\">". _('divers_comment_maj_1') ."</td>" ;
		echo "<td></td><td></td>" ;
		if( $_SESSION['config']['affiche_date_traitement'] ) {
			echo "<td class=\"titre\">". _('divers_date_traitement') ."</td>\n" ;
		}
		echo "</tr>\n" ;

		while ($resultat3 = $ReqLog3->fetch_array()) {
		
			$sql_p_date_deb = eng_date_to_fr($resultat3["p_date_deb"], $DEBUG);
			$sql_p_demi_jour_deb = $resultat3["p_demi_jour_deb"];
			if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
			$sql_p_date_fin = eng_date_to_fr($resultat3["p_date_fin"], $DEBUG);
			$sql_p_demi_jour_fin = $resultat3["p_demi_jour_fin"];
			if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
			$sql_p_nb_jours = $resultat3["p_nb_jours"];
			$sql_p_commentaire = $resultat3["p_commentaire"];
			//$sql_p_type = $resultat3["p_type"];
			$sql_p_type = $resultat3["ta_libelle"];
			$sql_p_etat = $resultat3["p_etat"];
			$sql_p_date_demande = $resultat3["p_date_demande"];
			$sql_p_date_traitement = $resultat3["p_date_traitement"];
			$sql_p_num = $resultat3["p_num"];

			// si on peut modifier une demande :on defini le lien à afficher
			if($_SESSION['config']['interdit_modif_demande']==FALSE) {
				//on ne peut pas modifier une demande qui a déja été validé une fois (si on utilise la double validation)
				if($sql_p_etat=="valid")
					$user_modif_demande="&nbsp;";
				else
					$user_modif_demande="<a href=\"user_index.php?session=$session&p_num=$sql_p_num&onglet=modif_demande\">". _('form_modif') ."</a>" ;
			}
			$user_suppr_demande="<a href=\"user_index.php?session=$session&p_num=$sql_p_num&onglet=suppr_demande\">". _('form_supprim') ."</a>" ;
			echo "<tr>\n" ;
			echo '<td class="histo">'.($sql_p_date_deb).' _ '.($demi_j_deb).'</td><td class="histo">'.($sql_p_date_fin).' _ '.($demi_j_fin).'</td>' ;
			echo '<td class="histo">'.schars($sql_p_type).'</td>' ;
			echo "<td class=\"histo\">".affiche_decimal($sql_p_nb_jours, $DEBUG)."</td>" ;
			echo '<td class="histo">'.schars($sql_p_commentaire).'</td>' ;
			if($_SESSION['config']['interdit_modif_demande']==FALSE) {
				echo '<td class="histo">'.($user_modif_demande).'</td>' ;
			}
			echo '<td class="histo">'.($user_suppr_demande).'</td>'."\n" ;
			
			if( $_SESSION['config']['affiche_date_traitement'] ) {
				if($sql_p_date_demande == NULL)
					echo "<td class=\"histo-left\">". _('divers_demande') ." : $sql_p_date_demande<br>". _('divers_traitement') ." : $sql_p_date_traitement</td>\n" ;
				else
					echo "<td class=\"histo-left\">". _('divers_demande') ." : $sql_p_date_demande<br>". _('divers_traitement') ." : pas traité</td>\n" ;
			}
				
			echo "</tr>\n" ;
		}
		echo "</table>\n" ;
	}
	echo "<br><br>\n\n" ;
}

