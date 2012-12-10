<?php


include ROOT_PATH . 'define.php';

function find_plugins_activated(){
        $list_plugins = array();
        if($_SESSION['config']['php_conges_version'] == "1.7.0")
            {
            $plugins_inst_activ_query = "SELECT p_name FROM conges_plugins WHERE p_is_active = 1 AND p_is_install = 1;";
            $ReqLog_list_plugins = SQL::query($plugins_inst_activ_query);
            if($ReqLog_list_plugins->num_rows !=0){
                while($plugin=$ReqLog_list_plugins->fetch_array())
                {
                    array_push($list_plugins, $plugin["p_name"]);
                }
            }
        return $list_plugins;
        }
}

function include_plugins($plugins_activated){
    $my_plugins = scandir(PLUGINS_DIR);
    $to_include = array();
    foreach($my_plugins as $dir)
    {
    if(is_dir(PLUGINS_DIR."/$dir") && !preg_match("/^\./",$dir))
        {
        if(in_array($dir, $plugins_activated))
            {
            if(file_exists(PLUGINS_DIR."/$dir/allfilestoinclude.php")) include(PLUGINS_DIR."/$dir/allfilestoinclude.php");
            }
        }
    }
}


$plugins_activated = find_plugins_activated();

//massive include for plugins...
include_plugins($plugins_activated);

