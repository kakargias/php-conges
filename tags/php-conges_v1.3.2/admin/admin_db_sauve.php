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

$session=(isset($_GET['session']) ? $_GET['session'] : ((isset($_POST['session'])) ? $_POST['session'] : session_id()) ) ;

include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");

$verif_droits_file="../INCLUDE.PHP/verif_droits.php";
if( ($_SESSION['config']['verif_droits']==TRUE) && (file_exists($verif_droits_file)) ){ include($verif_droits_file);}

$DEBUG=FALSE ;
//$DEBUG=TRUE ;

// verif des droits du user � afficher la page
verif_droits_user($session, "is_admin", $DEBUG);


	/*** initialisation des variables ***/
	/*************************************/
	// recup des parametres re�us :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET / POST
	$choix_action    = getpost_variable("choix_action");
	$type_sauvegarde = getpost_variable("type_sauvegarde");
	$commit          = getpost_variable("commit");

	$fichier_restaure_name="";
	$fichier_restaure_tmpname="";
	$fichier_restaure_size=0;
	$fichier_restaure_error=4;
	if(isset($_FILES['fichier_restaure'])) 
	{
		$fichier_restaure_name=$_FILES['fichier_restaure']['name']; 
		$fichier_restaure_size=$_FILES['fichier_restaure']['size']; 
		$fichier_restaure_tmpname=$_FILES['fichier_restaure']['tmp_name']; 
		$fichier_restaure_error=$_FILES['fichier_restaure']['error']; 
	}
	/*************************************/
	if($DEBUG==TRUE) {	echo "_FILES = <br>\n"; print_r($_FILES); echo "<br>\n"; }

	
	if($choix_action=="")
		choix_save_restore($DEBUG);
	elseif($choix_action=="sauvegarde")
	{
		if( (!isset($type_sauvegarde)) || ($type_sauvegarde=="") )
			choix_sauvegarde($DEBUG);
		else
		{
			if( (!isset($commit)) || ($commit=="") )
				sauve($type_sauvegarde);
			else
				commit_sauvegarde($type_sauvegarde, $DEBUG);
		}
	}
	elseif($choix_action=="restaure")
	{
		if( (!isset($fichier_restaure_name)) || ($fichier_restaure_name=="")||(!isset($fichier_restaure_tmpname)) || ($fichier_restaure_tmpname=="") )
			choix_restaure($DEBUG);
		else
			restaure($fichier_restaure_name, $fichier_restaure_tmpname, $fichier_restaure_size, $fichier_restaure_error, $DEBUG);
	}
	else
		/* APPEL D'UNE AUTRE PAGE immediat */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=admin_index.php?session=$session&onglet=admin-users\">";




/**********  FONCTIONS  ****************************************/

// CHOIX
function choix_save_restore($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_sauve_db_titre']."</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th colspan=\"2\">".$_SESSION['lang']['admin_sauve_db_choisissez']." :</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td><input type=\"radio\" name=\"choix_action\" value=\"sauvegarde\" checked></td>\n";
	echo "<td><b> ".$_SESSION['lang']['admin_sauve_db_sauve']."</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td><input type=\"radio\" name=\"choix_action\" value=\"restaure\" /></td>\n";
	echo "<td><b> ".$_SESSION['lang']['admin_sauve_db_restaure']."</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	&nbsp;\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"submit\" value=\"".$_SESSION['lang']['form_submit']."\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_cancel']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


// SAUVEGARDE
function choix_sauvegarde($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_sauve_db_titre']."</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th colspan=\"2\">".$_SESSION['lang']['admin_sauve_db_options']."</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td><input type=\"radio\" name=\"type_sauvegarde\" value=\"all\" checked></td>\n";
	echo "	<td>".$_SESSION['lang']['admin_sauve_db_complete']."</td>\n";
	echo "</tr>\n";
/*	echo "<tr>\n";
	echo "	<td><input type=\"radio\" name=\"type_sauvegarde\" value=\"structure\"></td>\n";
	echo "	<td>Sauvegarde de la structure seule</td>\n";
	echo "</tr>\n";
*/
	echo "<tr>\n";
	echo "	<td><input type=\"radio\" name=\"type_sauvegarde\" value=\"data\"></td>\n";
	echo "	<td>".$_SESSION['lang']['admin_sauve_db_data_only']."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	&nbsp;\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td colspan=\"2\" align=\"center\">\n";
	echo "		<input type=\"hidden\" name=\"choix_action\" value=\"sauvegarde\">\n";
	echo "		<input type=\"submit\" value=\"".$_SESSION['lang']['admin_sauve_db_do_sauve']."\">\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_submit']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}

function sauve($type_sauvegarde, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	// on renvoit imm�diatement sur la meme page qui va lancer la sauvegarde ...
	echo "<meta http-equiv=\"refresh\" content=\"0;url=$PHP_SELF?session=$session&choix_action=sauvegarde&type_sauvegarde=$type_sauvegarde&commit=ok\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_sauve_db_titre']."</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th colspan=\"2\">".$_SESSION['lang']['admin_sauve_db_save_ok']." ...</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_close_window']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";
}

function commit_sauvegarde($type_sauvegarde, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();
	
	//connexion mysql
	$mysql_link = connexion_mysql() ;
	
	header("Pragma: no-cache");
	header("Content-Type: text/x-delimtext; name=\"php_conges_".$type_sauvegarde.".sql\"");
	header("Content-disposition: attachment; filename=php_conges_".$type_sauvegarde.".sql");

	//
	// Build the sql script file...
	//
	$maintenant=date("d-m-Y H:i:s");
	echo "#\n";
	echo "# PHP_CONGES\n";
	echo "#\n# DATE : $maintenant\n";
	echo "#\n";
	
	//recup de la liste des tables
	$sql="SHOW TABLES";
	$ReqLog = mysql_query($sql, $mysql_link) or die("ERREUR : ".$sql."<br>\n".mysql_error());
	while ($resultat = mysql_fetch_array($ReqLog))
	{
		$table=$resultat[0] ;
	
		echo "#\n#\n# TABLE: $table \n#\n";
		if(($type_sauvegarde=="all") || ($type_sauvegarde=="structure") )
		{
			echo "# Struture : \n#\n";
			echo get_table_structure($table, $mysql_link);
		}
		if(($type_sauvegarde=="all") || ($type_sauvegarde=="data") )
		{
			echo "# Data : \n#\n";
			echo get_table_data($table, $mysql_link);
		}
	}
	
	mysql_close($mysql_link);

}


// RESTAURATION
function choix_restaure($DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_sauve_db_titre']."</h1>\n";

//	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<form enctype=\"multipart/form-data\" action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>".$_SESSION['lang']['admin_sauve_db_restaure']."<br>".$_SESSION['lang']['admin_sauve_db_file_to_restore']." :</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
//	echo "<td> <input type=\"file\" name=\"fichier_restaure\" size=\"30\"> </td>\n";
	echo "<td align=\"center\"> <input type=\"file\" name=\"fichier_restaure\"> </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\"> <font color=\"red\">".$_SESSION['lang']['admin_sauve_db_warning']." !</font> </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"center\">\n";
	echo "		<input type=\"hidden\" name=\"choix_action\" value=\"restaure\">\n";
	echo "		<input type=\"submit\" value=\"".$_SESSION['lang']['admin_sauve_db_do_restaure']."\">\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_cancel']."\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


function restaure($fichier_restaure_name, $fichier_restaure_tmpname, $fichier_restaure_size, $fichier_restaure_error, $DEBUG=FALSE)
{
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$session=session_id();

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../".$_SESSION['config']['stylesheet_file']."\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES :</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>".$_SESSION['lang']['admin_sauve_db_titre']."</h1>\n";
	
	if( ($fichier_restaure_error!=0)||($fichier_restaure_size==0) ) // s'il y a eu une erreur dans le telechargement OU taille==0
	//(cf code erreur dans fichier features.file-upload.errors.html de la doc php)
	{
		//message d'erreur et renvoit sur la page pr�c�dente (choix fichier)

		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<th> ".$_SESSION['lang']['admin_sauve_db_bad_file']." : <br>$fichier_restaure_name</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "	<input type=\"hidden\" name=\"choix_action\" value=\"restaure\">\n";
		echo "	<input type=\"submit\" value=\"".$_SESSION['lang']['form_redo']."\">\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "	<input type=\"button\" value=\"".$_SESSION['lang']['form_cancel']."\" onClick=\"javascript:window.close();\">\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";

	}
	else
	{
		//connexion mysql
		$mysql_link = connexion_mysql() ;
		
		//affichage du contenu :
		//readfile($fichier_restaure_tmpname);
		
		$result = execute_sql_file($fichier_restaure_tmpname, $mysql_link, $DEBUG);
/*		// on lit le fichier et on met chaque ligne dans un tableau
		$tab_lines = file ($fichier_restaure_tmpname);
		// puis parcourt du tableau :
		// si la ligne n'est pas un commentaire (commence par # (apr�s avoir enlev� les espaces de debut de chaine))
		// on l'ajoute a la requete sql )
		$sql="";
		foreach ($tab_lines as $line_num => $line) 
		{
			$line=trim($line);
			if(substr($line,0,1)=="#")
			{
				//echo "#<b>$line_num</b> $line<br>\n";
			}
			else
			{
				//echo "$line<br>\n";
				//execution de la requete sql:
				$sql=$line;
				//echo "$sql<br>";
				$ReqLog = mysql_query($sql, $mysql_link) or die("ERREUR : RESTAURATION : <br>\n $sql<br>\n<br>\n".mysql_error());
			}
		}
*/		
		mysql_close($mysql_link);
	
		echo "<form action=\"\" method=\"POST\">\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<th>".$_SESSION['lang']['admin_sauve_db_restaure_ok']." !</th>\n";
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

	}
	
	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";


}




// recup de la structure d'une table sous forme de CREATE ...
function get_table_structure($table, $mysql_link, $DEBUG=FALSE)
{

	$chaine_drop="DROP TABLE IF EXISTS  `$table` ;\n";
	$chaine_create = "CREATE TABLE `$table` ( ";

	// description des champs :
	$sql_champs="SHOW FIELDS FROM $table";
	$ReqLog_champs = mysql_query($sql_champs, $mysql_link) or die("ERREUR : get_table_structure() <br>\n".mysql_error());
	$count_champs=mysql_num_rows($ReqLog_champs);
	$i=0;
	while ($resultat_champs = mysql_fetch_array($ReqLog_champs))
	{
		$sql_field=$resultat_champs['Field'];
		$sql_type=$resultat_champs['Type'];
		$sql_null=$resultat_champs['Null'];
		$sql_key=$resultat_champs['Key'];
		$sql_default=$resultat_champs['Default'];
		$sql_extra=$resultat_champs['Extra'];

		$chaine_create=$chaine_create." `$sql_field` $sql_type ";
		if($sql_null != "YES")
			$chaine_create=$chaine_create." NOT NULL ";
		if(!empty($sql_default))
			$chaine_create=$chaine_create." default '$sql_default' ";
		if(!empty($sql_extra))
			$chaine_create=$chaine_create." $sql_extra ";
		if($i<$count_champs-1)
			$chaine_create=$chaine_create.",";
		$i++;
	}

	// description des index :
	$sql_index = "SHOW KEYS FROM $table";
	$ReqLog_index = mysql_query($sql_index, $mysql_link) or die("ERREUR : get_table_structure() <br>\n".mysql_error());
	$count_index=mysql_num_rows($ReqLog_index);
	$i=0;
	
	// il faut faire une liste pour prendre les PRIMARY, le nom de la colonne et 
	// gen�rer un PRIMARY KEY ('key1'), PRIMARY KEY ('key2', ...) 
	// puis on regarde ceux qui ne sont pas PRIMARY et on regarde s'ils sont UNIQUE ou pas et
	// on g�n�re une liste= UNIQUE 'key1' ('key1') , 'key2' ('key2') , .... 
	// ou une liste= KEY key1' ('key1') , 'key2' ('key2') , ....
	$list_primary="";
	$list_unique="";
	$list_key="";
	while ($resultat_index = mysql_fetch_array($ReqLog_index))
	{
		$sql_key_name=$resultat_index['Key_name'];
		$sql_column_name=$resultat_index['Column_name'];
		$sql_non_unique=$resultat_index['Non_unique'];
		
		if($sql_key_name=="PRIMARY")
		{
			if($list_primary=="")
				$list_primary=" PRIMARY KEY (`$sql_column_name` ";
			else
				$list_primary=$list_primary.", `$sql_column_name` ";
		}
		elseif($sql_non_unique== 0)
		{
			if($list_unique=="")
				$list_unique=" UNIQUE  `$sql_column_name` (`$sql_key_name`) ";
			else
				$list_unique=$list_unique.", `$sql_column_name` (`$sql_key_name`) ";
		}
		else
		{
			if($list_key=="")
				$list_key=" KEY  `$sql_column_name` (`$sql_key_name`) ";
			else
				$list_key=$list_key.", KEY `$sql_column_name` (`$sql_key_name`) ";
		}
	}
	
	if($list_primary!="")
		$list_primary=$list_primary." ) ";

	if($list_primary!="")
		$chaine_create=$chaine_create.",    ".$list_primary;
	if($list_unique!="")
		$chaine_create=$chaine_create.",    ".$list_unique;
	if($list_key!="")
		$chaine_create=$chaine_create.",    ".$list_key;	
	
	$chaine_create=$chaine_create." ) TYPE=MyISAM ;\n#\n";

	return($chaine_drop.$chaine_create);

} 


// recup des data d'une table sous forme de INSERT ...
function get_table_data($table, $mysql_link, $DEBUG=FALSE)
{
	
	$chaine_data="";
	
	// suppression des donn��es de la table :
	$chaine_delete="DELETE FROM `$table` ;\n";
	$chaine_data=$chaine_data.$chaine_delete ;
	
	// recup des donn��es de la table :
	$sql_data="SELECT * FROM $table";
	$ReqLog_data = requete_mysql($sql_data, $mysql_link, "get_table_data", $DEBUG);
	
	while ($resultat_data = mysql_fetch_array($ReqLog_data))
	{
		$count_fields=count($resultat_data)/2;   // on divise par 2 car c'est un tableau index� (donc compte key+valeur)
		$chaine_insert = "INSERT INTO `$table` VALUES ( ";
		for($i=0; $i<$count_fields; $i++)
		{
			if(isset($resultat_data[$i]))
				$chaine_insert = $chaine_insert."'".addslashes($resultat_data[$i])."'";
			else
				$chaine_insert = $chaine_insert."NULL";
				
			if($i!=$count_fields-1)
				$chaine_insert = $chaine_insert.", ";
		}
		$chaine_insert = $chaine_insert." );\n";
		
		$chaine_data=$chaine_data.$chaine_insert;
	}

	return $chaine_data;
}

?>
