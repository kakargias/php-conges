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

include("fonctions_conges.php") ;
include("INCLUDE.PHP/fonction.php");
include("INCLUDE.PHP/session.php");
$verif_droits_file="INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG=FALSE;
//$DEBUG=TRUE


	/*** initialisation des variables ***/
	$session=session_id();
	$user="";
	$date_debut="";
	$date_fin="";
	/************************************/

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET	/ POST
	$user       = getpost_variable("user") ;
	$date_debut = getpost_variable("date_debut") ;
	$date_fin   = getpost_variable("date_fin") ;
	$opt_debut  = getpost_variable("opt_debut") ;
	$opt_fin    = getpost_variable("opt_fin") ;
	/*************************************/

// ATTENTION ne pas mettre cet appel avant les include car plantage sous windows !!!
?>
<script language="javascript">
function envoi(valeur)
{window.opener.document.forms[0].new_nb_jours.value=valeur}
</Script>
<?php
	
	if( ($user!="") && ($date_debut!="") && ($date_fin!="") && ($opt_debut!="") && ($opt_fin!="") )
		affichage($user, $date_debut, $date_fin, $opt_debut, $opt_fin, $DEBUG);
	else
		/* APPEL D'UNE AUTRE PAGE immediat */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=user_index.php?session=$session&onglet=nouvelle_absence\">";
		//echo " $user, $date_debut, $date_fin, $opt_debut, $opt_fin <br>\n";




/**********  FONCTIONS  ****************************************/

function affichage($user, $date_debut, $date_fin, $opt_debut, $opt_fin, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	$comment="&nbsp;" ;
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : </title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>$user</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	// calcul :
	$nb_jours=compter($user, $date_debut, $date_fin, $opt_debut, $opt_fin, $comment, $DEBUG);
	echo "<td align=\"center\"><h2>".$_SESSION['lang']['calcul_nb_jours_nb_jours']." <b>$nb_jours</b></h2></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\"><i><font color=\"red\">$comment<font/></i></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\"><i>".$_SESSION['lang']['calcul_nb_jours_reportez']." \"".$_SESSION['lang']['saisie_conges_nb_jours']."\" ".$_SESSION['lang']['calcul_nb_jours_form'].".</i></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_close_window']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	if( ($_SESSION['config']['rempli_auto_champ_nb_jours_pris']==TRUE) && ($nb_jours!=0) )
		echo "<script>envoi($nb_jours)</script>";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


// calcule le nb de jours de conges à prendre pour un user entre 2 dates
// retourne le nb de jours  (opt_debut et opt_fin ont les valeurs "am" ou "pm"
function compter($user, $date_debut, $date_fin, $opt_debut, $opt_fin, &$comment, $DEBUG=FALSE)
{	
	// verif si date_debut est bien anterieure à date_fin
	if (strtotime($date_debut) > strtotime($date_fin))
		return 0 ;
	
	
	if( ($date_debut!=0) && ($date_fin!=0) )
	{
		$nb_jours=0;
		
		// 1 : on fabrique un tableau de jours (divisé chacun en 2 demi-jour) de la date_debut à la date_fin
		// 2 : on affecte à 0 ou 1 chaque demi jour, en fonction de s'il est travaillé ou pas
		// 3 : à la fin , on parcours le tableau en comptant le nb de demi-jour à 1, on multiplie ce total par 0.5, ça donne le nb de jours du conges ! 


		// 1 : fabrication et initialisation du tableau de demi-jours
		$tab_periode_calcul=array();
		$nb_jours_entre_date = (((strtotime($date_fin) - strtotime($date_debut))/3600)/24)+1 ;

		if($DEBUG==TRUE) { echo "$nb_jours_entre_date<br>\n"; }
		
		// on va avancer jour par jour jusqu'à la date limite 
		$current_day=$date_debut;
		$date_limite=jour_suivant($date_fin);
		while($current_day!=$date_limite)
		{
			$jour['am']=1;
			$jour['pm']=1;
			$tab_periode_calcul[$current_day]=$jour;
			$current_day=jour_suivant($current_day);
		}
		// attention au premier et dernier jour :
		if($opt_debut=="pm")
			$tab_periode_calcul[$date_debut]['am']=0;
		if($opt_fin=="am")
			$tab_periode_calcul[$date_fin]['pm']=0;
			
		if($DEBUG==TRUE) { echo "tab_periode_calcul :<br>\n"; print_r($tab_periode_calcul); echo "<br>\n"; }
		
		
		
		// 2 : on affecte à 0 ou 1 chaque demi jour, en fonction de s'il est travaillé ou pas

		//connexion mysql
		$mysql_link = connexion_mysql() ;

		// on initialise le tableau global des jours fériés s'il ne l'est pas déjà :
		if(!isset($_SESSION["tab_j_feries"]))
		{
			init_tab_jours_feries($mysql_link);
			//print_r($_SESSION["tab_j_feries"]);   // verif DEBUG
		}

		$current_day=$date_debut;
		$date_limite=jour_suivant($date_fin);

		// on va avancer jour par jour jusqu'à la date limite et voir si chaque jour est travaillé, férié, rtt, etc ...
		while($current_day!=$date_limite)
		{
			// calcul du timestamp du jour courant
			$pieces = explode("-", $current_day);  // date de la forme yyyy-mm-jj
			$y=$pieces[0]; 
			$m=$pieces[1]; 
			$j=$pieces[2]; 
			$timestamp_du_jour=mktime (0,0,0,$m,$j,$y);

			// on regarde si le jour est travaillé ou pas dans la config de l'appli
			$j_name=date("D", $timestamp_du_jour);
			if( (($j_name=="Sat")&&($_SESSION['config']['samedi_travail']==FALSE)) 
				|| (($j_name=="Sun")&&($_SESSION['config']['dimanche_travail']==FALSE)))
			{
				// on ne compte ce jour à 0
				$tab_periode_calcul[$current_day]['am']=0;
				$tab_periode_calcul[$current_day]['pm']=0;
			}
			else
			{
				if(est_chome($timestamp_du_jour)==TRUE) // verif si jour férié
				{
					// on ne compte ce jour à 0
					$tab_periode_calcul[$current_day]['am']=0;
					$tab_periode_calcul[$current_day]['pm']=0;
				}
				else
				{
					// verif des rtt ou temp partiel (dans la table rtt)
					$val_matin="N";
					$val_aprem="N";
					recup_infos_artt_du_jour($user, $timestamp_du_jour, $val_matin, $val_aprem, $mysql_link);

					if($val_matin=="Y")  // rtt le matin
						$tab_periode_calcul[$current_day]['am']=0;

					if($val_aprem=="Y") // rtt l'après midi
						$tab_periode_calcul[$current_day]['pm']=0;
						
					// verif si c'est deja un conges 
					// verif si le jour courant est un congés (p_type=1) accepté (p_etat='ok') ou non
					$user_periode_sql = "SELECT  p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat
									FROM conges_periode 
									WHERE p_login = '$user' AND ( p_etat='ok' OR p_etat='demande' ) AND p_type=1 AND p_date_deb<='$current_day' AND p_date_fin>='$current_day' ";
			
					$user_periode_request = requete_mysql($user_periode_sql, $mysql_link, "compter", $DEBUG);
					
					if(mysql_num_rows($user_periode_request)!=0)  // le jour courant est dans un periode de conges du user
					{
						$comment = $_SESSION['lang']['calcul_nb_jours_commentaire'];
						while($resultat_periode=mysql_fetch_array($user_periode_request))
						{
							$sql_p_date_deb=$resultat_periode["p_date_deb"];
							$sql_p_date_fin=$resultat_periode["p_date_fin"];
							$sql_p_demi_jour_deb=$resultat_periode["p_demi_jour_deb"];
							$sql_p_demi_jour_fin=$resultat_periode["p_demi_jour_fin"];
							$sql_p_etat=$resultat_periode["p_etat"];
							
							// on regarde si on tombe dans une demande .... 
							//si c'est le cas, comme on ne sais pas si elle va être acceptée ou refusée : le calcul est impossible
							if($sql_p_etat=="demande")
							{
								$comment = $_SESSION['lang']['calcul_nb_jours_commentaire_impossible'];
								return 0;
							}
							else // sinon, on poursuit le calcul
							{							
								//array_walk ($tab_periode, 'test_print_array');
								//si on est le premier jour de la periode
								if($current_day==$sql_p_date_deb)
								{
									if($sql_p_demi_jour_deb=="pm")
										$tab_periode_calcul[$current_day]['am']=0;
									else
									{
										$tab_periode_calcul[$current_day]['am']=0;
										$tab_periode_calcul[$current_day]['pm']=0;								
									}
								}
								//si on est le dernier jour
								elseif($current_day==$sql_p_date_fin)
								{
									if($sql_p_demi_jour_fin=="am")
										$tab_periode_calcul[$current_day]['pm']=0;
									else
									{
										$tab_periode_calcul[$current_day]['am']=0;
										$tab_periode_calcul[$current_day]['pm']=0;								
									}
								}
								else
								{
									$tab_periode_calcul[$current_day]['am']=0;
									$tab_periode_calcul[$current_day]['pm']=0;								
								}
							}		
						}
					}

				}
			}
			$current_day=jour_suivant($current_day);
		}

		if($DEBUG==TRUE) { echo "tab_periode_calcul :<br>\n"; print_r($tab_periode_calcul); echo "<br>\n"; }

		mysql_close($mysql_link);
		
		// on va avancer jour par jour jusqu'à la date limite pour compter le nb de demi jour à 1
		$current_day=$date_debut;
		$date_limite=jour_suivant($date_fin);
		while($current_day!=$date_limite)
		{
			$nb_jours = $nb_jours + $tab_periode_calcul[$current_day]['am'] + $tab_periode_calcul[$current_day]['pm'];
			$current_day=jour_suivant($current_day);
		}
		 $nb_jours = $nb_jours * 0.5; 
		return $nb_jours; 
	}
	else
		return 0; 
}


// calcule le nb de jours de conges à prendre pour un user entre 2 dates
// retourne le nb de jours  (opt_debut et opt_fin ont les valeurs "am" ou "pm"
function compter_old($user, $date_debut, $date_fin, $opt_debut, $opt_fin)
{	
	// verif si date_debut est bien anterieure à date_fin
	if (strtotime($date_debut) > strtotime($date_fin))
		return 0 ;
	
	
	if( ($date_debut!=0) && ($date_fin!=0) )
	{
		$nb_jours=0;

		//connexion mysql
		$mysql_link = connexion_mysql() ;

		// on initialise le tableau global des jours fériés s'il ne l'est pas déjà :
		if(!isset($_SESSION["tab_j_feries"]))
		{
			init_tab_jours_feries($mysql_link);
			//print_r($_SESSION["tab_j_feries"]);   // verif DEBUG
		}

		$current_day=$date_debut;
		$date_limite=jour_suivant($date_fin);

		// on va avancer jour par jour jusqu'à la date limite et voir si chaque jour est travaillé, férié, rtt, etc ...
		while($current_day!=$date_limite)
		{
			$pieces = explode("-", $current_day);  // date de la forme yyyy-mm-jj
			$y=$pieces[0]; 
			$m=$pieces[1]; 
			$j=$pieces[2]; 
			$timestamp_du_jour=mktime (0,0,0,$m,$j,$y);

			// on regarde si le jour est travaillé ou pas
			$j_name=date("D", $timestamp_du_jour);
			if( (($j_name=="Sat")&&($_SESSION['config']['samedi_travail']==FALSE)) 
				|| (($j_name=="Sun")&&($_SESSION['config']['dimanche_travail']==FALSE)))
			{
				// on ne compte pas ce jour
			}
			else
			{
				if(est_chome($timestamp_du_jour)==TRUE) // verif si jour férié
				{
					// on ne compte pas ce jour
				}
				else
				{
					// verif des rtt ou temp partiel (dans la table rtt)
					$val_matin="N";
					$val_aprem="N";
					recup_infos_artt_du_jour($user, $timestamp_du_jour, $val_matin, $val_aprem, $mysql_link);

					// si on est le premier jour et que les conges commencent à midi
					if( ($current_day==$date_debut) && ($opt_debut=="pm") ) 
					{
						//on ne traite pas le matin
					}
					else
					{
						if($val_matin!="Y")  // pas de rtt le matin
							$nb_jours=$nb_jours+0.5;
					}

					// si on est le dernier jour et que les conges finissent à midi
					if( ($current_day==$date_fin) && ($opt_fin=="am") )
					{
						//on ne traite pas l'apres midi
					}
					else
					{
						if($val_aprem!="Y") // pas de rtt l'après midi
							$nb_jours=$nb_jours+0.5;
					}

				}
			}
			$current_day=jour_suivant($current_day);
		}

		mysql_close($mysql_link);
		return $nb_jours; 
	}
	else
		return 0; 
}

// renvoit le jour suivant de la date paséée en paramètre sous la forme yyyy-mm-jj
function jour_suivant($date)
{
	$pieces = explode("-", $date);  // date de la forme yyyy-mm-jj
	$y=$pieces[0]; 
	$m=$pieces[1]; 
	$j=$pieces[2]; 

	$lendemain = date("Y-m-d", mktime(0, 0, 0, $m , $j+1, $y) );
	return $lendemain;
}

?>
