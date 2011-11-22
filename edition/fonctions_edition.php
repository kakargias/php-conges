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


function enregistrement_edition($login, $mysql_link)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];

	$sql1 = "SELECT u_solde_jours, u_solde_rtt FROM conges_users where u_login = '$login' ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : edition.php : ".mysql_error());
	while ($resultat1 = mysql_fetch_array($ReqLog1)) 
	{
		$sql_solde_jours=$resultat1["u_solde_jours"];
		$sql_solde_rtt=$resultat1["u_solde_rtt"];
	}
	$new_edition_id=get_last_edition_id($mysql_link)+1;
	$aujourdhui = date("Y-m-d");
	$num_for_user=get_num_last_edition_user($login, $mysql_link)+1;

	/*************************************************/
	/* Insertion dans le table conges_edition_papier */
	/*************************************************/
	$sql_insert = "INSERT INTO conges_edition_papier
			SET ep_id=$new_edition_id, ep_login='$login', ep_date='$aujourdhui', ep_solde_jours=$sql_solde_jours, ep_solde_rtt=$sql_solde_rtt, ep_num_for_user=$num_for_user ";
	$result_insert = mysql_query($sql_insert, $mysql_link) or die("ERREUR : enregistrement_edition() : ".$sql_insert." --> ".mysql_error());
	
	
	/********************************************************************************************/
	/* Update du num edition dans la table periode pour les Conges et demandes de cette edition */
	/********************************************************************************************/
	$sql_update = "UPDATE conges_periode SET p_edition_id=$new_edition_id 
			WHERE p_login = '$login' 
			AND p_edition_id IS NULL
			AND (p_type='conges' OR  p_type='rtt')
			AND (p_etat='ok' OR  p_etat='annul' OR  p_etat='refus' OR  p_etat='ajout') ";
		$ReqLog_update = mysql_query($sql_update, $mysql_link) or die("ERREUR : enregistrement_edition() : ".$sql_update." --> ".mysql_error());
	
	return $new_edition_id;
}


// renvoi le + grand id de la table edition_papier (l'id de la derniere edition)
function get_last_edition_id($mysql_link)
{
	// verif si table edition pas vide
	$sql1 = "SELECT ep_id FROM conges_edition_papier ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
	if(mysql_num_rows($ReqLog1)==0) 
		return 0;    // c'est qu'il n'y a pas encore d'edition 
	else
	{
		$sql2 = "SELECT MAX(ep_id) FROM conges_edition_papier ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
		return mysql_result($ReqLog2, 0);
	}	
}

// renvoi le + grand num_par_user de la table edition_papier pour un user donné (le num de la derniere edition du user)
function get_num_last_edition_user($login, $mysql_link)
{
	// verif si le user a une edition
	$sql1 = "SELECT ep_num_for_user FROM conges_edition_papier WHERE ep_login='$login' ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
	if(mysql_num_rows($ReqLog1)==0) 
		return 0;    // c'est qu'il n'y a pas encore d'edition pour ce user
	else
	{
		$sql2 = "SELECT MAX(ep_num_for_user) FROM conges_edition_papier WHERE ep_login='$login' ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : get_last_edition_id() : ".mysql_error());
		return mysql_result($ReqLog2, 0);
	}
}


// renvoi le num_par_user de la table edition_papier de l'edition précédente pour un user donné et un edition_id donné.
function get_num_edition_precedente_user($login, $edition_id, $mysql_link)
{
	// verif si le user n'a pas une seule edition
	$sql1 = "SELECT MAX(ep_num_for_user) FROM conges_edition_papier WHERE ep_login='$login' ";
	$ReqLog1 = mysql_query($sql1, $mysql_link) or die("ERREUR : get_num_edition_precedente_user() : ".mysql_error());
	$resultat1 = mysql_fetch_array($ReqLog1) ;
	if($resultat1[0]==1)    // une seule edition pour ce user
		return 0;
	else
	{
		$sql2 = "SELECT MAX(ep_num_for_user) FROM conges_edition_papier WHERE ep_login='$login' AND ep_num_for_user<$edition_id ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : get_num_edition_precedente_user() : ".mysql_error());
		return mysql_result($ReqLog2, 0);
		
	}
}



?>
