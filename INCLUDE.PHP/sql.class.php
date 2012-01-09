<?php

defined( '_PHP_CONGES' ) or die( 'Restricted access' );




// class SQL, interface with mysqli, it's a singleton, non-static method can be call staticly
// Build for PHP 5.3
class SQL
{
	// singleton
	private static $instance;
	
	// warper obj
	private static $pdo_obj;

	//=====================
	// singleton
	//=====================
	
	// singleton pattern, code from php.net
	// fucking parameters ... I don't find a way to use $args and call construtor with it ...
	public static function singleton() {
		if (!isset(self::$instance)) {
            $className = __CLASS__;
			include __DIR__ .'/../dbconnect.php';
			
            self::$instance = new $className( $mysql_serveur , $mysql_user, $mysql_pass, $mysql_database);
		}
		return self::$instance;
	}
	
	private function __construct() {
		$args = func_get_args();
		// this doesn't work ... need use ReflectionClass ... BEURK ! ReflectionClass is not documented ... unstable
		// self::$pdo_obj = call_user_func_array('Database::__construct', $args);
		$r = new ReflectionClass('Database');
		self::$pdo_obj = $r->newInstanceArgs($args);
	}

	// singleton pattern, code from php.net
	public function __clone() { error_handler('Clone is not allowed.', E_USER_ERROR); }
	
	// singleton pattern, code from php.net
	public function __wakeup() { error_handler('Unserializing is not allowed.', E_USER_ERROR); }

	// for call staticly dynamic fx (doesn't use instance vars and doesn't use singleton ;-) )
	public static function __callStatic($name, $args) {
		self::singleton();
		if (method_exists(self::$instance, $name))
			return call_user_func_array(array(self::$instance, $name), $args);
		elseif (method_exists(self::$pdo_obj, $name))
			return call_user_func_array(array(self::$pdo_obj, $name), $args);
		else
			throw new Exception(sprintf('The required method "%s" does not exist for %s', $name, get_class(self::$instance))); 
    }	
	
	//=====================
	// warper
	//=====================
	
	// isset on the warped obj
    public function __isset($name) {
		return isset(self::$pdo_obj->$name);
    }
	
	// get on the warped obj
    public function __get($name) {
		return self::$pdo_obj->$name;
    }
	
	// isset on the warped obj
    public function __set($name, $value) {
		self::$pdo_obj->$name = $value;
    }
	
	// unset on the warped obj
	public function __unset($name) {
		unset(self::$pdo_obj->$name);
	}
	   
	// call on the warped obj
	public function __call($name, $args) {
		return call_user_func_array(array(self::$pdo_obj, $name), $args);
	}
	
	// call on the warped obj
	public static function getVar($name) {
		return self::$pdo_obj->$name;
	}
}


class Database extends mysqli
{
    public function __construct ( $host='localhost', $username='root', $passwd ='',$dbname = 'db_conges')
    {
		parent::__construct (  $host , $username , $passwd , $dbname );
		$this->query('SET NAMES \'utf8\';');
    }

    public function query( $query , $resultmode = MYSQLI_STORE_RESULT )
    {
		$result = parent::query($query, $resultmode);
		if ($this->errno != 0)
		{
			echo '<div><table>';
			echo '<thead><tr><th>#</th><th>FX</th><th>line</th><th>file</th><th>class</th><th>object</th><th>type</th><th>args</th></tr></thead>';
			echo '<tbody>';
			$backtraces = debug_backtrace();
			foreach( $backtraces as  $k => $b )
			{
				echo '<tr><td>'.($k +1).'</td><td>'.$b['function'].'</td><td>'.$b['line'].'</td><td>'.$b['file'].'</td><td>'.(isset($b['class'])?$b['class']:'').'</td><td>';
				if (isset($b['object']))
					var_dump($b['object']);
				echo '</td><td>'.(isset($b['type'])?$b['type']:'').'</td><td>';
				if (isset($b['args']))
					var_dump($b['args']);
				echo '</td></tr>';
			
			}
			
			echo '</tbody></table>';
			echo '<hr/>'."\n".'Requete SQL = '.$query."\n".'<hr/>'."\n";
			throw new Exception('Erreur : '.$this->error );
		}
		return $result;
    }
	
	public function quote( $escapestr )
	{
		return $this->escape_string( $escapestr );
	}
}
