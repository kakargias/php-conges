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


	echo "<H2> ". _('admin_onglet_gestion_user') ." :</H2>\n\n";
	
	/*********************/
	/* Etat Utilisateurs */
	/*********************/

	// recup du tableau des types de conges (seulement les conges)
	$tab_type_conges=recup_tableau_types_conges($DEBUG);

	// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
	if ( $_SESSION['config']['gestion_conges_exceptionnels'] )
	  $tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels($DEBUG);

	// AFFICHAGE TABLEAU
	// echo "<h3><font color=\"red\">". _('admin_users_titre') ." :</font></h3>\n";

	echo "<table cellpadding=\"2\" class=\"tablo\" >\n";
	echo "<thead>\n";
	echo "<tr>\n";
	echo "<th>". _('divers_nom_maj_1') ."</th>\n";
	echo "<th>". _('divers_prenom_maj_1') ."</th>\n";
	echo "<th>". _('divers_login_maj_1') ."</th>\n";
	echo "<th>". _('divers_quotite_maj_1') ."</th>\n";
	foreach($tab_type_conges as $id_type_cong => $libelle)
	{
		echo "<th>$libelle / ". _('divers_an') ."</th>\n";
		echo "<th>". _('divers_solde') ." $libelle</th>\n";
	}

	if ($_SESSION['config']['gestion_conges_exceptionnels']) {
	  foreach($tab_type_conges_exceptionnels as $id_type_cong => $libelle)
	  {
	    echo "<th>". _('divers_solde') ." $libelle</th>\n";
	  }
	}
	echo "<th>". _('admin_users_is_resp') ."</th>\n";
	echo "<th>". _('admin_users_resp_login') ."</th>\n";
	echo "<th>". _('admin_users_is_admin') ."</th>\n";
	echo "<th>". _('admin_users_is_hr') ."</th>\n";
	echo "<th>". _('admin_users_see_all') ."</th>\n";
	if($_SESSION['config']['where_to_find_user_email']=="dbconges")
		echo "<th>". _('admin_users_mail') ."</th>\n";
	echo "<th></th>\n";
	echo "<th></th>\n";
	if($_SESSION['config']['admin_change_passwd'])
		echo "<th></th>\n";
	echo "</tr>\n";
	echo "</thead>\n";
	echo "<tbody>\n";

	// Récuperation des informations des users:
	$tab_info_users=array();
	// si l'admin peut voir tous les users  OU si on est en mode "responsble virtuel" OU si l'admin n'est pas responsable
	if( $_SESSION['config']['admin_see_all'] || $_SESSION['config']['responsable_virtuel'] || !is_resp($_SESSION['userlogin']) )
		$tab_info_users = recup_infos_all_users($DEBUG);
	else
		$tab_info_users = recup_infos_all_users_du_resp($_SESSION['userlogin'], $DEBUG);


	$i = true;
	foreach($tab_info_users as $current_login => $tab_current_infos)
	{

		
		$admin_modif_user="<a href=\"admin_index.php?onglet=modif_user&session=$session&u_login=$current_login\">"."<img src=\"". TEMPLATE_PATH . "img/edition-22x22.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('form_modif') ."\" alt=\"". _('form_modif') ."\"></a>" ;
		$admin_suppr_user="<a href=\"admin_index.php?onglet=suppr_user&session=$session&u_login=$current_login\">"."<img src=\"". TEMPLATE_PATH . "img/stop.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('form_supprim') ."\" alt=\"". _('form_supprim') ."\"></a>" ;
		$admin_chg_pwd_user="<a href=\"admin_index.php?onglet=chg_pwd_user&session=$session&u_login=$current_login\">"."<img src=\"". TEMPLATE_PATH . "img/password.png\" width=\"17\" height=\"17\" border=\"0\" title=\"". _('form_password') ."\" alt=\"". _('form_password') ."\"></a>" ;


		echo '<tr class="'.($i?'i':'p').'">';
		echo "<td><b>".$tab_current_infos['nom']."</b></td>\n";
		echo "<td><b>".$tab_current_infos['prenom']."</b></td>\n";
		echo "<td>$current_login</td>\n";
		echo "<td>".$tab_current_infos['quotite']."%</td>\n";

		//tableau de tableaux les nb et soldes de conges d'un user (indicé par id de conges)
		$tab_conges=$tab_current_infos['conges'];
		
		foreach($tab_type_conges as $id_conges => $libelle)
		{
			if (isset($tab_conges[$libelle]))
			{
				echo "<td>".$tab_conges[$libelle]['nb_an']."</td>\n";
				echo "<td>".$tab_conges[$libelle]['solde']."</td>\n";
			}
			else
			{
				echo "<td>0</td>\n";
				echo "<td>0</td>\n";
			}
		}
		if ($_SESSION['config']['gestion_conges_exceptionnels'])
		{
			foreach($tab_type_conges_exceptionnels as $id_conges => $libelle)
			{
				if (isset($tab_conges[$libelle]))
					echo "<td>".$tab_conges[$libelle]['solde']."</td>\n";
				else
					echo "<td>0</td>\n";
			}
		}
		echo "<td>".$tab_current_infos['is_resp']."</td>\n";
		echo "<td>".$tab_current_infos['resp_login']."</td>\n";
		echo "<td>".$tab_current_infos['is_admin']."</td>\n";
		echo "<td>".$tab_current_infos['is_hr']."</td>\n";
		echo "<td>".$tab_current_infos['see_all']."</td>\n";
		if($_SESSION['config']['where_to_find_user_email']=="dbconges")
			echo "<td>".$tab_current_infos['email']."</td>\n";
		echo "<td>$admin_modif_user</td>\n";
		echo "<td>$admin_suppr_user</td>\n";
		if(($_SESSION['config']['admin_change_passwd']) && ($_SESSION['config']['how_to_connect_user'] == "dbconges"))
			echo "<td>$admin_chg_pwd_user</td>\n";
		echo "</tr>\n";
		$i = !$i;
	}
	
	echo "</tbody>\n";
	echo"</table>\n\n";
	echo "<br>\n";


