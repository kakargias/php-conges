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
	
/*************************************/
/***   FONCTIONS   ***/
/*************************************/

function affichage($user_login,  $year_affichage, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $tri_date, $DEBUG)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id();

	// on initialise le tableau global des jours fériés s'il ne l'est pas déjà :
	if(!isset($_SESSION["tab_j_feries"]))
	{
		init_tab_jours_feries();
		//print_r($GLOBALS["tab_j_feries"]);   // verif DEBUG
	}
	
	/********************/
	/* Récupération des informations sur le user : */
	/********************/
	$list_group_dbl_valid_du_resp = get_list_groupes_double_valid_du_resp($_SESSION['userlogin'], $DEBUG);
	$tab_user=array();
	$tab_user = recup_infos_du_user($user_login, $list_group_dbl_valid_du_resp, $DEBUG);
	if($DEBUG==TRUE) { echo"tab_user =<br>\n"; print_r($tab_user); echo "<br>\n"; }
	
	$list_all_users_du_hr=get_list_all_users_du_hr($_SESSION['userlogin'], $DEBUG);
	if($DEBUG==TRUE) { echo"list_all_users_du_hr = $list_all_users_du_hr<br>\n"; }

	// recup des grd resp du user
	$tab_grd_resp=array();
	if($_SESSION['config']['double_validation_conges']==TRUE) 
	{
		get_tab_grd_resp_du_user($user_login, $tab_grd_resp, $DEBUG);
		if($DEBUG==TRUE) { echo"tab_grd_resp =<br>\n"; print_r($tab_grd_resp); echo "<br>\n"; }
	}	
	
	/********************/
	/* Titre */
	/********************/
	echo "<H3>".$_SESSION['lang']['resp_traite_user_titre']."</H3><H2>".$tab_user['nom']." ".$tab_user['prenom'].".</H2>\n\n";

	
	/********************/
	/* Bilan des Conges */
	/********************/
	// AFFICHAGE TABLEAU
	// affichage du tableau récapitulatif des solde de congés d'un user
	affiche_tableau_bilan_conges_user($user_login);
	echo "<br><br>\n";

	/*************************/
	/* SAISIE NOUVEAU CONGES */
	/*************************/
	// dans le cas ou les users ne peuvent pas saisir de demande, le responsable saisi les congès :
	if(($_SESSION['config']['user_saisie_demande']==FALSE)||($_SESSION['config']['resp_saisie_mission']==TRUE)) 
	{
	
		// si les mois et année ne sont pas renseignés, on prend ceux du jour
		if($year_calendrier_saisie_debut==0)
			$year_calendrier_saisie_debut=date("Y");
		if($mois_calendrier_saisie_debut==0)
			$mois_calendrier_saisie_debut=date("m");
		if($year_calendrier_saisie_fin==0)
			$year_calendrier_saisie_fin=date("Y");
		if($mois_calendrier_saisie_fin==0)
			$mois_calendrier_saisie_fin=date("m");	
		if($DEBUG==TRUE) { echo "$mois_calendrier_saisie_debut  $year_calendrier_saisie_debut  -  $mois_calendrier_saisie_fin  $year_calendrier_saisie_fin<br>\n"; }
	
		echo "<H3>".$_SESSION['lang']['resp_traite_user_new_conges']."</H3>\n\n";
		
		//affiche le formulaire de saisie d'une nouvelle demande de conges ou d'un  nouveau conges
		$onglet = "hr_traite_user";
		saisie_nouveau_conges($user_login, $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $onglet);

		echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	}

	/*********************/
	/* Etat des Demandes */
	/*********************/
	if($_SESSION['config']['user_saisie_demande']==TRUE) 
	{
		//verif si le user est bien un user du resp (et pas seulement du grad resp)
		if(strstr($list_all_users_du_hr, "'$user_login'")!=FALSE)
		{
			echo "<h3>".$_SESSION['lang']['resp_traite_user_etat_demandes']."</h3>\n";
	
			//affiche l'état des demande du user (avec le formulaire pour le responsable)
			affiche_etat_demande_user_for_resp($user_login, $tab_user, $tab_grd_resp, $DEBUG);
	
			echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
		}
	}
	
	/*********************/
	/* Etat des Demandes en attente de 2ieme validation */
	/*********************/
	if($_SESSION['config']['double_validation_conges']==TRUE) 
	{
		/*******************************/
		/* verif si le resp est grand_responsable pour ce user*/
	
		if(in_array($_SESSION['userlogin'], $tab_grd_resp)==TRUE) // si resp_login est dans le tableau
		{
			echo "<h3>".$_SESSION['lang']['resp_traite_user_etat_demandes_2_valid']."</h3>\n";
	
			//affiche l'état des demande en attente de 2ieme valid du user (avec le formulaire pour le responsable)
			affiche_etat_demande_2_valid_user_for_resp($user_login, $DEBUG);
	
			echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
		}
	}
	
	/*******************/
	/* Etat des Conges */
	/*******************/
	echo "<h3>".$_SESSION['lang']['resp_traite_user_etat_conges']."</h3>\n";
	
	//affiche l'état des conges du user (avec le formulaire pour le responsable)
	affiche_etat_conges_user_for_resp($user_login,  $year_affichage, $tri_date, $DEBUG);
	
	//echo "<hr align=\"center\" size=\"2\" width=\"90%\"> \n";
	
	
	echo "<td valign=\"middle\">\n";
	//bouton_actualiser("", $DEBUG);
	echo "</td></tr></table>\n";
	echo "<center>\n";

}



//affiche l'état des demande du user (avec le formulaire pour le responsable)
function affiche_etat_demande_user_for_resp($user_login, $tab_user, $tab_grd_resp, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;

	// Récupération des informations
	$sql2 = "SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_date_demande, p_date_traitement, p_num " .
			"FROM conges_periode " .
			"WHERE p_login = '$user_login' AND p_etat ='demande' ".
			"ORDER BY p_date_deb";
	$ReqLog2 = requete_mysql($sql2, "affichage", $DEBUG);
		
	$count2=$ReqLog2->num_rows;
	if($count2==0)
	{
		echo "<b>".$_SESSION['lang']['resp_traite_user_aucune_demande']."</b><br><br>\n";		
	}
	else
	{
		// recup dans un tableau des types de conges
		$tab_type_all_abs = recup_tableau_tout_types_abs();

		// AFFICHAGE TABLEAU
		echo " <form action=\"$PHP_SELF?session=$session&onglet=hr_traite_user\" method=\"POST\"> \n";
		//echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
		echo "<table cellpadding=\"2\" class=\"tablo\">\n";
		echo "<tr align=\"center\">\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_debut_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_nb_jours_pris_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_accepter_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['divers_refuser_maj_1']."</td>\n";
		echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_user_motif_refus']."</td>\n";
		if($_SESSION['config']['affiche_date_traitement']==TRUE)
		{
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
		}
		echo "</tr>\n";
		
		$tab_checkbox=array();
		while ($resultat2 = $ReqLog2->fetch_array()) 
		{
			$sql_date_deb = $resultat2["p_date_deb"];
			$sql_date_deb_fr = eng_date_to_fr($resultat2["p_date_deb"]) ;
			$sql_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
			if($sql_demi_jour_deb=="am") 
				$demi_j_deb = $_SESSION['lang']['divers_am_short'];
			else
				$demi_j_deb = $_SESSION['lang']['divers_pm_short'];
			$sql_date_fin = $resultat2["p_date_fin"];
			$sql_date_fin_fr = eng_date_to_fr($resultat2["p_date_fin"]) ;
			$sql_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
			if($sql_demi_jour_fin=="am")
				$demi_j_fin = $_SESSION['lang']['divers_am_short'];
			else
				$demi_j_fin = $_SESSION['lang']['divers_pm_short'];
			$sql_nb_jours=affiche_decimal($resultat2["p_nb_jours"]) ;
			$sql_commentaire=$resultat2["p_commentaire"] ;
			$sql_type=$resultat2["p_type"] ;
			$sql_date_demande = $resultat2["p_date_demande"];
			$sql_date_traitement = $resultat2["p_date_traitement"];
			$sql_num=$resultat2["p_num"] ;
			
			// on construit la chaine qui servira de valeur à passer dans les boutons-radio
			$chaine_bouton_radio = "$user_login--$sql_nb_jours--$sql_type--$sql_date_deb--$sql_demi_jour_deb--$sql_date_fin--$sql_demi_jour_fin";
			
			// si le user fait l'objet d'une double validation on a pas le meme resultat sur le bouton !
			if($tab_user['double_valid'] == "Y")
			{
				/*******************************/
				/* verif si le resp est grand_responsable pour ce user*/
				if(in_array($_SESSION['userlogin'], $tab_grd_resp)==TRUE) // si resp_login est dans le tableau
					$boutonradio1="<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$chaine_bouton_radio--VALID\">";
				else
					$boutonradio1="<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$chaine_bouton_radio--ACCEPTE\">";				
			}
			else
				$boutonradio1="<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$chaine_bouton_radio--ACCEPTE\">";

			$boutonradio2 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$chaine_bouton_radio--REFUSE\">";
			
			$text_refus  = "<input type=\"text\" name=\"tab_text_refus[$sql_num]\" size=\"20\" max=\"100\">";
			
			echo "<tr align=\"center\">\n";
			echo "<td class=\"histo\">$sql_date_deb_fr _ $demi_j_deb</td>\n";
			echo "<td class=\"histo\">$sql_date_fin_fr _ $demi_j_fin</td>\n";
			echo "<td class=\"histo\">$sql_nb_jours</td>\n";
			echo "<td class=\"histo\">$sql_commentaire</td>\n";
			echo "<td class=\"histo\">".$tab_type_all_abs[$sql_type]['libelle']."</td>\n";			
			echo "<td class=\"histo\">$boutonradio1</td>\n";
			echo "<td class=\"histo\">$boutonradio2</td>\n";
			echo "<td class=\"histo\">$text_refus</td>\n";
			if($_SESSION['config']['affiche_date_traitement']==TRUE)
			{
				if($sql_date_traitement==NULL)
					echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_date_demande<br>".$_SESSION['lang']['divers_traitement']." : pas traité</td>\n" ;
				else
					echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_date_traitement</td>\n" ;
			}
			
			echo "</tr>\n";
		}
		echo "</table>\n\n";

		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"".$_SESSION['lang']['form_cancel']."\">\n";
		echo " </form> \n";
	}
}



//affiche l'état des demande en attente de 2ieme validation du user (avec le formulaire pour le responsable)
function affiche_etat_demande_2_valid_user_for_resp($user_login, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;

		// Récupération des informations
		$sql2 = "SELECT p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_date_demande, p_date_traitement, p_num " .
				"FROM conges_periode " .
				"WHERE p_login = '$user_login' AND p_etat ='valid' ORDER BY p_date_deb";
		$ReqLog2 = requete_mysql($sql2, "affichage", $DEBUG);
			
		$count2=$ReqLog2->num_rows;
		if($count2==0)
		{
			echo "<b>".$_SESSION['lang']['resp_traite_user_aucune_demande']."</b><br><br>\n";		
		}
		else
		{
			// recup dans un tableau des types de conges
			$tab_type_all_abs = recup_tableau_tout_types_abs();
	
			// AFFICHAGE TABLEAU
			echo " <form action=\"$PHP_SELF?session=$session&onglet=hr_traite_user\" method=\"POST\"> \n";
			//echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
			echo "<table cellpadding=\"2\" class=\"tablo\">\n";
			echo "<tr align=\"center\">\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_debut_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_nb_jours_pris_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_accepter_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_refuser_maj_1']."</td>\n";
			echo "<td class=\"titre\">".$_SESSION['lang']['resp_traite_user_motif_refus']."</td>\n";
			if($_SESSION['config']['affiche_date_traitement']==TRUE)
			{
				echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
			}
			echo "</tr>\n";
			
			$tab_checkbox=array();
			while ($resultat2 = $ReqLog2->fetch_array() ) 
			{
				$sql_date_deb = $resultat2["p_date_deb"];
				$sql_date_deb_fr = eng_date_to_fr($resultat2["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat2["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am") 
					$demi_j_deb = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_deb = $_SESSION['lang']['divers_pm_short'];
				$sql_date_fin = $resultat2["p_date_fin"];
				$sql_date_fin_fr = eng_date_to_fr($resultat2["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat2["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am")
					$demi_j_fin = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_fin = $_SESSION['lang']['divers_pm_short'];
				$sql_nb_jours=affiche_decimal($resultat2["p_nb_jours"]) ;
				$sql_commentaire=$resultat2["p_commentaire"] ;
				$sql_type=$resultat2["p_type"] ;
				$sql_date_demande = $resultat2["p_date_demande"];
				$sql_date_traitement = $resultat2["p_date_traitement"];
				$sql_num=$resultat2["p_num"] ;
				
				// on construit la chaine qui servira de valeur à passer dans les boutons-radio
				$chaine_bouton_radio = "$user_login--$sql_nb_jours--$sql_type--$sql_date_deb--$sql_demi_jour_deb--$sql_date_fin--$sql_demi_jour_fin";
				
				
				$casecocher1 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$chaine_bouton_radio--ACCEPTE\">";
				$casecocher2 = "<input type=\"radio\" name=\"tab_radio_traite_demande[$sql_num]\" value=\"$chaine_bouton_radio--REFUSE\">";
				$text_refus  = "<input type=\"text\" name=\"tab_text_refus[$sql_num]\" size=\"20\" max=\"100\">";
				
				echo "<tr align=\"center\">\n";
				echo "<td class=\"histo\">$sql_date_deb_fr _ $demi_j_deb</td>\n";
				echo "<td class=\"histo\">$sql_date_fin_fr _ $demi_j_fin</td>\n";
				echo "<td class=\"histo\">$sql_nb_jours</td>\n";
				echo "<td class=\"histo\">$sql_commentaire</td>\n";
				echo "<td class=\"histo\">".$tab_type_all_abs[$sql_type]['libelle']."</td>\n";			
				echo "<td class=\"histo\">$casecocher1</td>\n";
				echo "<td class=\"histo\">$casecocher2</td>\n";
				echo "<td class=\"histo\">$text_refus</td>\n";
				if($_SESSION['config']['affiche_date_traitement']==TRUE)
				{
					echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_date_traitement</td>\n" ;
				}
				
				echo "</tr>\n";
			}
			echo "</table>\n\n";
	
			echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
			echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">  &nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"reset\" value=\"".$_SESSION['lang']['form_cancel']."\">\n";
			echo " </form> \n";
		}

}



//affiche l'état des conges du user (avec le formulaire pour le responsable)
function affiche_etat_conges_user_for_resp($user_login, $year_affichage, $tri_date, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;
	
	// affichage de l'année et des boutons de défilement
	$year_affichage_prec = $year_affichage-1 ;
	$year_affichage_suiv = $year_affichage+1 ;
	
	echo "<b>";
	echo "<a href=\"$PHP_SELF?session=$session&onglet=hr_traite_user&user_login=$user_login&year_affichage=$year_affichage_prec\"><<</a>";
	echo "&nbsp&nbsp&nbsp  $year_affichage &nbsp&nbsp&nbsp";
	echo "<a href=\"$PHP_SELF?session=$session&onglet=hr_traite_user&user_login=$user_login&year_affichage=$year_affichage_suiv\">>></a>";
	echo "</b><br><br>\n";


	// Récupération des informations de speriodes de conges/absences
	$sql3 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat, p_motif_refus, p_date_demande, p_date_traitement, p_num FROM conges_periode " .
			"WHERE p_login = '$user_login' " .
			"AND p_etat !='demande' " .
			"AND p_etat !='valid' " .
			"AND (p_date_deb LIKE '$year_affichage%' OR p_date_fin LIKE '$year_affichage%') ";
	if($tri_date=="descendant")
		$sql3=$sql3." ORDER BY p_date_deb DESC ";
	else
		$sql3=$sql3." ORDER BY p_date_deb ASC ";

	$ReqLog3 = requete_mysql($sql3, "affiche_etat_conges_user_for_resp", $DEBUG);

	$count3=$ReqLog3->num_rows;
	if($count3==0)
	{
		echo "<b>".$_SESSION['lang']['resp_traite_user_aucun_conges']."</b><br><br>\n";		
	}
	else
	{
		// recup dans un tableau de tableau les infos des types de conges et absences
		$tab_types_abs = recup_tableau_tout_types_abs($DEBUG) ;
		
		// AFFICHAGE TABLEAU
		echo "<form action=\"$PHP_SELF?session=$session&onglet=hr_traite_user\" method=\"POST\"> \n";
		//echo "<table cellpadding=\"2\" class=\"tablo\" width=\"80%\">\n";
		echo "<table cellpadding=\"2\" class=\"tablo\">\n";
		echo "<tr align=\"center\">\n";
		echo " <td class=\"titre\">\n";
		echo " <a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=descendant\"><img src=\"../img/1downarrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>\n";
		echo " ".$_SESSION['lang']['divers_debut_maj_1']." \n";
		echo " <a href=\"$PHP_SELF?session=$session&user_login=$user_login&tri_date=ascendant\"><img src=\"../img/1uparrow-16x16.png\" width=\"16\" height=\"16\" border=\"0\" title=\"trier\"></a>\n";
		echo " </td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_fin_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_nb_jours_pris_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_comment_maj_1']."<br><i>".$_SESSION['lang']['resp_traite_user_motif_possible']."</i></td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_type_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['divers_etat_maj_1']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['resp_traite_user_annul']."</td>\n";
		echo " <td class=\"titre\">".$_SESSION['lang']['resp_traite_user_motif_annul']."</td>\n";
		if($_SESSION['config']['affiche_date_traitement']==TRUE)
		{
			echo "<td class=\"titre\">".$_SESSION['lang']['divers_date_traitement']."</td>\n" ;
		}
		echo "</tr>\n";
		$tab_checkbox=array();
		while ($resultat3 = $ReqLog3->fetch_array() ) 
		{
				$sql_login=$resultat3["p_login"] ;
				$sql_date_deb=eng_date_to_fr($resultat3["p_date_deb"]) ;
				$sql_demi_jour_deb=$resultat3["p_demi_jour_deb"] ;
				if($sql_demi_jour_deb=="am")
					$demi_j_deb = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_deb = $_SESSION['lang']['divers_pm_short'];
				$sql_date_fin=eng_date_to_fr($resultat3["p_date_fin"]) ;
				$sql_demi_jour_fin=$resultat3["p_demi_jour_fin"] ;
				if($sql_demi_jour_fin=="am")
					$demi_j_fin = $_SESSION['lang']['divers_am_short'];
				else
					$demi_j_fin = $_SESSION['lang']['divers_pm_short'];
				$sql_nb_jours=affiche_decimal($resultat3["p_nb_jours"]) ;
				$sql_commentaire=$resultat3["p_commentaire"] ;
				$sql_type=$resultat3["p_type"] ;
				$sql_etat=$resultat3["p_etat"] ;
				$sql_motif_refus=$resultat3["p_motif_refus"] ;
				$sql_p_date_demande = $resultat3["p_date_demande"];
				$sql_p_date_traitement = $resultat3["p_date_traitement"];
				$sql_num=$resultat3["p_num"] ;
				
				if(($sql_etat=="annul") || ($sql_etat=="refus") || ($sql_etat=="ajout")) 
				{
					$casecocher1="";
					if($sql_etat=="refus")
					{
						if($sql_motif_refus=="")
							$sql_motif_refus = $_SESSION['lang']['divers_inconnu'] ;
						//$text_annul="<i>motif du refus : $sql_motif_refus</i>";
						$text_annul="<i>".$_SESSION['lang']['resp_traite_user_motif']." : $sql_motif_refus</i>";
					}
					elseif($sql_etat=="annul")
					{
						if($sql_motif_refus=="")
							$sql_motif_refus = $_SESSION['lang']['divers_inconnu'] ;
						//$text_annul="<i>motif de l'annulation : $sql_motif_refus</i>";
						$text_annul="<i>".$_SESSION['lang']['resp_traite_user_motif']." : $sql_motif_refus</i>";
					}
					elseif($sql_etat=="ajout")
					{
						$text_annul="&nbsp;";
					}
				}
				else
				{
					$casecocher1=sprintf("<input type=\"checkbox\" name=\"tab_checkbox_annule[$sql_num]\" value=\"$sql_login--$sql_nb_jours--$sql_type--ANNULE\">");
					$text_annul="<input type=\"text\" name=\"tab_text_annul[$sql_num]\" size=\"20\" max=\"100\">";
				}

				echo "<tr align=\"center\">\n";
					echo "<td class=\"histo\">$sql_date_deb _ $demi_j_deb</td>\n";
					echo "<td class=\"histo\">$sql_date_fin _ $demi_j_fin</td>\n";
					echo "<td class=\"histo\">$sql_nb_jours</td>\n";
					echo "<td class=\"histo\">$sql_commentaire</td>\n";
					echo "<td class=\"histo\">".$tab_types_abs[$sql_type]['libelle']."</td>\n";
					echo "<td class=\"histo\">";
					if($sql_etat=="refus")
						echo $_SESSION['lang']['divers_refuse'];
					elseif($sql_etat=="annul")
						echo $_SESSION['lang']['divers_annule'];
					else
						echo "$sql_etat";
					echo "</td>\n";
					echo "<td class=\"histo\">$casecocher1</td>\n";
					echo "<td class=\"histo\">$text_annul</td>\n";
					
					if($_SESSION['config']['affiche_date_traitement']==TRUE)
					{
						if(empty($sql_p_date_demande))
						 echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_traitement']." : $sql_p_date_traitement</td>\n" ;
						else 
							echo "<td class=\"histo-left\">".$_SESSION['lang']['divers_demande']." : $sql_p_date_demande<br>".$_SESSION['lang']['divers_traitement']." : $sql_p_date_traitement</td>\n" ;	
					}
					echo "</tr>\n";
			}
		echo "</table>\n\n";

		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<br><input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n";
		echo " </form> \n";
	}
}



function annule_conges($user_login, $tab_checkbox_annule, $tab_text_annul, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id() ;
	
	// recup dans un tableau de tableau les infos des types de conges et absences
	$tab_tout_type_abs = recup_tableau_tout_types_abs($DEBUG);
	
	while($elem_tableau = each($tab_checkbox_annule))
	{
		$champs = explode("--", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$user_nb_jours_pris_float=(float) $user_nb_jours_pris ;
		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		$user_type_abs_id=$champs[2];
		
		$motif_annul=addslashes($tab_text_annul[$numero_int]);
		
		if($DEBUG==TRUE) { echo "<br><br>conges numero :$numero ---> login : $user_login --- nb de jours : $user_nb_jours_pris_float --- type : $user_type_abs_id ---> ANNULER <br>"; }

		/* UPDATE table "conges_periode" */
		$sql1 = "UPDATE conges_periode SET p_etat=\"annul\", p_motif_refus='$motif_annul', p_date_traitement=NOW() WHERE p_num=$numero_int" ;
		$ReqLog1 = requete_mysql($sql1, "annule_conges", $DEBUG);

		// Log de l'action
		log_action($numero_int,"annul", $user_login, "annulation conges $numero ($user_login) ($user_nb_jours_pris jours)", $DEBUG);
			
		/* UPDATE table "conges_solde_user" (jours restants) */
		// on re-crédite les jours seulement pour des conges pris (pas pour les absences)
		// donc seulement si le type de l'absence qu'on annule est un "conges"
		if($tab_tout_type_abs[$user_type_abs_id]['type']=="conges")
		{
			$sql2 = "UPDATE conges_solde_user SET su_solde = su_solde+$user_nb_jours_pris_float WHERE su_login='$user_login' AND su_abs_id=$user_type_abs_id " ;
			//echo($sql2."<br>");
			$ReqLog2 = requete_mysql($sql2, "annule_conges", $DEBUG);
		}
		
		//envoi d'un mail d'alerte au user (si demandé dans config de php_conges)
		if($_SESSION['config']['mail_annul_conges_alerte_user']==TRUE)
			alerte_mail($_SESSION['userlogin'], $user_login, $numero_int, "annul_conges", $DEBUG);
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"hidden\" name=\"onglet\" value=\"hr_traite_user\">\n";
		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";
	}

}



function traite_demandes($user_login, $tab_radio_traite_demande, $tab_text_refus, $DEBUG=FALSE) 
{
	$PHP_SELF=$_SERVER['PHP_SELF']; ;
	$session=session_id();

	// recup dans un tableau de tableau les infos des types de conges et absences
	$tab_tout_type_abs = recup_tableau_tout_types_abs($DEBUG);
	
	while($elem_tableau = each($tab_radio_traite_demande))
	{
		$champs = explode("--", $elem_tableau['value']);
		$user_login=$champs[0];
		$user_nb_jours_pris=$champs[1];
		$user_nb_jours_pris_float=(float) $user_nb_jours_pris ;
		$value_type_abs_id=$champs[2];
		$date_deb=$champs[3];
		$demi_jour_deb=$champs[4];
		$date_fin=$champs[5];
		$demi_jour_fin=$champs[6];
		$reponse=$champs[7];
		$value_traite=$champs[3];

		$numero=$elem_tableau['key'];
		$numero_int=(int) $numero;
		if($DEBUG==TRUE) { echo "<br><br>conges numero :$numero --- User_login : $user_login --- nb de jours : $user_nb_jours_pris --->$value_traite<br>" ; }

		if($reponse == "ACCEPTE") // acceptation definitive d'un conges
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"ok\", p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			$ReqLog1 = requete_mysql($sql1, "traite_demandes", $DEBUG);

			// Log de l'action
			log_action($numero_int,"ok", $user_login, "traite demande $numero ($user_login) ($user_nb_jours_pris jours) : $value_traite", $DEBUG);
			
			/* UPDATE table "conges_solde_user" (jours restants) */
			// on retranche les jours seulement pour des conges pris (pas pour les absences)
			// donc seulement si le type de l'absence qu'on annule est un "conges"
			if($DEBUG==TRUE) { echo "type_abs = ".$tab_tout_type_abs[$value_type_abs_id]['type']."<br>\n" ; }
			if(($tab_tout_type_abs[$value_type_abs_id]['type']=="conges")||($tab_tout_type_abs[$value_type_abs_id]['type']=="conges_exceptionnels"))
			{
//				soustrait_solde_user($user_login, $user_nb_jours_pris_float, $value_type_abs_id, $DEBUG);
				soustrait_solde_et_reliquat_user($user_login, $user_nb_jours_pris_float, $value_type_abs_id, $date_deb, $demi_jour_deb, $date_fin, $demi_jour_fin, $DEBUG);
			}
			
			//envoi d'un mail d'alerte au user (si demandé dans config de php_conges)
			if($_SESSION['config']['mail_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $numero_int, "accept_conges", $DEBUG);
		}
		elseif($reponse == "VALID") // première validation dans le cas d'une double validation  
		{
			/* UPDATE table "conges_periode" */
			$sql1 = "UPDATE conges_periode SET p_etat=\"valid\", p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			$ReqLog1 = requete_mysql($sql1, "traite_demandes", $DEBUG);

			// Log de l'action
			log_action($numero_int,"valid", $user_login, "traite demande $numero ($user_login) ($user_nb_jours_pris jours) : $value_traite", $DEBUG);
			
			//envoi d'un mail d'alerte au user (si demandé dans config de php_conges)
			if($_SESSION['config']['mail_valid_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $numero_int, "valid_conges", $DEBUG);
		}
		elseif($reponse == "REFUSE") // refus d'un conges
		{
			// recup di motif de refus
			$motif_refus=addslashes($tab_text_refus[$numero_int]);
			//$sql3 = "UPDATE conges_periode SET p_etat=\"refus\" WHERE p_num=$numero_int" ;
			$sql3 = "UPDATE conges_periode SET p_etat=\"refus\", p_motif_refus='$motif_refus', p_date_traitement=NOW() WHERE p_num=$numero_int" ;
			$ReqLog3 = requete_mysql($sql3, "traite_demandes", $DEBUG);
		
			// Log de l'action
			log_action($numero_int,"refus", $user_login, "traite demande $numero ($user_login) ($user_nb_jours_pris jours) : $value_traite", $DEBUG);
			
			//envoi d'un mail d'alerte au user (si demandé dans config de php_conges)
			if($_SESSION['config']['mail_refus_conges_alerte_user']==TRUE)
				alerte_mail($_SESSION['userlogin'], $user_login, $numero_int, "refus_conges", $DEBUG);
		}
	}

	if($DEBUG==TRUE)
	{
		echo "<form action=\"$PHP_SELF\" method=\"POST\">\n" ;
		echo "<input type=\"hidden\" name=\"session\" value=\"$session\">\n";
		echo "<input type=\"hidden\" name=\"onglet\" value=\"hr_traite_user\">\n";
		echo "<input type=\"hidden\" name=\"user_login\" value=\"$user_login\">\n";
		echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_ok']."\">\n";
		echo "</form>\n" ;
	}
	else
	{
		echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		/* APPEL D'UNE AUTRE PAGE au bout d'une tempo de 2secondes */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=$PHP_SELF?session=$session&user_login=$user_login\">";
	}

}

function new_conges($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type_id, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	// verif validité des valeurs saisies
	$valid=verif_saisie_new_demande($new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment);
	
	if($valid==TRUE)
	{
		echo "$user_login---$new_debut _ $new_demi_jour_deb---$new_fin _ $new_demi_jour_fin---$new_nb_jours---$new_comment---$new_type_id<br>\n";

		// recup dans un tableau de tableau les infos des types de conges et absences
		$tab_tout_type_abs = recup_tableau_tout_types_abs($DEBUG);
	
		/**********************************/
		/* insert dans conges_periode     */
		/**********************************/
		$new_etat="ok";
		$result=insert_dans_periode($user_login, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin, $new_nb_jours, $new_comment, $new_type_id, $new_etat, 0,$DEBUG);
		
		/************************************************/
		/* UPDATE table "conges_solde_user" (jours restants) */
		// on retranche les jours seulement pour des conges pris (pas pour les absences)
		// donc seulement si le type de l'absence qu'on annule est un "conges"
		if($tab_tout_type_abs[$new_type_id]['type']=="conges")
		{
			$user_nb_jours_pris_float=(float) $new_nb_jours ;
//			soustrait_solde_user($user_login, $user_nb_jours_pris_float, $new_type_id, $DEBUG);
			soustrait_solde_et_reliquat_user($user_login, $user_nb_jours_pris_float, $new_type_id, $new_debut, $new_demi_jour_deb, $new_fin, $new_demi_jour_fin , $DEBUG);
		}
		
		$comment_log = "saisie conges par le responsable pour $user_login ($new_nb_jours jour(s)) type_conges = $new_type_id ( de $new_debut $new_demi_jour_deb a $new_fin $new_demi_jour_fin) ($new_comment)";
		log_action(0, "", $user_login, $comment_log, $DEBUG);

		if($result==TRUE)
			echo $_SESSION['lang']['form_modif_ok']."<br><br> \n";
		else
			echo $_SESSION['lang']['form_modif_not_ok']."<br><br> \n";
	}
	else
	{
			echo $_SESSION['lang']['resp_traite_user_valeurs_not_ok']."<br><br> \n";
	}

	/* APPEL D'UNE AUTRE PAGE */
	echo "<form action=\"$PHP_SELF?session=$session&onglet=hr_traite_user&user_login=$user_login\" method=\"POST\"> \n";
	echo "<input type=\"submit\" value=\"".$_SESSION['lang']['form_retour']."\">\n";
	echo "</form> \n";
	
}


?>

