<SCRIPT LANGUAGE="JavaScript1.2">
<!--
function OpenPopUp(MyFile,MyWindow,MyWidth,MyHeight)
//MyFile :	  nom du fichier contenant le code HTML du pop-up
//MyWindow :	  nom de la fen�tre (ne pas mettre d'espace)
//MyWidth :	  entier indiquant la largeur de la fen�tre en pixels
//MyHeight :	  entier indiquant la hauteur de la fen�tre en pixels

{	  
var ns4 = (document.layers)? true:false; 	  //NS 4
var ie4 = (document.all)? true:false;	  //IE 4
var dom = (document.getElementById)? true:false;	  //DOM
var xMax, yMax, xOffset, yOffset;;	  

    if (ie4 || dom)
        {
        xMax = screen.width;
        yMax = screen.height;
        }
    else if (ns4)
        {
        xMax = window.outerWidth;
        yMax = window.outerHeight;
        }
    else
        {
        xMax = 800;
        yMax = 600;
        }
    xOffset = (xMax - MyWidth)/2;
    yOffset = (yMax - MyHeight)/2;
    window.open(MyFile,MyWindow,'width='+MyWidth
+',height='+MyHeight
+',screenX='+xOffset
+',screenY='+yOffset
+',top='+yOffset
+',left='+xOffset
+',scrollbars=yes,resizable=yes');
    }
//-->
</SCRIPT>

<script language=javascript>

function compter_jours(radiodebut, radiofin, login_user, j_debut, j_fin)
{
	var login = document.forms[0].login_user.value;
	
	for (var i=0; i<radiodebut.length; i++) 
		if (radiodebut[i].checked) 
			var d_debut = radiodebut[i].value;
			
	for (var i=0; i<radiofin.length; i++) 
		if (radiofin[i].checked) 
			var d_fin = radiofin[i].value;	
			
	for (var i=0; i<j_debut.length; i++) 
		if (j_debut[i].checked) 
			var opt_deb = j_debut[i].value;	
	// am ou pm
	
	for (var i=0; i<j_fin.length; i++) 
		if (j_fin[i].checked) 
			var opt_fin = j_fin[i].value;	
	// am ou pm
	
	var msg = 'de ' + d_debut + ' � ' + d_fin;
	
	var page ='../calcul_nb_jours_pris.php?date_debut='+d_debut+'&date_fin='+d_fin+'&user='+login+'&opt_debut='+opt_deb+'&opt_fin='+opt_fin;
	//alert(msg);
	
	window.open(page, '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=350,height=200');
	
}
</script>


