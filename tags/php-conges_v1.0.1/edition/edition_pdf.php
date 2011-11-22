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

session_start();
if(isset($_GET['session'])) { $session=$_GET['session']; }
if(isset($_POST['session'])) { $session=$_POST['session']; }

//include("../config.php") ;
include("fonctions_edition.php") ;
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
//include("../INCLUDE.PHP/session.php");
//if($_SESSION['config']['verif_droits']==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}
//if($_SESSION['config']['where_to_find_user_email']=="ldap"){ include("../config_ldap.php");}
	

	/*************************************/
	// recup des parametres reçus :
	// GET
	if(isset($_GET['user_login'])) { $user_login=$_GET['user_login']; }
	if(isset($_GET['edit_id'])) { $edit_id=$_GET['edit_id']; }
	// POST
	if(!isset($user_login))
		if(isset($_POST['user_login'])) { $user_login=$_POST['user_login']; }
	if(!isset($edit_id))
		if(isset($_POST['edit_id'])) { $edit_id=$_POST['edit_id']; }
	/*************************************/

	/************************************/

	
	//connexion mysql
	$mysql_link = connexion_mysql();
	
	if($edit_id==0)   // si c'est une nouvelle édition, on insert dans la base avant d'éditer et on renvoit l'id de l'édition
		$edit_id=enregistrement_edition($user_login, $mysql_link);
	
	edition_pdf($user_login, $edit_id, $mysql_link);

	mysql_close($mysql_link);

	
	
	
/**************************************************************************************/
/********  FONCTIONS      ******/
/**************************************************************************************/

function edition_pdf($login, $edit_id, $mysql_link)
{
	require_once('../INCLUDE.PHP/fpdf/fpdf/fpdf.php');
	define('FPDF_FONTPATH','font/');
	
	class PDF extends FPDF
	{
		function Header()
		{
			/**************************************/
			/* affichage du texte en haut de page */
			/**************************************/
			$this->SetFont('Times','',10);
			$this->Cell(0,3, $_SESSION['config']['texte_haut_edition_papier'],0,1,'C');
			$this->Ln(10);
		}
		
		function Footer()
		{
			/**************************************/
			/* affichage du texte de bas de page */
			/**************************************/
			$this->SetFont('Times','',10);
			//$pdf->Cell(0,6, 'texte_haut_edition_papier',0,1,'C');
			$this->Cell(0,3, $_SESSION['config']['texte_bas_edition_papier'],0,1,'C');
			$this->Ln(10);
		}
	} 
	
	// recup infos du user
	$sql_user = "SELECT u_nom, u_prenom, u_nb_jours_an, u_solde_jours, u_nb_rtt_an, u_solde_rtt, u_quotite FROM conges_users where u_login = '$login' ";
	$ReqLog_user = mysql_query($sql_user, $mysql_link) or die("ERREUR : edition.php : <br>\n".mysql_error());
	while ($resultat_user = mysql_fetch_array($ReqLog_user)) {
		$sql_nom=$resultat_user["u_nom"];
		$sql_prenom=$resultat_user["u_prenom"];
		$sql_nb_jours_an=affiche_decimal($resultat_user["u_nb_jours_an"]);
		$sql_nb_rtt_an=affiche_decimal($resultat_user["u_nb_rtt_an"]);
		$sql_quotite=$resultat_user["u_quotite"];
	}

	// recup infos de l'édition
	$sql_edition= "SELECT ep_date, ep_solde_jours, ep_solde_rtt FROM conges_edition_papier where ep_id = $edit_id ";
	$ReqLog_edition = mysql_query($sql_edition, $mysql_link) or die("ERREUR : edition.php : <br>\n".mysql_error());
	while ($resultat_edition = mysql_fetch_array($ReqLog_edition)) {
		$sql_date=$resultat_edition["ep_date"];
		$sql_solde_jours=affiche_decimal($resultat_edition["ep_solde_jours"]);
		$sql_solde_rtt=affiche_decimal($resultat_edition["ep_solde_rtt"]);
	}
	
	
	/**************************************/
	/* on commence l'affichage ...        */
	/**************************************/
	header('content-type: application/pdf');
	//header('content-Disposition: attachement; filename="downloaded.pdf"');    // pour IE
	
	$pdf=new PDF();
	//$pdf->Open();
	$pdf->AddPage();
		
	$pdf->SetFillColor(200);
	
	/**************************************/
	/* affichage du texte en haut de page */
	/**************************************/
	// fait dans le header de la classe (cf + haut)
	
	/**************************************/
	/* affichage du TITRE                 */
	/**************************************/
	$pdf->SetFont('Times', 'B', 18);				
	$pdf->Cell(0, 5, $sql_nom." ".  $sql_prenom,0,1,'C');
	$pdf->Ln(5);
	$pdf->SetFont('Times', 'B', 13);				
	$tab_date=explode("-", $sql_date);
	$pdf->Cell(0, 5, "bilan au ".$tab_date[2]." / ".$tab_date[1]." / ".$tab_date[0],0,1,'C');
	$pdf->Ln(5);
	
	/****************************/
	/* tableau Bilan des Conges */
	/****************************/
	$pdf->SetFont('Times', 'B', 10);
	// decalage pour centrer ( = (21cm - (marges x 2) - (sommes des cell définies en dessous) )/2  ) (marges=1cm)
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
		$pdf->Cell(25); 
	else
		$pdf->Cell(55); 
		
	$pdf->Cell(20, 5, "quotité", 1, 0, 'C');
	$pdf->Cell(30, 5, "NB CONGES / AN", 1, 0, 'C');
	$pdf->Cell(30, 5, "SOLDE CONGES", 1, 0, 'C');
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
	{
		$pdf->Cell(30, 5, "NB RTT / AN", 1, 0, 'C');
		$pdf->Cell(30, 5, "SOLDE RTT", 1, 1, 'C');
	}

	// decalage pour centrer ( = (21cm - (marges x 2) - (sommes des cell définies en dessous) )/2  ) (marges=1cm=
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
		$pdf->Cell(25); 
	else
		$pdf->Cell(55); 
		
	$pdf->Cell(20, 5, $sql_quotite."%", 1, 0, 'C');
	$pdf->Cell(30, 5, $sql_nb_jours_an, 1, 0, 'C');
	$pdf->Cell(30, 5, $sql_solde_jours, 1, 0, 'C', 1);
	if($_SESSION['config']['rtt_comme_conges']==TRUE)
	{
		$pdf->Cell(30, 5, $sql_nb_rtt_an, 1, 0, 'C');
		$pdf->Cell(30, 5, $sql_solde_rtt, 1, 1, 'C', 1);
	}

	$pdf->Ln(12);


	$pdf->SetFont('Times', 'BU', 11);				
	$pdf->Cell(0, 5, "Historique :",0,1,'C');
	$pdf->Ln(5);
	/*********************************************/
	/* Tableau Historique des Conges et demandes */
	/*********************************************/

		$pdf->SetFont('Times', 'B', 10);				
		
		// Récupération des informations
		$sql2 = "SELECT p_login, p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_nb_jours, p_commentaire, p_type, p_etat ";
		$sql2=$sql2."FROM conges_periode ";
		$sql2=$sql2."WHERE p_login = '$login' ";
		$sql2=$sql2."AND (p_type='conges' OR  p_type='rtt') ";
		$sql2=$sql2."AND (p_etat='ok' OR  p_etat='annul' OR  p_etat='refus' OR  p_etat='ajout') ";
		$sql2=$sql2."AND p_edition_id = $edit_id ";
		$sql2=$sql2."ORDER BY p_date_deb ASC ";
		$ReqLog2 = mysql_query($sql2, $mysql_link) or die("ERREUR : mysql_query : ".$sql2." --> ".mysql_error());

		$count2=mysql_num_rows($ReqLog2);
		if($count2==0)
		{
			$pdf->Cell(0, 5, "Aucun congés dans la base de données ...",0,1,'C');
			$pdf->Ln(5);
		}
		else
		{
			/*************************************/
			/* affichage anciens soldes          */
			/*************************************/
			$pdf->SetFont('Arial', 'B', 10);				
			// decalage pour centrer 
			if($_SESSION['config']['rtt_comme_conges']==TRUE)
				$pdf->Cell(8); 
			else
				$pdf->Cell(18); 
			
			$edition_precedente_id=get_num_edition_precedente_user($login, $edit_id, $mysql_link);
			if($edition_precedente_id!=0)
			{
				$sql_solde_prec = "SELECT ep_solde_jours, ep_solde_rtt FROM conges_edition_papier where ep_id=$edition_precedente_id ";
				$ReqLog_solde_prec = mysql_query($sql_solde_prec, $mysql_link) or die("ERREUR : edition.php : <br>\n".mysql_error());
				while ($resultat_solde_prec = mysql_fetch_array($ReqLog_solde_prec))
				{
					$sql_solde_prec_jours=affiche_decimal($resultat_solde_prec["ep_solde_jours"]);
					$sql_solde_prec_rtt=affiche_decimal($resultat_solde_prec["ep_solde_rtt"]);

					// attention au retour à la ligne apres la derniere cellule
					if($_SESSION['config']['rtt_comme_conges']==TRUE)
					{
						$pdf->SetFont('Arial', '', 10);
						$pdf->Cell(42, 5, "solde congés précédent :",0,0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(10, 5, $sql_solde_prec_jours, 0, 0);
						$pdf->SetFont('Arial', '', 10);
						$pdf->Cell(37, 5, "/  solde rtt précédent :",0,0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(10, 5, $sql_solde_prec_rtt, 0,1);
					}
					else
					{
						$pdf->SetFont('Arial', '', 10);
						$pdf->Cell(42, 5, "solde congés précédent :",0,0);
						$pdf->SetFont('Arial', 'B', 10);
						$pdf->Cell(10, 5, $sql_solde_prec_jours, 0, 1);
					}
				}
			}
			else
			{
				// attention au retour à la ligne apres la derniere cellule
				if($_SESSION['config']['rtt_comme_conges']==TRUE)
				{
					$pdf->Cell(55, 5, "solde congés précédent : inconnu",0,0);
					$pdf->Cell(55, 5, "/  solde rtt précédent : inconnu",0,1);
				}
				else
				{
					$pdf->Cell(55, 5, "solde congés précédent : inconnu",0,1);
				}
				
			}
			$pdf->Ln(5);

			/*************************************/
			/* affichage lignes de l'edition     */
			/*************************************/
			// decalage pour centrer 
			if($_SESSION['config']['rtt_comme_conges']==TRUE)
				$pdf->Cell(8); 
			else
				$pdf->Cell(18); 

			$pdf->SetFont('Times', 'B', 10);				
			if($_SESSION['config']['rtt_comme_conges']==TRUE)
				$pdf->Cell(20, 5, "Type", 1, 0, 'C', 1); 
			$pdf->Cell(15, 5, "Etat", 1, 0, 'C', 1);
			$pdf->Cell(20, 5, "nb Jours", 1, 0, 'C', 1);
			$pdf->Cell(40, 5, "Debut", 1, 0, 'C', 1);
			$pdf->Cell(40, 5, "Fin", 1, 0, 'C', 1);
			$pdf->Cell(40, 5, "Commentaire", 1, 1, 'C', 1);
			
			while ($resultat2 = mysql_fetch_array($ReqLog2)) {
					$sql_p_date_deb = eng_date_to_fr($resultat2["p_date_deb"]);
					$sql_p_demi_jour_deb = $resultat2["p_demi_jour_deb"];
					if($sql_p_demi_jour_deb=="am") $demi_j_deb="mat";  else $demi_j_deb="aprm";
					$sql_p_date_fin = eng_date_to_fr($resultat2["p_date_fin"]);
					$sql_p_demi_jour_fin = $resultat2["p_demi_jour_fin"];
					if($sql_p_demi_jour_fin=="am") $demi_j_fin="mat";  else $demi_j_fin="aprm";
					$sql_p_nb_jours = $resultat2["p_nb_jours"];
					$sql_p_commentaire = $resultat2["p_commentaire"];
					$sql_p_type = $resultat2["p_type"];
					$sql_p_etat = $resultat2["p_etat"];
					
					// decalage pour centrer 
					if($_SESSION['config']['rtt_comme_conges']==TRUE)
						$pdf->Cell(8); 
					else
						$pdf->Cell(18); 
					$pdf->SetFont('Times', '', 10);				

					$pdf->Cell(20, 5, $sql_p_type, 1, 0, 'C'); 

					if($sql_p_etat=="refus")
						$pdf->Cell(15, 5, "refusé", 1, 0, 'C');
					elseif($sql_p_etat=="annul")
						$pdf->Cell(15, 5, "annulé", 1, 0, 'C');
					else
						$pdf->Cell(15, 5, $sql_p_etat, 1, 0, 'C');

					$pdf->SetFont('Times', 'B', 10);				
					if($sql_p_etat=="ok")
						$pdf->Cell(20, 5, "-".$sql_p_nb_jours, 1, 0, 'C');
					elseif($sql_p_etat=="ajout")
						$pdf->Cell(20, 5, "+".$sql_p_nb_jours, 1, 0, 'C');
					else
						$pdf->Cell(20, 5, $sql_p_nb_jours, 1, 0, 'C');

					$pdf->SetFont('Times', '', 10);				
					$pdf->Cell(40, 5, $sql_p_date_deb." _ ".$demi_j_deb, 1, 0, 'C');
					$pdf->Cell(40, 5, $sql_p_date_fin." _ ".$demi_j_fin, 1, 0, 'C');
					$pdf->Cell(40, 5, $sql_p_commentaire, 1, 1, 'C');
			}
			$pdf->Ln(5);
			
			/*************************************/
			/* affichage nouveaux soldes         */
			/*************************************/

			// decalage pour centrer 
			if($_SESSION['config']['rtt_comme_conges']==TRUE)
				$pdf->Cell(8); 
			else
				$pdf->Cell(18); 

			// attention au retour à la ligne apres la derniere cellule
			if($_SESSION['config']['rtt_comme_conges']==TRUE)
			{
				$pdf->SetFont('Arial', '', 10);
				$pdf->Cell(40, 5, "nouveau solde congés :",0,0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->Cell(10, 5, $sql_solde_jours, 0, 0);
				$pdf->SetFont('Arial', '', 10);
				$pdf->Cell(35, 5, "/  nouveau solde rtt :",0,0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->Cell(10, 5, $sql_solde_rtt, 0,1);
			}
			else
			{
				$pdf->SetFont('Arial', '', 10);
				$pdf->Cell(40, 5, "nouveau solde congés :",0,0);
				$pdf->SetFont('Arial', 'B', 10);
				$pdf->Cell(10, 5, $sql_solde_jours, 0, 1);
			}


		}
	
		$pdf->Ln(8);
	
	/*************************************/
	/* affichage des zones de signature  */
	/*************************************/
	$pdf->SetFont('Times', 'B', 10);				
	// decalage pour centrer 
	$pdf->Cell(20); 
	$pdf->Cell(70, 5, "date :",0,0);
	$pdf->Cell(70, 5, "date :",0,1);
	// decalage pour centrer 
	$pdf->Cell(20); 
	$pdf->Cell(70, 5, "signature du titulaire :",0,0);
	$pdf->Cell(70, 5, "signature du responsable :",0,1);
	
	$pdf->SetFont('Times', 'I', 10);				
	// decalage pour centrer 
	$pdf->Cell(20); 
	$pdf->Cell(70, 5, "",0,0);
	$pdf->Cell(70, 5, "(et cachet de l'établissement)",0,1);
	
	$pdf->Ln(30);
	
	/*************************************/
	/* affichage du texte en bas de page */
	/*************************************/
	// fait dans le footer de la classe (cf + haut)
	
	$pdf->Output();
}


?>
