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

include("controle_ids.php") ;

function affiche_javascript_et_css_des_calendriers()
{
?>
<script type="text/javascript">

var timer = null;
var OldDiv = "";
var newFrame = null;
var TimerRunning = false;
// ## PARAMETRE D'AFFICHAGE du CALENDRIER ## //
//si enLigne est a true , le calendrier s'affiche sur une seule ligne,
//sinon il prend la taille sp�cifi� par d�faut;

var largeur = "150";
var separateur = "/";

/* ##################### CONFIGURATION ##################### */

/* ##- INITIALISATION DES VARIABLES -##*/
var calendrierSortie = '';
//Date actuelle
var today = '';
//Mois actuel
var current_month = '';
//Ann�e actuelle
var current_year = '' ;
//Jours actuel
var current_day = '';
//Nombres de jours depuis le d�but de la semaine
var current_day_since_start_week = '';
//On initialise le nom des mois et le nom des jours en VF :)
var month_name = new Array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
var day_name = new Array('L','M','M','J','V','S','D');
//permet de r�cup�rer l'input sur lequel on a click� et de le remplir avec la date format�e
var myObjectClick = null;
//Classe qui sera d�tect� pour afficher le calendrier
var classMove = "calendrier";
//Variable permettant de savoir si on doit garder en m�moire le champs input click�
var lastInput = null;
//Div du calendrier
var div_calendar = "";
var year, month, day = "";
/* ##################### FIN DE LA CONFIGURATION ##################### */

//########################## Fonction permettant de remplacer "document.getElementById"  ########################## //
function $(element){
	return document.getElementById(element);
}


//Permet de faire glisser une div de la gauche vers la droite
function slideUp(bigMenu,smallMenu){
	//Si le timer n'est pas finit on d�truit l'ancienne div
	if(parseInt($(bigMenu).style.left) < 0){
		$(bigMenu).style.left = parseInt($(bigMenu).style.left) + 10 + "px";
		$(smallMenu).style.left  =parseInt($(smallMenu).style.left) + 10 + "px";
		timer = setTimeout('slideUp("'+bigMenu+'","'+smallMenu+'")',10);
	}
	else{
		clearTimeout(timer);
		TimerRunning = false;
		$(smallMenu).parentNode.removeChild($(smallMenu));
		//alert("timer up bien kill");
	}
}

//Permet de faire glisser une div de la droite vers la gauche
function slideDown(bigMenu,smallMenu){
	if(parseInt($(bigMenu).style.left) > 0){
		$(bigMenu).style.left = parseInt($(bigMenu).style.left) - 10 + "px";
		$(smallMenu).style.left =parseInt($(smallMenu).style.left) - 10 + "px";
		timer = setTimeout('slideDown("'+bigMenu+'","'+smallMenu+'")',10);
	}
	else{
		clearTimeout(timer);
		TimerRunning = false;
		//delete de l'ancienne
		$(smallMenu).parentNode.removeChild($(smallMenu));
		//alert("timer down bien kill");
	}
}

//Cr�ation d'une nouvelle div contenant les jours du calendrier
function CreateDivTempo(From){
	if(!TimerRunning){
	var DateTemp = new Date();
	IdTemp = DateTemp.getMilliseconds();
	var  NewDiv = document.createElement('DIV');
		 NewDiv.style.position = "absolute";
		 NewDiv.style.top = "0px";
		 NewDiv.style.width = "100%";
		 NewDiv.className = "ListeDate";
		 NewDiv.id = IdTemp;
		 //remplissage
		 NewDiv.innerHTML = CreateDayCalandar(year, month, day);

	$("Contenant_Calendar").appendChild(NewDiv);

		if(From == "left"){
			TimerRunning = true;
			NewDiv.style.left = "-"+largeur+"px";
			slideUp(NewDiv.id,OldDiv);
		}
		else if(From == "right"){
			TimerRunning = true;
			NewDiv.style.left = largeur+"px";
			slideDown(NewDiv.id,OldDiv);
		}
		else{
			"";
			NewDiv.style.left = 0+"px";
		}
		$('Contenant_Calendar').style.height = NewDiv.offsetHeight+"px";
		$('Contenant_Calendar').style.zIndex = "200";
		OldDiv = NewDiv.id;
	}
}

//########################## FIN DES FONCTION LISTENER ########################## //
/*Ajout du listener pour d�tecter le click sur l'�l�ment et afficher le calendrier
uniquement sur les textbox de class css date */

//Fonction permettant d'initialiser les listeners
function init_evenement(){
	//On commence par affecter une fonction � chaque �v�nement de la souris
	if(window.attachEvent){
		document.onmousedown = start;
		document.onmouseup = drop;
	}
	else{
		document.addEventListener("mousedown",start, false);
		document.addEventListener("mouseup",drop, false);
	}
}
//Fonction permettant de r�cup�rer l'objet sur lequel on a click�, et l'on r�cup�re sa classe
function start(e){
	//On initialise l'�v�nement s'il n'a aps �t� cr�� ( sous ie )
	if(!e){
		e = window.event;
	}
	//D�tection de l'�l�ment sur lequel on a click�
	var monElement = null;
	monElement = (e.target)? e.target:e.srcElement;
	if(monElement != null && monElement)
	{
		//On appel la fonction permettant de r�cup�rer la classe de l'objet et assigner les variables
		getClassDrag(monElement);

		if(myObjectClick){
			initialiserCalendrier(monElement);
			lastInput = myObjectClick;
		}
	}
}
function drop(){
		 myObjectClick = null;
}
//########################## Fonction permettant de r�cup�rer la liste des classes d'un objet ##########################//
function getClassDrag(myObject){
	with(myObject){
		var x = className;
		listeClass = x.split(" ");
		//On parcours le tableau pour voir si l'objet est de type calendrier
		for(var i = 0 ; i < listeClass.length ; i++){
			if(listeClass[i] == classMove){
				myObjectClick = myObject;
				break;
			}
		}
	}
}

//########################## Pour combler un bug d'ie 6 on masque les select ########################## //
function masquerSelect(){
        var ua = navigator.userAgent.toLowerCase();
        var versionNav = parseFloat( ua.substring( ua.indexOf('msie ') + 5 ) );
        var isIE        = ( (ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && (ua.indexOf('webtv') == -1) );

        if(isIE && (versionNav < 7)){
	         svn=document.getElementsByTagName("SELECT");
             for (a=0;a<svn.length;a++){
                svn[a].style.visibility="hidden";
             }
        }
}

function montrerSelect(){
       var ua = navigator.userAgent.toLowerCase();
        var versionNav = parseFloat( ua.substring( ua.indexOf('msie ') + 5 ) );
        var isIE        = ( (ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && (ua.indexOf('webtv') == -1) );
        if(isIE && versionNav < 7){
	         svn=document.getElementsByTagName("SELECT");
             for (a=0;a<svn.length;a++){
                svn[a].style.visibility="visible";
             }
         }
}

function createFrame(){
	newFrame = document.createElement('iframe');
	newFrame.style.width = largeur+"px";
	newFrame.style.height = div_calendar.offsetHeight-10+"px";
	newFrame.style.zIndex = "0";
	newFrame.frameBorder="0";
	newFrame.style.position = "absolute";
	newFrame.style.display = "block";
	//newFrame.style.opacity = 0 ;
	//newFrame.filters.alpha.opacity = 0 ;
	newFrame.style.top = 0 +"px";
	newFrame.style.left = 0+"px";
	div_calendar.appendChild(newFrame);
}

//######################## FONCTIONS PROPRE AU CALENDRIER ########################## //
//Fonction permettant de passer a l'annee pr�c�dente
function annee_precedente(){

	//On r�cup�re l'annee actuelle puis on v�rifit que l'on est pas en l'an 1 :-)
	if(current_year == 1){
		current_year = current_year;
	}
	else{
		current_year = current_year - 1 ;
	}
	//et on appel la fonction de g�n�ration de calendrier
	CreateDivTempo('left');
	//calendrier(	current_year , current_month, current_day);
}

//Fonction permettant de passer � l'annee suivante
function annee_suivante(){
	//Pas de limite pour l'ajout d'ann�e
	current_year = current_year +1 ;
	//et on appel la fonction de g�n�ration de calendrier
	//calendrier(	current_year , current_month, current_day);
	CreateDivTempo('right');
}

//Fonction permettant de passer au mois pr�c�dent
function mois_precedent(){

	//On r�cup�re le mois actuel puis on v�rifit que l'on est pas en janvier sinon on enl�ve une ann�e
	if(current_month == 0){
		current_month = 11;
		current_year = current_year - 1;
	}
	else{
		current_month = current_month - 1 ;
	}
	//et on appel la fonction de g�n�ration de calendrier
	CreateDivTempo('left');
	//calendrier(	current_year , current_month, current_day);
}

//Fonction permettant de passer au mois suivant
function mois_suivant(){
	//On r�cup�re le mois actuel puis on v�rifit que l'on est pas en janvier sinon on ajoute une ann�e
	if(current_month == 11){
		current_month = 0;
		current_year = current_year  + 1;
	}
	else{
		current_month = current_month + 1;
	}
	//et on appel la fonction de g�n�ration de calendrier
	//calendrier(	current_year , current_month, current_day);
	CreateDivTempo('right');
}

//Fonction principale qui g�n�re le calendrier
//Elle prend en param�tre, l'ann�e , le mois , et le jour
//Si l'ann�e et le mois ne sont pas renseign�s , la date courante est affect� par d�faut
function calendrier(year, month, day){
 	//Aujourd'hui si month et year ne sont pas renseign�s
	if(month == null || year == null){
		today = new Date();
	}
	else{
		//month = month - 1;
		//Cr�ation d'une date en fonction de celle pass�e en param�tre
		today = new Date(year, month , day);
	}


	//Mois actuel
	current_month = today.getMonth()

	//Ann�e actuelle
	current_year = today.getFullYear();

	//Jours actuel
	current_day = today.getDate();


	//######################## ENTETE ########################//
	//Ligne permettant de changer l'ann�e et de mois
	var month_bef = "<a href=\"javascript:mois_precedent()\" style=\"position:absolute;left:30px;z-index:200;\" > < </a>";
	var month_next = "<a href=\"javascript:mois_suivant()\" style=\"position:absolute;right:30px;z-index:200;\"> > </a>";
	var year_next = "<a href=\"javascript:annee_suivante()\" style=\"position:absolute;right:5px;z-index:200;\" >&nbsp;&nbsp; > > </a>";
	var year_bef = "<a href=\"javascript:annee_precedente()\" style=\"position:absolute;left:5px;z-index:200;\"  > < < &nbsp;&nbsp;</a>";
	calendrierSortie = "<p class=\"titleMonth\" style=\"position:relative;z-index:200;\"> <a href=\"javascript:alimenterChamps('')\" style=\"float:left;margin-left:3px;color:#cccccc;font-size:10px;z-index:200;\"> Effacer la date </a><a href=\"javascript:masquerCalendrier()\" style=\"float:right;margin-right:3px;color:red;font-weight:bold;font-size:12px;z-index:200;\">X</a>&nbsp;</p>";
	//On affiche le mois et l'ann�e en titre
	calendrierSortie += "<p class=\"titleMonth\" style=\"float:left;position:relative;z-index:200;\">" + year_next + year_bef+  month_bef + "<span id=\"curentDateString\">" + month_name[current_month]+ " "+ current_year +"</span>"+ month_next+"</p><div id=\"Contenant_Calendar\">";
	//######################## FIN ENTETE ########################//

	//Si aucun calendrier n'a encore �t� cr�e :
	if(!document.getElementById("calendrier")){
		//On cr�e une div dynamiquement, en absolute, positionn� sous le champs input
		div_calendar = document.createElement("div");

		//On lui attribut un id
		div_calendar.setAttribute("id","calendrier");

		//On d�finit les propri�t�s de cette div ( id et classe )
		div_calendar.className = "calendar";

		//Pour ajouter la div dans le document
		var mybody = document.getElementsByTagName("body")[0];

		//Pour finir on ajoute la div dans le document
		mybody.appendChild(div_calendar);
	}
	else{
			div_calendar = document.getElementById("calendrier");
	}

	//On ins�rer dans la div, le contenu du calendrier g�n�r�
	//On assigne la taille du calendrier de fa�on dynamique ( on ajoute 10 px pour combler un bug sous ie )
	var width_calendar = largeur+"px";
 	//Ajout des �l�ments dans le calendrier
	calendrierSortie = calendrierSortie + "</div><div class=\"separator\"></div>";
	div_calendar.innerHTML = calendrierSortie;
	div_calendar.style.width = width_calendar;
	//On remplit le calendrier avec les jours
//	alert(CreateDayCalandar(year, month, day));
	CreateDivTempo('');
}

function CreateDayCalandar(){

	// On r�cup�re le premier jour de la semaine du mois
	var dateTemp = new Date(current_year, current_month,1);

	//test pour v�rifier quel jour �tait le prmier du mois
	current_day_since_start_week = (( dateTemp.getDay()== 0 ) ? 6 : dateTemp.getDay() - 1);

	//variable permettant de v�rifier si l'on est d�ja rentr� dans la condition pour �viter une boucle infinit
	var verifJour = false;

	//On initialise le nombre de jour par mois
	var nbJoursfevrier = (current_year % 4) == 0 ? 29 : 28;
	//Initialisation du tableau indiquant le nombre de jours par mois
	var day_number = new Array(31,nbJoursfevrier,31,30,31,30,31,31,30,31,30,31);

	var x = 0

	//On initialise la ligne qui comportera tous les noms des jours depuis le d�but du mois
	var list_day = '';
	var day_calendar = '';
	//On remplit le calendrier avec le nombre de jour, en remplissant les premiers jours par des champs vides
	for(var nbjours = 0 ; nbjours < (day_number[current_month] + current_day_since_start_week) ; nbjours++){

		// On boucle tous les 7 jours pour cr�er la ligne qui comportera le nom des jours en fonction des<br />
		// param�tres d'affichage
		if(verifJour == false){
			for(x = 0 ; x < 7 ; x++){
				if(x == 6){
					list_day += "<span>" + day_name[x] + "</span>";
				}
				else{
					list_day += "<span>" + day_name[x] + "</span>";
				}
			}
			verifJour = true;
		}
		//et enfin on ajoute les dates au calendrier
		//Pour g�rer les jours "vide" et �viter de faire une boucle on v�rifit que le nombre de jours corespond bien au
		//nombre de jour du mois
		if(nbjours < day_number[current_month]){
			if(current_day == (nbjours+1)){
				day_calendar += "<span onclick=\"alimenterChamps(this.innerHTML)\" class=\"currentDay DayDate\">" + (nbjours+1) + "</span>";
			}
			else{
				day_calendar += "<span class=\"DayDate\" onclick=\"alimenterChamps(this.innerHTML)\">" + (nbjours+1) + "</span>";
			}
		}
	}

	//On ajoute les jours "vide" du d�but du mois
	for(i  = 0 ; i < current_day_since_start_week ; i ++){
		day_calendar = "<span>&nbsp;</span>" + day_calendar;
	}
	//On met �galement a jour le mois et l'ann�e
	$('curentDateString').innerHTML = month_name[current_month]+ " "+ current_year;
	return (list_day  + day_calendar);
}

function initialiserCalendrier(objetClick){
		//on affecte la variable d�finissant sur quel input on a click�
		myObjectClick = objetClick;

		if(myObjectClick.disabled != true){
		    //On v�rifit que le champs n'est pas d�ja remplit, sinon on va se positionner sur la date du champs
		    if(myObjectClick.value != ''){
			    //On utilise la chaine de separateur
					var reg=new RegExp("/", "g");
					var dateDuChamps = myObjectClick.value;
					var tableau=dateDuChamps.split(reg);
					calendrier(	tableau[2] , tableau[1] - 1 , tableau[0]);
		    }
		    else{
			    //on cr�er le calendrier
			    calendrier(objetClick);


		    }
		    //puis on le positionne par rapport a l'objet sur lequel on a click�
		    //positionCalendar(objetClick);
		    positionCalendar(objetClick);
			fadePic();
		    //masquerSelect();
			createFrame();
		}

}

 //Fonction permettant de trouver la position de l'�l�ment ( input ) pour pouvoir positioner le calendrier
function ds_getleft(el) {
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}

function ds_gettop(el) {
	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}

//fonction permettant de positioner le calendrier
function positionCalendar(objetParent){
	//document.getElementById('calendrier').style.left = ds_getleft(objetParent) + "px";
	document.getElementById('calendrier').style.left = ds_getleft(objetParent) + "px";
	//document.getElementById('calendrier').style.top = ds_gettop(objetParent) + 20 + "px" ;
	document.getElementById('calendrier').style.top = ds_gettop(objetParent) + 20 + "px" ;
	// et on le rend visible
	document.getElementById('calendrier').style.visibility = "visible";
}
//Fonction permettant d'alimenter le champs
function alimenterChamps(daySelect){
		if(daySelect != ''){
			lastInput.value= formatInfZero(daySelect) + separateur + formatInfZero((current_month+1)) + separateur +current_year;
		}
		else{
			lastInput.value = '';
		}
		masquerCalendrier();
}
function masquerCalendrier(){
		fadePic();
		//On Masque la frame /!\
//		newFrame.style.display = "none";
		document.getElementById('calendrier').style.visibility = "hidden";
		//montrerSelect();
}

function formatInfZero(numberFormat){
		if(parseInt(numberFormat) < 10){
				numberFormat = "0"+numberFormat;
		}

		return numberFormat;
}

function CreateSpan(){
	var spanTemp = document.createElement("span");
		spanTemp.className = "";
		spanTemp.innerText = "";
		spanTemp.onClick = "";
	return spanTemp;
}

//######################## FONCTION PERMETTANT DE VERIFIER UNE DATE SAISI PAR L UTILISATEUR ########################//
function CheckDate(d) {
      // Format de la date : JJ/MM/AAAA .
      var j=(d.substring(0,2));
      var m=(d.substring(3,5));
      var a=(d.substring(6));
	  var regA = new RegExp("[0-9]{4}");
	  alert(regA.test(a));
      if ( ((isNaN(j))||(j<1)||(j>31))) {
         return false;
      }

      if ( ((isNaN(m))||(m<1)||(m>12))) {
         return false;
      }

      if ((isNaN(a))||(regA.test(a))) {
         return false;
      }
      return true;
}
//######################## FONCTION PERMETTANT D'AFFICHER LE CALENDRIER DE FACON PROGRESSIVE ########################//
var max = 100;
var min = 0;
var opacite=min;
up=true;
var IsIE=!!document.all;


function fadePic(){
try{
				var ThePic=document.getElementById("calendrier");
				if (opacite < max && up){opacite+=5;}
				if (opacite>min && !up){opacite-=5;}
				IsIE?ThePic.filters[0].opacity=opacite:document.getElementById("calendrier").style.opacity=opacite/100;

				if(opacite<max && up){
					timer = setTimeout('fadePic()',10);
				}
				else if(opacite>min && !up){
					timer = setTimeout('fadePic()',10);
				}
				else{
					if (opacite==max){up=false;}
					if (opacite<=min){up=true;}
					clearTimeout(timer);
				}
}
catch(error){
	alert(error.message);
}
}

window.onload = init_evenement;
</script>

<style type="text/css">
/* CSS Document */
.calendar{
	background-color:#f7f6f3;
	position:absolute;
	font-family:Arial, Helvetica, sans-serif;
	font-size:9px;
	border:1px solid #0099cc;
	-moz-opacity:0;
	filter:alpha(opacity=0);

}
.calendar a{
	text-decoration:none;
	color:#ffffff;
	font-weight:bold;
}
.ListeDate{
	background-color:#FFFFFF;
}
#Contenant_Calendar{
	float:left;
	width:100%;
	overflow:hidden;
	position:relative;
}
#Contenant_Calendar span{
	float:left;
	display:block;
	width:20px;
	height:20px;
	line-height:20px;
	text-align:center;
}
.DayDate:hover{
	background-color:#8CD1EC;
	cursor:pointer;
}
#curentDateString{
	width:100%;
	text-align:center;
}
.titleMonth{
	width:100%;
	background-color:#08a1d4;
	color:#FFFFFF;
	text-align:center;
	border-bottom:1px solid #666;
	margin:0px;
	padding:0px;
	padding-bottom:2px;
	margin-top:0px;
	margin-bottom:0px;
	font-weight:bold;
}
.separator{
	float:left;
	display:block;
	width:15px;
}
.currentDay{
	font-weight:bold;
	background-color:#FFB0B0;
}
/* pour l'image de fond (calendrier) du champ de saisie */
input.DatePicker_trigger{
	background-image:url(../img/DatePicker.gif);
	background-position:100% 50%;
	background-repeat:no-repeat;
	cursor:pointer;
	padding-right:20px;
	width:90px
}

</style>
<?php
}

?>
