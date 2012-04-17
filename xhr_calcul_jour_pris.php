<?php
// defined( '_PHP_CONGES' ) or die( 'Restricted access' );
define('_PHP_CONGES', 1);
define('ROOT_PATH', '');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );


//$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include ROOT_PATH . 'fonctions_conges.php' ;
include INCLUDE_PATH . 'fonction.php';
include INCLUDE_PATH . 'session.php';
// include ROOT_PATH . 'fonctions_calcul.php';
/*
session:session,
date_debut:d_debut,
date_fin:d_fin,
user:login,
opt_debut:opt_deb,
opt_fin:opt_fin
*/
if (isset($_GET['date_debut']) && isset($_GET['date_fin']))
{
$session =$_GET['session'];
$user = $_GET['user'];
$date_debut = $_GET['date_debut'];
$date_fin = $_GET['date_fin'];
$opt_debut = $_GET['opt_debut'];
$opt_fin = $_GET['opt_fin'];
// $comment="&nbsp;" ;
// $num_current_periode="";
/***DATE EN YY-MM-DD*/
// $d_d = explode("-",$date_debut);
// $date_debut = $d_d[2].'-'.$d_d[1].'-'.$d_d[0];
// $d_f = explode("-",$date_fin);
// $date_fin = $d_f[2].'-'.$d_f[1].'-'.$d_f[0];
//$nb_jours=compter($user, "", $date_debut, $date_fin, $opt_debut, $opt_fin, $comment, $DEBUG);
$DEBUG=FALSE;
echo comptage($user,"", $date_debut, $date_fin, $opt_debut, $opt_fin, $comment, $DEBUG);
// echo comptage($date_debut, $date_fin, $user, $num_current_periode, $tab_periode_calcul, $comment,  $DEBUG, $num_update);
// echo $comment;
}
// calcule le nb de jours de conges � prendre pour un user entre 2 dates
// retourne le nb de jours  (opt_debut et opt_fin ont les valeurs "am" ou "pm"
function comptage($user, $num_current_periode, $date_debut, $date_fin, $opt_debut, $opt_fin, &$comment,  $DEBUG=FALSE, $num_update = null)
{

	// verif si date_debut est bien anterieure � date_fin
	// ou si meme jour mais debut l'appres mide et fin le matin
	if( (strtotime($date_debut) > strtotime($date_fin)) || ( ($date_debut==$date_fin) && ($opt_debut=="pm") && ($opt_fin=="am") ) )
	{
		$comment =  _('calcul_nb_jours_commentaire_bad_date') ;
		return $comment ;
	}


	if( ($date_debut!=0) && ($date_fin!=0) )
	{

		// On ne peut pas calculer si, pour l'ann�e consid�r�e, les jours feries ont ete saisis
		if( (verif_jours_feries_saisis($date_debut,  $DEBUG, $num_update)==FALSE)
		    || (verif_jours_feries_saisis($date_fin,  $DEBUG, $num_update)==FALSE) )
		{
			$comment =  _('calcul_impossible') ."<br>\n". _('jours_feries_non_saisis') ."<br>\n". _('contacter_admin') ."<br>\n" ;
			//
			return $comment ;
		}


		/************************************************************/
		// 1 : on fabrique un tableau de jours (divis� chacun en 2 demi-jour) de la date_debut � la date_fin
		// 2 : on verifie que le conges demand� ne chevauche pas une periode deja pos�e
		// 3 : on affecte � 0 ou 1 chaque demi jour, en fonction de s'il est travaill� ou pas
		// 4 : � la fin , on parcours le tableau en comptant le nb de demi-jour � 1, on multiplie ce total par 0.5, �a donne le nb de jours du conges !

		$nb_jours=0;

		/************************************************************/
		// 1 : fabrication et initialisation du tableau des demi-jours de la date_debut � la date_fin
		$tab_periode_calcul = make_tab_demi_jours_periode($date_debut, $date_fin, $opt_debut, $opt_fin, $DEBUG);


		/************************************************************/
		// 2 : on verifie que le conges demand� ne chevauche pas une periode deja pos�e
		if(verif_periode_chevauche_periode_user($date_debut, $date_fin, $user, $num_current_periode, $tab_periode_calcul, $comment,  $DEBUG, $num_update) )
			return $comment;

		/************************************************************/
		// 3 : on affecte � 0 ou 1 chaque demi jour, en fonction de s'il est travaill� ou pas

		// on initialise le tableau global des jours f�ri�s s'il ne l'est pas d�j� :
		if(!isset($_SESSION["tab_j_feries"]))
		{
			init_tab_jours_feries();
			//print_r($_SESSION["tab_j_feries"]);   // verif DEBUG
		}
		// on initialise le tableau global des jours ferm�s s'il ne l'est pas d�j� :
		if(!isset($_SESSION["tab_j_fermeture"]))
		{
			init_tab_jours_fermeture($user,  $DEBUG);
			//print_r($_SESSION["tab_j_fermeture"]);   // verif DEBUG
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
			elseif(est_chome($timestamp_du_jour)) // verif si jour f�ri�
			{
				// on ne compte ce jour � 0
				$tab_periode_calcul[$current_day]['am']=0;
				$tab_periode_calcul[$current_day]['pm']=0;
			}
			else
			{
				/***************/
				// verif des rtt ou temp partiel (dans la table rtt)
				$val_matin="N";
				$val_aprem="N";
				recup_infos_artt_du_jour($user, $timestamp_du_jour, $val_matin, $val_aprem);

				if($val_matin=="Y")  // rtt le matin
					$tab_periode_calcul[$current_day]['am']=0;

				if($val_aprem=="Y") // rtt l'apr�s midi
					$tab_periode_calcul[$current_day]['pm']=0;
			}

			$current_day=jour_suivant($current_day);
		}


		if( $DEBUG ) { echo "tab_periode_calcul :<br>\n"; print_r($tab_periode_calcul); echo "<br>\n"; }

		/************************************************************/
		// 3 : on va avancer jour par jour jusqu'� la date limite pour compter le nb de demi jour � 1
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
		return _('calcul_impossible') ;
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

// verifie si les jours f�ri�s de l'annee de la date donn�e sont enregistr�s
// retourne TRUE ou FALSE
function verif_jours_feries_saisis($date,  $DEBUG=FALSE)
{

	// on calcule le premier de l'an et le dernier de l'an de l'ann�e de la date passee en parametre
	$tab_date=explode("-", $date); // date est de la forme aaaa-mm-jj
	$an=$tab_date[0];
	$premier_an="$an-01-01";
	$dernier_an="$an-12-31";

	$sql_select='SELECT jf_date FROM conges_jours_feries WHERE jf_date >= \''.SQL::quote($premier_an).'\' AND jf_date <= \''.SQL::quote($dernier_an).'\' ';
	$res_select = SQL::query($sql_select);
	
	return ($res_select->num_rows != 0);
}



// fabrication et initialisation du tableau des demi-jours de la date_debut � la date_fin d'une periode
function make_tab_demi_jours_periode($date_debut, $date_fin, $opt_debut, $opt_fin, $DEBUG=FALSE)
{
		$tab_periode_calcul=array();
		$nb_jours_entre_date = (((strtotime($date_fin) - strtotime($date_debut))/3600)/24)+1 ;

		if( $DEBUG ) { echo "$nb_jours_entre_date<br>\n"; }

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

		if( $DEBUG ) { echo "tab_periode_calcul :<br>\n"; print_r($tab_periode_calcul); echo "<br>\n"; }

		return $tab_periode_calcul;
}



// verifie si la periode donnee chevauche une periode de conges d'un user donn�
// attention � ne pas verifer le chevauchement avec la periode qu on est en train de traiter (si celle ci a d�j� un num_periode)
// retourne TRUE si chevauchement et FALSE sinon !
function verif_periode_chevauche_periode_user($date_debut, $date_fin, $user, $num_current_periode='', $tab_periode_calcul, &$comment, $DEBUG=FALSE, $num_update = null)
{

		/************************************************************/
		// 2 : on verifie que le conges demand� ne chevauche pas une periode deja pos�e
		// -> on recupere les periodes par rapport aux dates, on en fait une tableau de 1/2 journees, et on compare par 1/2 journee

		$tab_periode_deja_prise=array();

		$current_day=$date_debut;
		$date_limite=jour_suivant($date_fin);

		// on va avancer jour par jour jusqu'� la date limite et recupere les periodes qui contiennent ce jour...
		// on construit un tableau par date et 1/2 jour avec l'�tat de la periode
		while($current_day!=$date_limite)
		{
		
			$tab_periode_deja_prise[$current_day]['am']="no" ;
			$tab_periode_deja_prise[$current_day]['pm']="no" ;
			
			if ($num_update === null)
			{
				
				// verif si c'est deja un conges
				$user_periode_sql = 'SELECT  p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat
								FROM conges_periode
								WHERE p_login = \''.SQL::quote($user).'\' AND ( p_etat=\'ok\' OR p_etat=\'valid\' OR p_etat=\'demande\' )
									'.(!empty($num_current_periode) ? 'AND p_num != '.intval($num_current_periode).' ' : '') .'
									AND p_date_deb<=\''.SQL::quote($current_day).'\' AND p_date_fin>=\''.SQL::quote($current_day).'\' ';
			
			
			}
			else
			{
				
				// verif si c'est deja un conges
				$user_periode_sql = 'SELECT  p_date_deb, p_demi_jour_deb, p_date_fin, p_demi_jour_fin, p_etat
								FROM conges_periode
								WHERE p_login = \''.SQL::quote($user).'\' AND ( p_etat=\'ok\' OR p_etat=\'valid\' OR p_etat=\'demande\' )
									'.(!empty($num_current_periode) ? 'AND p_num != '.intval($num_current_periode).' ' : '') .'
									AND p_date_deb<=\''.SQL::quote($current_day).'\' AND p_date_fin>=\''.SQL::quote($current_day).'\'
									AND p_num != \''.intval($num_update).'\' ';
			}
			
			$user_periode_request = SQL::query($user_periode_sql);
//			$user_periode_request = SQL::query($user_periode_sql);
			
			if($user_periode_request->num_rows !=0)  // le jour courant est dans un periode de conges du user
			{
			
				while($resultat_periode=$user_periode_request->fetch_array())
				{
					$sql_p_date_deb=$resultat_periode["p_date_deb"];
					$sql_p_date_fin=$resultat_periode["p_date_fin"];
					$sql_p_demi_jour_deb=$resultat_periode["p_demi_jour_deb"];
					$sql_p_demi_jour_fin=$resultat_periode["p_demi_jour_fin"];
					$sql_p_etat=$resultat_periode["p_etat"];
				
					if( ($current_day!=$sql_p_date_deb) && ($current_day!=$sql_p_date_fin) )
					{
						// pas la peine d'aller + loin, on chevauche une periode de conges !!!
						if($sql_p_etat=="demande")
							$comment =  _('calcul_nb_jours_commentaire_impossible') ;
							
						else
							$comment =  _('calcul_nb_jours_commentaire') ;

						if( $DEBUG ) { echo "tab_periode_deja_prise :<br>\n"; print_r($tab_periode_deja_prise); echo "<br>\n"; }
						return TRUE ;
					}
					elseif( ($current_day==$sql_p_date_deb) && ($current_day==$sql_p_date_fin) ) // periode sur une seule journee
					{
						if($sql_p_demi_jour_deb=="am")
							$tab_periode_deja_prise[$current_day]['am']="$sql_p_etat" ;
						if($sql_p_demi_jour_fin=="pm")
							$tab_periode_deja_prise[$current_day]['pm']="$sql_p_etat" ;
					}
					elseif($current_day==$sql_p_date_deb)
					{
						if($sql_p_demi_jour_deb=="am")
						{
							$tab_periode_deja_prise[$current_day]['am']="$sql_p_etat" ;
							$tab_periode_deja_prise[$current_day]['pm']="$sql_p_etat" ;
						}
						else // alors ($sql_p_demi_jour_deb=="pm")
							$tab_periode_deja_prise[$current_day]['pm']="$sql_p_etat" ;

					}
					else // alors ($current_day==$sql_p_date_fin)
					{
						if($sql_p_demi_jour_fin=="pm")
						{
							$tab_periode_deja_prise[$current_day]['am']="$sql_p_etat" ;
							$tab_periode_deja_prise[$current_day]['pm']="$sql_p_etat" ;
						}
						else // alors ($sql_p_demi_jour_fin=="am")
							$tab_periode_deja_prise[$current_day]['am']="$sql_p_etat" ;

					}
				}
			}

			$current_day=jour_suivant($current_day);
		}// fin du while

		/**********************************************/
		// Ensuite verifie en parcourant le tableau qu'on vient de cr�e (s'il n'est pas vide)
		
		if(count($tab_periode_deja_prise)!=0)
		{
			$current_day=$date_debut;
			$date_limite=jour_suivant($date_fin);

			// on va avancer jour par jour jusqu'� la date limite et recupere les periodes qui contiennent ce jour...
			// on construit un tableau par date et 1/2 jour avec l'�tat de la periode
			while($current_day!=$date_limite)
			{
				if( ($tab_periode_calcul[$current_day]['am']==1) && ($tab_periode_deja_prise[$current_day]['am']!="no") )
				{
					// pas la peine d'aller + loin, on chevauche une periode de conges !!!
					if($tab_periode_deja_prise[$current_day]['am']=="demande")
						$comment =  _('calcul_nb_jours_commentaire_impossible') ;
					else
						$comment =  _('calcul_nb_jours_commentaire') ;

					if( $DEBUG ) { echo "tab_periode_deja_prise :<br>\n"; print_r($tab_periode_deja_prise); echo "<br>\n"; }
					return TRUE ;
				}
				if( ($tab_periode_calcul[$current_day]['pm']==1) && ($tab_periode_deja_prise[$current_day]['pm']!="no") )
				{
					// pas la peine d'aller + loin, on chevauche une periode de conges !!!
					if($tab_periode_deja_prise[$current_day]['pm']=="demande")
						$comment =  _('calcul_nb_jours_commentaire_impossible') ;
					else
						$comment =  _('calcul_nb_jours_commentaire') ;

					if( $DEBUG ) { echo "tab_periode_deja_prise :<br>\n"; print_r($tab_periode_deja_prise); echo "<br>\n"; }
					return TRUE ;
				}

				$current_day=jour_suivant($current_day);
			}// fin du while
		}

		if( $DEBUG ) { echo "tab_periode_calcul :<br>\n"; print_r($tab_periode_calcul); echo "<br>\n"; }

		return FALSE ;

		/************************************************************/
		// Fin de le verif de chevauchement d'une p�riode d�ja saisie

}


