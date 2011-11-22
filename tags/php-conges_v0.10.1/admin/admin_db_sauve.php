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

include("../config.php") ;
include("../config_ldap.php");
include("../fonctions_conges.php") ;
include("../INCLUDE.PHP/fonction.php");
include("../INCLUDE.PHP/session.php");
if($config_verif_droits==TRUE){ include("../INCLUDE.PHP/verif_droits.php");}


	/*** initialisation des variables ***/
	$choix_action="";
	$type_sauvegarde="";
	$commit="";
	$fichier_restaure_name="";
	$fichier_restaure_tmpname='';
	$fichier_restaure_size=0;
	$fichier_restaure_error=4;
	/************************************/

	/*************************************/
	// recup des parametres reçus :
	// SERVER
	$PHP_SELF=$_SERVER['PHP_SELF'];
	// GET
	if(isset($_GET['choix_action'])) { $choix_action=$_GET['choix_action']; }
	if(isset($_GET['type_sauvegarde'])) { $type_sauvegarde=$_GET['type_sauvegarde']; }
	if(isset($_GET['commit'])) { $commit=$_GET['commit']; }
	// POST
	if( (!isset($choix_action)) || ($choix_action=="") )
		if(isset($_POST['choix_action'])) { $choix_action=$_POST['choix_action']; }
	if( (!isset($type_sauvegarde)) || ($type_sauvegarde=="") )
		if(isset($_POST['type_sauvegarde'])) { $type_sauvegarde=$_POST['type_sauvegarde']; }
	//if(isset($_POST['fichier_restaure'])) { $fichier_restaure=$_POST['fichier_restaure']; }
	if(isset($_FILES['fichier_restaure'])) 
	{
		$fichier_restaure_name=$_FILES['fichier_restaure']['name']; 
		$fichier_restaure_size=$_FILES['fichier_restaure']['size']; 
		$fichier_restaure_tmpname=$_FILES['fichier_restaure']['tmp_name']; 
		$fichier_restaure_error=$_FILES['fichier_restaure']['error']; 
		//print_r($_FILES);
	}
	/*************************************/


	
	if($choix_action=="")
		choix();
	elseif($choix_action=="sauvegarde")
	{
		if( (!isset($type_sauvegarde)) || ($type_sauvegarde=="") )
			choix_sauvegarde();
		else
		{
			if( (!isset($commit)) || ($commit=="") )
				sauve($type_sauvegarde);
			else
				commit_sauvegarde($type_sauvegarde);
		}
	}
	elseif($choix_action=="restaure")
	{
		if( (!isset($fichier_restaure_name)) || ($fichier_restaure_name=="")||(!isset($fichier_restaure_tmpname)) || ($fichier_restaure_tmpname=="") )
			choix_restaure();
		else
			restaure($fichier_restaure_name, $fichier_restaure_tmpname, $fichier_restaure_size, $fichier_restaure_error);
	}
	else
		/* APPEL D'UNE AUTRE PAGE immediat */
		echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=admin_index.php?session=$session&onglet=admin-users\">";




/**********  FONCTIONS  ****************************************/

// CHOIX
function choix()
{
	global $PHP_SELF;
	global $session;
	
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Sauvegarde / Restauration de la Base de données</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th colspan=\"2\">Choisissez :</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td><input type=\"radio\" name=\"choix_action\" value=\"sauvegarde\" checked></td>\n";
	echo "<td><b> Sauvegarder</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td><input type=\"radio\" name=\"choix_action\" value=\"restaure\" /></td>\n";
	echo "<td><b> Restaurer</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	&nbsp;\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"submit\" value=\"Valider\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Abandonner\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


// SAUVEGARDE
function choix_sauvegarde()
{
	global $PHP_SELF;
	global $session;

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Sauvegarde / Restauration de la Base de données</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th colspan=\"2\">Options de Sauvegarde</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td><input type=\"radio\" name=\"type_sauvegarde\" value=\"all\" checked></td>\n";
	echo "	<td>Sauvegarde complète</td>\n";
	echo "</tr>\n";
/*	echo "<tr>\n";
	echo "	<td><input type=\"radio\" name=\"type_sauvegarde\" value=\"structure\"></td>\n";
	echo "	<td>Sauvegarde de la structure seule</td>\n";
	echo "</tr>\n";
*/
	echo "<tr>\n";
	echo "	<td><input type=\"radio\" name=\"type_sauvegarde\" value=\"data\"></td>\n";
	echo "	<td>Sauvegarde des données seules</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	&nbsp;\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td colspan=\"2\" align=\"center\">\n";
	echo "		<input type=\"hidden\" name=\"choix_action\" value=\"sauvegarde\">\n";
	echo "		<input type=\"submit\" value=\"Démarrer la sauvegarde\">\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Abandonner\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}

function sauve($type_sauvegarde)
{
	global $PHP_SELF;
	global $session;
	

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	// on renvoit immédiatement sur la meme page qui va lancer la sauvegarde ...
	echo "<meta http-equiv=\"refresh\" content=\"0;url=$PHP_SELF?session=$session&choix_action=sauvegarde&type_sauvegarde=$type_sauvegarde&commit=ok\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Sauvegarde / Restauration de la Base de données</h1>\n";

	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th colspan=\"2\">Sauvegarde effectuée ...</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\" align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Fermer cette Fenêtre\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";
}

function commit_sauvegarde($type_sauvegarde)
{
	global $PHP_SELF;
	global $session;
	
	//connexion mysql
	$link = connexion_mysql() ;
	
	header("Pragma: no-cache");
	header("Content-Type: text/x-delimtext; name=\"php_conges_".$type_sauvegarde.".sql\"");
	header("Content-disposition: attachment; filename=php_conges_".$type_sauvegarde.".sql");

	//
	// Build the sql script file...
	//
	$maintenant=date("d-m-Y H:i:s");
	echo "#\n";
	echo "# PHP_CONGES\n";
	echo "# Database : $dbname\n";
	echo "#\n# DATE : $maintenant\n";
	echo "#\n";
	
	//recup de la liste des tables
	$sql="SHOW TABLES";
	$ReqLog = mysql_query($sql, $link) or die("ERREUR : ".$sql."<br>\n".mysql_error());
	while ($resultat = mysql_fetch_array($ReqLog))
	{
		$table=$resultat[0] ;
	
		echo "#\n#\n# TABLE: $table \n#\n";
		if(($type_sauvegarde=="all") || ($type_sauvegarde=="structure") )
		{
			echo "# Struture : \n#\n";
			echo get_table_structure($table, $link);
		}
		if(($type_sauvegarde=="all") || ($type_sauvegarde=="data") )
		{
			echo "# Données : \n#\n";
			echo get_table_data($table, $link);
		}
	}
	
	mysql_close($link);

}


// RESTAURATION
function choix_restaure()
{
	global $PHP_SELF;
	global $session;

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Sauvegarde / Restauration de la Base de données</h1>\n";

//	echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<form enctype=\"multipart/form-data\" action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Restauration de la base de données<br>Fichier à restaurer :</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
//	echo "<td> <input type=\"file\" name=\"fichier_restaure\" size=\"30\"> </td>\n";
	echo "<td align=\"center\"> <input type=\"file\" name=\"fichier_restaure\"> </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\"> <font color=\"red\">ATTENTION : toutes les données de la database php_conges vont être écrasées avant la restauration !</font> </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"center\">\n";
	echo "		<input type=\"hidden\" name=\"choix_action\" value=\"restaure\">\n";
	echo "		<input type=\"submit\" value=\"Lancer la Restauration\">\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "	<input type=\"button\" value=\"Abandonner\" onClick=\"javascript:window.close();\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";

}


function restaure($fichier_restaure_name, $fichier_restaure_tmpname, $fichier_restaure_size, $fichier_restaure_error)
{
	global $PHP_SELF;
	global $session;

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"../style_basic.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<title>PHP_CONGES : administration</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>\n";
	echo "<h1>Sauvegarde / Restauration de la Base de données</h1>\n";
	
//	if( (!isset($fichier_restaure)) || ($fichier_restaure == "") || (!file_exists($fichier_restaure)) )
//	if( (!isset($fichier_restaure_tmpname)) || ($fichier_restaure_tmpname == "") || (!is_uploaded_file($fichier_restaure_tmpname)) )
	if( ($fichier_restaure_error!=0)||($fichier_restaure_size==0) ) // s'il y a eu une erreur dans le telechargement OU taille==0
	//(cf code erreur dans fichier features.file-upload.errors.html de la doc php)
	{
		//message d'erreur et renvoit sur la page précédente (choix fichier)

		echo "<form action=\"$PHP_SELF?session=$session\" method=\"POST\">\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<th>le Fichier indiqué inexistant : <br>$fichier_restaure_name</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "	<input type=\"hidden\" name=\"choix_action\" value=\"restaure\">\n";
		echo "	<input type=\"submit\" value=\"Recommencer\">\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "	<input type=\"button\" value=\"Abandonner\" onClick=\"javascript:window.close();\">\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";

	}
	else
	{
		//connexion mysql
		$link = connexion_mysql() ;
		
		//affichage du contenu :
		//readfile($fichier_restaure_tmpname);
		
		// on lit le fichier et on met chaque ligne dans un tableau
		$tab_lines = file ($fichier_restaure_tmpname);
		// puis parcourt du tableau :
		// si la ligne n'est pas un commentaire (commence par # (après avoir enlevé les espaces de debut de chaine))
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
				$ReqLog = mysql_query($sql, $link) or die("ERREUR : RESTAURATION : <br>\n $sql<br>\n<br>\n".mysql_error());
			}
		}
		
		mysql_close($link);
	
		echo "<form action=\"\" method=\"POST\">\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<th>Restauration effectuée avec succés !</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">&nbsp;</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align=\"center\">\n";
		echo "	<input type=\"button\" value=\"Fermer cette Fenêtre\" onClick=\"javascript:window.close();\">\n";
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
function get_table_structure($table, $mysql_link)
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
	// genérer un PRIMARY KEY ('key1'), PRIMARY KEY ('key2', ...) 
	// puis on regarde ceux qui ne sont pas PRIMARY et on regarde s'ils sont UNIQUE ou pas et
	// on génére une liste= UNIQUE 'key1' ('key1') , 'key2' ('key2') , .... 
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
function get_table_data($table, $mysql_link)
{
	
	$chaine_data="";
	
	// suppression des donnéées de la table :
	$chaine_delete="DELETE FROM `$table` ;\n";
	$chaine_data=$chaine_data.$chaine_delete ;
	
	// recup des donnéées de la table :
	$sql_data="SELECT * FROM $table";
	$ReqLog_data = mysql_query($sql_data, $mysql_link) or die("ERREUR : get_table_data() <br>\n".mysql_error());
	
	while ($resultat_data = mysql_fetch_array($ReqLog_data))
	{
		$count_fields=count($resultat_data)/2;   // on divise par 2 car c'est un tableau indexé (donc compte key+valeur)
		$chaine_insert = "INSERT INTO `$table` VALUES ( ";
		for($i=0; $i<$count_fields; $i++)
		{
			if(isset($resultat_data[$i]))
				$chaine_insert = $chaine_insert."'".$resultat_data[$i]."'";
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
