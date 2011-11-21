<?php

defined( '_PHP_CONGES' ) or die( 'Restricted access' );

class SQL extends mysqli
{
    private static $instance;

    private function __construct ( $host='localhost', $username='root', $passwd ='',$dbname = 'db_conges')
    {
		parent :: __construct (  $host , $username , $passwd , $dbname );
		$this->query('SET NAMES \'utf8\';');
    }

    public static function singleton()
    {
        if (!isset(self::$instance)) 
		{
            $className = __CLASS__;
			include __DIR__ .'/../dbconnect.php';
			
            self::$instance = new $className( $mysql_serveur , $mysql_user, $mysql_pass, $mysql_database);
        }
        return self::$instance;
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

    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
	
	public function escape( $escapestr )
	{
		return $this->escape_string( $escapestr );
	}

    public function __wakeup()
    {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }
}

// $l = SQL::singleton();
// $result = $l->query('SHOW TABLES');
// while ($ligne = $result->fetch_assoc())
// {
	// print_r($ligne);
// }


?>