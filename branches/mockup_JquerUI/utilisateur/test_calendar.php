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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';
include INCLUDE_PATH .'session.php';
include ROOT_PATH .'fonctions_calcul.php';
// include  INCLUDE_PATH .'sql.class.php';

//include$_SESSION['config']['lang_file'] ;

$DEBUG=FALSE;
//$DEBUG=TRUE;


if($_SESSION['config']['where_to_find_user_email']=="ldap"){ include CONFIG_PATH .'config_ldap.php';}



	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$onglet = getpost_variable('onglet');
	
	
	/*********************************/
	/*   COMPOSITION DES ONGLETS...  */
	/*********************************/

	 $onglets = array(1,2);
	
	// if( $_SESSION['config']['user_saisie_demande'] || $_SESSION['config']['user_saisie_mission'] )
		// $onglets['nouvelle_absence'] = _('divers_nouvelle_absence');

	// if( $_SESSION['config']['user_echange_rtt'] )
		// $onglets['echange_jour_absence'] = _('user_onglet_echange_abs');
		
	// if( $_SESSION['config']['user_saisie_demande'] )
		// $onglets['demandes_en_cours'] = _('user_onglet_demandes');
	
	// $onglets['historique_conges'] = _('user_onglet_historique_conges');
	// $onglets['historique_autres_absences'] = _('user_onglet_historique_abs');

	// if( $_SESSION['config']['auth'] && $_SESSION['config']['user_ch_passwd'] )
		// $onglets['changer_mot_de_passe'] = _('user_onglet_change_passwd');
	
	// if ( !isset($onglets[ $onglet ]) && !in_array($onglet, array('modif_demande','suppr_demande')))
		// $onglet = 'nouvelle_absence';
	
	/*********************************/
	/*   COMPOSITION DU HEADER...    */
	/*********************************/
	
	$add_css = '<style>
		#onglet_menu { width: 100%;}
		#onglet_menu .onglet.active{ border: 1px solid black; margin: -1px; }
		#onglet_menu .onglet{ width: '. (100 / count($onglets) ).'% ; float: left; padding: 10px 0px 10px 0px;}
	</style>
	
	
	';
	
	header_menu('user','',$add_css);
	
	/*****tablo des jours feries*******/
	include CONFIG_PATH ."dbconnect.php";
	// $mysql_serveur="10.14.42.36" ;
	// $mysql_user="userconges" ;
	// $mysql_pass="conges";
	// $mysql_database= "db_conges" ;
	mysql_connect($mysql_serveur,$mysql_user,$mysql_pass);
	mysql_select_db($mysql_database);
	$sql= "SELECT jf_date FROM conges_jours_feries";
	$res= mysql_query($sql);

	while ($don = mysql_fetch_array($res))
	{
	$jourferie[] = str_replace("-","",$don['jf_date']);
	}
	// print_r($jourferie);
	
	/* TEST CALENDRIER*/
	echo '	<link type="text/css" href="../css/trontastic/jquery-ui-1.8.18.custom.css" rel="stylesheet" />';
	?>
	<script>
	$(function() {
		$( "#datepicker" ).datepicker( $.datepicker.regional[ "fr" ] );
		var dates = $( "#from, #to" ).datepicker({
			// defaultDate: "+1w",
			dateFormat : "dd-mm-yy",
			//beforeShowDay:$.datepicker.noWeekends,
			// beforeShow:  isAvailable,
			beforeShowDay: disabledays,
			changeMonth: true,
			//numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				//alert(selectedDate);
				dates.not( this ).datepicker( "option", option, date );
				if (document.getElementById('from').value != '' && document.getElementById('to').value!='') compter_jours1();
			}
		});
	});
	
	function strpad(val){
		return (!isNaN(val) && val.toString().length==1)?"0"+val:val;
	}
var unavailableDates = <?php echo json_encode($jourferie);?>;
// var unavailableDates = <?php echo json_encode(explode(",", $cloDays));?>;
// $cloDays = json_encode(explode(",", $cloDays));  
// var unavailableDates = new Array();
// var unavailableDates = ["20120323","20120322"];
function disabledays(date) {

	dmy =  date.getFullYear()+''+ strpad((date.getMonth() + 1))+''+strpad(date.getDate());
     var day = date.getDay();
    if ((jQuery.inArray(dmy, unavailableDates) >= 0 ) || (day == 0 || day == 6)){ 
        return [false, "", "Fermé"]
    } else {
       
        // return [(day != 0 && day != 6)];
        return [true,"","Ouvert"];
    }
// document.getElementById('arrayjs').innerHTML += unavailableDates[1]+' '+date+'<br/>';
}
</script>
	<script>
	$(function() {
		$( "#radio" ).buttonset();
		// $( "#radio1" ).buttonset();
	});
	function compter_jours1(/*radiodebut, radiofin, login_user, j_debut, j_fin*/)
{
		var originalbg = $('#new_nb_jours').css('backgroundColor');
		$('#new_nb_jours').animate({
					'backgroundColor': '#ff8888',
					'color': 'red'
				}, 1500, function(){
							$('#new_nb_jours').css({
							'backgroundColor': 'white',
							'color': 'black'
							})
				});
				//$('#new_nb_jours').css({'backgroundColor':'transparent'});
// var login = document.forms[0].login_user.value;
// var session = document.forms[0].session.value;
var session = document.getElementById('session').value;
var login = document.getElementById('login').value;
var from = document.getElementById('from').value;
a_from = from.split('-');
var d_debut = a_from[2]+'-'+a_from[1]+'-'+a_from[0];
// alert (d_debut);
var to = document.getElementById('to').value;
a_to = to.split('-');
var d_fin = a_to[2]+'-'+a_to[1]+'-'+a_to[0];

var rad1 = document.getElementById('radio1').checked;
var rad2 = document.getElementById('radio2').checked;
var rad3 = document.getElementById('radio3').checked;
var rad4 = document.getElementById('radio4').checked;
if (rad1 == true) {opt_deb = document.getElementById('radio1').value} else {opt_deb = document.getElementById('radio2').value}
if (rad3 == true) {opt_fin = document.getElementById('radio3').value} else {opt_fin = document.getElementById('radio4').value}
var msg = 'de ' + d_debut + ' à ' + d_fin;
if( (d_debut) && (d_fin))
{
 var page ='../calcul_nb_jours_pris.php?session='+session+'&date_debut='+d_debut+'&date_fin='+d_fin+'&user='+login+'&opt_debut='+opt_deb+'&opt_fin='+opt_fin;
//alert(msg);
// window.open(page, '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=350,height=250');
$.get('../xhr_calcul_jour_pris.php', {
//idsup:id  //variable de type GET (on récupèrera la variable avec $_GET['idsup'])
session:session,
date_debut:d_debut,
date_fin:d_fin,
user:login,
opt_debut:opt_deb,
opt_fin:opt_fin
}, function(data){
//si la requête s'est bien déroulée
if (data) {
		document.getElementById('new_nb_jours').value= data;
		// Création de l'objet
		// var XHR = new XHRConnection();		
		// // Zone à remplir
		// XHR.setRefreshArea('jourpris');
		// XHR.sendAndLoad("jq.php?id="+data, "GET");
		return true;
		} else{
alert('Problème de connexion à la base de donnée');
}
});
}
}

	</script>	
	
<div class="content">
<div id="radio">
<div>

<label for="from">Du</label><input type="text" id="from" name="from"/><br/>
<label for="radio1">Matin</label><input type="radio" onclick="compter_jours1()" id="radio1" name="new_demi_jour_deb" value="am" checked="checked"/>
<label for="radio2">Après-Midi</label><input type="radio" onclick="compter_jours1()" id="radio2" name="new_demi_jour_deb" value="pm" />
<br/><br>
<!---	
<label for="new_demi_jour_deb">Matin</label><input type="radio" name="new_demi_jour_deb" value="am" checked>
<label for="new_demi_jour_deb">Aprem</label><input type="radio" name="new_demi_jour_deb" value="pm" >
-->
<label for="to">jusqu'au </label><input type="text" id="to" name="to"/><br/>

<label for="radio3">Matin</label><input type="radio" onclick="compter_jours1()" id="radio3" name="new_demi_jour_fin" value="am"/>
<label for="radio4">Après-Midi</label><input type="radio" onclick="compter_jours1()" id="radio4" name="new_demi_jour_fin" value="pm"  checked="checked"/>

</div>
<div style="display:inline;">
<!--
<label for="new_demi_jour_fin">Matin</label><input type="radio" value="am"  name="new_demi_jour_fin">
<label for="new_demi_jour_fin">Aprem</label><input type="radio" value="am"  name="new_demi_jour_fin" checked>
-->
<?php
$user_login = $_SESSION['userlogin'];
echo '<table cellpadding="0" cellspacing="2" border="0" >';
				echo '<tr>';
				echo '<td valign="top">';
					echo '<table id="JOUR" cellpadding="2" cellspacing="3" border="0" >';
//					echo '<input type="text" name="login_user" value="'.'.$_SESSION['userlogin'].'.'">';
					echo '<input type="hidden" id="login"  name="login_user" value="'.$user_login.'">';
					echo '<input type="hidden" id="session" name="session" value="'.$session.'">';
					// bouton 'compter les jours'
					if($_SESSION['config']['affiche_bouton_calcul_nb_jours_pris'])
					{
						echo '<tr><td colspan="2">';
							echo '<input type="button" onclick="compter_jours1();return false;" value="'. _('saisie_conges_compter_jours') .'">';
						echo '</td></tr>';
					}
					// zones de texte
					echo '<tr align="center"><td><span id="NB"><b>'. _('saisie_conges_nb_jours') .'</b></td><td><b>'. _('divers_comment_maj_1') .'</b></span></td></tr>';

					if($_SESSION['config']['disable_saise_champ_nb_jours_pris'])  // zone de texte en readonly et grisée
						$text_nb_jours ='<input type="text" id="new_nb_jours" style="background-color: white;" name="new_nb_jours" size="10" maxlength="30" value="" style="background-color: #D4D4D4; " readonly="readonly">' ;
					else
						$text_nb_jours ='<input type="text" id="new_nb_jours" name="new_nb_jours" size="10" maxlength="30" value="">' ;

					$text_commentaire='<input type="text" name="new_comment" size="25" maxlength="30" value="">' ;
					echo '<tr align="center">';
					echo '<td>'.($text_nb_jours).'</td><td>'.($text_commentaire).'</td>';
					echo '</tr>';
					echo '<tr align="center"><td><img src="'. TEMPLATE_PATH . 'img/shim.gif" width="15" height="10" border="0" vspace="0" hspace="0"></td><td></td></tr>';
					echo '<tr align="center">';
					echo '<td colspan=2>';
						echo '<input type="hidden" name="user_login" value="'.$user_login.'">';
						echo '<input type="hidden" name="new_demande_conges" value=1>';
						// boutons du formulaire
						// les classes "button_type_submit" et "button_type_cancel"
						// servent à choisir leur position (droite gauche) dans vos feuilles de style (voir style.css)
						echo '<input type="submit" class="button_type_submit" value="'. _('form_submit') .'">   <input type="reset" class="button_type_cancel" value="'. _('form_cancel') .'">';
					echo '</td>';
					echo '</tr>';
					echo '</table>';

				echo '</td>';
				/*****************/
				/* boutons radio */
				/*****************/
				// recup d tableau des types de conges
				$tab_type_conges=recup_tableau_types_conges( $DEBUG);
				// recup du tableau des types d'absence
				$tab_type_absence=recup_tableau_types_absence( $DEBUG);
				// recup d tableau des types de conges exceptionnels
				$tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels( $DEBUG);

				$already_checked = false;
				
				 echo '<td align="left" valign="top">';
				// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
				// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
				if( (($_SESSION['config']['user_saisie_demande'])&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['user_saisie_demande']==FALSE)&&($user_login!=$_SESSION['userlogin'])) )
				{
					// congés
					echo '<br/><b><i><u>'. _('divers_conges') .' :</u></i></b><br>';
					foreach($tab_type_conges as $id => $libelle)
					{
						if($id==1) {
							echo '<br/><input type="radio" id="conges'.$id.'" name="new_type" value="'.$id.'" checked> <label for="conges'.$id.'">'.$libelle.'</label>';
							$already_checked = true;
						}
						else
							echo '<input  id="conges'.$id.'" type="radio" name="new_type" value="'.$id.'"><label for="conges'.$id.'">'.$libelle.'</label>';
					}
				}
				// si le user a droit de saisir une mission ET si on est PAS dans une fenetre de responsable
				// OU si le resp a droit de saisir une mission ET si on est PAS dans une fenetre dd'utilisateur
				// OU si le resp a droit de saisir une mission ET si le resp est resp de lui meme
				if( (($_SESSION['config']['user_saisie_mission'])&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['resp_saisie_mission'])&&($user_login!=$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['resp_saisie_mission'])&&(is_resp_of_user($_SESSION['userlogin'], $user_login,  $DEBUG))) )
				{
					echo '<br>';
					// absences
					echo '<br/><b><i><u>'. _('divers_absences') .' :</u></i></b><br><br>';
					foreach($tab_type_absence as $id => $libelle) {
						if (!$already_checked){
							echo '<input type="radio" id="abs'.$id.'" name="new_type" value="'.$id.'" checked> <label for="abs'.$id.'">'.$libelle.'</label>';
							$already_checked = true;
						}
						else
							echo '<input type="radio" id="abs'.$id.'" name="new_type" value="'.$id.'"> <label for="abs'.$id.'">'.$libelle.'</label>';
					}
				}
				// si le user a droit de saisir une demande de conges ET si on est PAS dans une fenetre de responsable
				// OU si le user n'a pas droit de saisir une demande de conges ET si on est dans une fenetre de responsable
				if( ($_SESSION['config']['gestion_conges_exceptionnels']) && (
				    (($_SESSION['config']['user_saisie_demande'])&&($user_login==$_SESSION['userlogin'])) ||
				    (($_SESSION['config']['user_saisie_demande']==FALSE)&&($user_login!=$_SESSION['userlogin'])) ) )
				{
					echo '<br>';
					// congés exceptionnels
					echo '<b><i><u>'. _('divers_conges_exceptionnels') .' :</u></i></b><br>';
					 foreach($tab_type_conges_exceptionnels as $id => $libelle)
					{
						 if($id==1) {
							 echo '<input type="radio" name="new_type" value="'.$id.'" checked> '.$libelle.'<br>';
						 }
						 else
							 echo '<input type="radio" name="new_type" value="'.$id.'"> '.$libelle.'<br>';
					 }
				}

				 echo '</td>';
				echo '</tr>';
				echo '</table>';

			echo '</td>';
			echo '</tr>';
			echo '</table>';
/**************************************************/
/* cellule 2 : boutons radio matin ou après midi *
echo '<td align="left">';
	echo '<input type="radio" name="new_demi_jour_deb" ';
	if($_SESSION['config']['rempli_auto_champ_nb_jours_pris'])
	{
		// attention : IE6 : bug avec les "OnChange" sur les boutons radio!!! (on remplace par OnClick)
		if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
			echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
		else
			echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
	}
	echo 'value="am" checked><b><u>'. _('form_am') .'</u></b><br><br>';

	echo '<input type="radio" name="new_demi_jour_deb" ';
	if($_SESSION['config']['rempli_auto_champ_nb_jours_pris'])
	{
		if( (isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')!=FALSE) )
			echo 'onClick="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return true;"' ;
		else
			echo 'onChange="compter_jours(new_debut, new_fin, login_user, new_demi_jour_deb, new_demi_jour_fin);return false;"' ;
	}
	echo 'value="pm"><b><u>'. _('form_pm') .'</u></b><br><br>';
echo '</td>';
/**************************************************/
?>
<div>
</div>							
</div>
<div id="arrayjs"></div>	
<?php	
echo $user.init_tab_jours_fermeture($user);
// print_r ($_SESSION["tab_j_fermeture"]);
	/*********************************/
	/*   AFFICHAGE DES ONGLETS...  */
	/*********************************/
	//saisie_nouveau_conges($_SESSION['userlogin'], $year_calendrier_saisie_debut, $mois_calendrier_saisie_debut, $year_calendrier_saisie_fin, $mois_calendrier_saisie_fin, $onglet, $mysql_link, $DEBUG);
	
	
	/*********************************/
	/*   AFFICHAGE DU BOTTOM ...   */
	/*********************************/

	bottom();
	exit;
?>	
</div>