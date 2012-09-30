<?php
define('PLUGINS_DIR', __DIR__ . "/plugins/");


function include_plugins(){
        $my_plugins = scandir(PLUGINS_DIR);
	foreach($my_plugins as $dir)
        {
	if(is_dir(PLUGINS_DIR."/$dir") && $dir != "." && $dir != "..")
            {
	    foreach(glob(PLUGINS_DIR."/$dir/*.php") as $filename)
                { include_once $filename; }
	    }
        }
}

//massive include for plugins...
include_plugins();


?>
