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

// calcule le nb de jours de conges � prendre pour un user entre 2 dates
// retourne le nb de jours  (opt_debut et opt_fin ont les valeurs "am" ou "pm"
function compter($user, $date_debut, $date_fin, $opt_debut, $opt_fin, &$comment, $mysql_link, $DEBUG=FALSE)
{	
	// verif si date_debut est bien anterieure � date_fin
	if (strtotime($date_debut) > strtotime($date_fin))
	{
		$comment = $_SESSION['lang']['calcul_nb_jours_commentaire_bad_date'];
		return 0 ;
	}
	
	
	if( ($date_debut!=0) && ($date_fin!=0) )
	{
		// On ne peut pas calculer si, pour l'ann�e consid�r�e, les jours feries n'ont pas ete saisis
		if( (verif_jours_feries_saisis($date_debut, $mysql_link, $DEBUG)==FALSE) 
		    && (verif_jours_feries_saisis($date_fin, $mysql_link, $DEBUG)==FALSE) )
		{
			$comment = $_SESSION['lang']['calcul_impossible']."<br>\n".$_SESSION['lang']['jours_feries_non_saisis']."<br>\n".$_SESSION['lang']['contacter_admin']."<br>\n" ;
			return 0 ;
		}
		

		/************************************************************/
		// 1 : on fabrique un tableau de jours (divis� chacun en 2 demi-jour) de la date_debut � la date_fin
		// 2 : on affecte � 0 ou 1 chaque demi jour, en fonction de s'il est travaill� ou pas
		// 3 : � la fin , on parcours le tableau en comptant le nb de demi-jour � 1, on multiplie ce total par 0.5, �a donne le nb de jours du conges ! 

		$nb_jours=0;
		
		/************************************************************/
		// 1 : fabrication et initialisation du tableau de demi-jours
		$tab_periode_calcul=array();
		$nb_jours_entre_date = (((strtotime($date_fin) - strtotime($date_debut))/3600)/24)+1 ;

		if($DEBUG==TRUE) { echo "$nb_jours_entre_date<br>\n"; }
		
		// on va avancer jour par jour jusqu'� la date limite 
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
		
		
		
		/************************************************************/
		// 2 : on affecte � 0 ou 1 chaque demi jour, en fonction de s'il est travaill� ou pas

		// on initialise le tableau global des jours f�ri�s s'il ne l'est pas d�j� :
		if(!isset($_SESSION["tab_j_feries"]))
		{
			init_tab_jours_feries($mysql_link);
			//print_r($_SESSION["tab_j_feries"]);   // verif DEBUG
		}

		$current_day=$date_debut;
		$date_limite=jour_suivant($date_fin);

		// on va avancer jour par jour jusqu'� la date limite et voir si chaque jour est travaill�, f�ri�, rtt, etc ...
		while($current_day!=$date_limite)
		{
			// calcul du timestamp du jour courant
			$pieces = explode("-", $current_day);  // date de la forme yyyy-mm-jj
			$y=$pieces[0]; 
			$m=$pieces[1]; 
			$j=$pieces[2]; 
			$timestamp_du_jour=mktime (0,0,0,$m,$j,$y);

			// on regarde si le jour est travaill� ou pas dans la config de l'appli
			$j_name=date("D", $timestamp_du_jour);
			if( (($j_name=="Sat")&&($_SESSION['config']['samedi_travail']==FALSE)) 
				|| (($j_name=="Sun")&&($_SESSION['config']['dimanche_travail']==FALSE)))
			{
				// on ne compte ce jour � 0
				$tab_periode_calcul[$current_day]['am']=0;
				$tab_periode_calcul[$current_day]['pm']=0;
			}
			else
			{
				if(est_chome($timestamp_du_jour)==TRUE) // verif si jour f�ri�
				{
					// on ne compte ce jour � 0
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

					if($val_aprem=="Y") // rtt l'apr�s midi
						$tab_periode_calcul[$current_day]['pm']=0;
						
					// verif si c'est deja un conges 
					// verif si le jour courant est un cong�s (p_type=1) accept� (p_etat='ok') ou non
/*					$user_periode_sql = "SELECT  p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat
									FROM conges_periode 
									WHERE p_login = '$user' AND ( p_etat='ok' OR p_etat='valid' OR p_etat='demande' ) AND p_type=1 AND p_date_deb<='$current_day' AND p_date_fin>='$current_day' ";
*/
					$user_periode_sql = "SELECT  p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat
									FROM conges_periode 
									WHERE p_login = '$user' AND ( p_etat='ok' OR p_etat='valid' OR p_etat='demande' ) AND p_date_deb<='$current_day' AND p_date_fin>='$current_day' ";
			
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
							//si c'est le cas, comme on ne sais pas si elle va �tre accept�e ou refus�e : le calcul est impossible
							if( ($sql_p_etat=="demande") || ($sql_p_etat=="valid") )
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

		/************************************************************/
		// on va avancer jour par jour jusqu'� la date limite pour compter le nb de demi jour � 1
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

// renvoit le jour suivant de la date pas��e en param�tre sous la forme yyyy-mm-jj
function jour_suivant($date)
{
	$pieces = explode("-", $date);  // date de la forme yyyy-mm-jj
	$y=$pieces[0]; 
	$m=$pieces[1]; 
	$j=$pieces[2]; 

	$lendemain = date("Y-m-d", mktime(0, 0, 0, $m , $j+1, $y) );
	return $lendemain;
}

// renvoit le jour precedent de la date pas��e en param�tre sous la forme yyyy-mm-jj
function jour_precedent($date)
{
	$pieces = explode("-", $date);  // date de la forme yyyy-mm-jj
	$y=$pieces[0]; 
	$m=$pieces[1]; 
	$j=$pieces[2]; 

	$lendemain = date("Y-m-d", mktime(0, 0, 0, $m , $j-1, $y) );
	return $lendemain;
}

function verif_jours_feries_saisis($date, $mysql_link, $DEBUG=FALSE)
{
	// on calcule le premier de l'an et le dernier de l'an de l'ann�e de la date passee en parametre
	$tab_date=explode("-", $date); // date est de la forme aaaa-mm-jj
	$an=$tab_date[0];
	$premier_an="$an-01-01";
	$dernier_an="$an-12-31";
	
	$sql_select="SELECT jf_date FROM conges_jours_feries WHERE jf_date >= '$premier_an' AND jf_date <= '$dernier_an' ";
	$res_select = requete_mysql($sql_select, $mysql_link, "verif_jours_feries_saisis", $DEBUG);
	if(mysql_num_rows($res_select)==0)
		return FALSE;
	else
		return TRUE;
}

?>
