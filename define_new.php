<?php


defined( '_PHP_CONGES' ) or die( 'Restricted access' );
defined( 'ROOT_PATH' ) or die( 'ROOT_PATH not defined !' );

if (!defined( 'DEFINE_INCLUDE' )) {
	define('DEFINE_INCLUDE',	true);
	define('SHOW_SQL',			false);
	
	define('LIBRARY_PATH',		ROOT_PATH . 'library/');
	define('INCLUDE_PATH',		ROOT_PATH . 'include/');
	define('CONFIG_PATH',		ROOT_PATH . 'cfg/');
	define('INSTALL_PATH',		ROOT_PATH . 'install/');
	define('LOCALE_PATH',		ROOT_PATH . 'locale/');
	define('DUMP_PATH',			ROOT_PATH . 'dump/');
	define('TEMPLATE_PATH',		ROOT_PATH . 'template/default/');
}