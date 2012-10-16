<?php

$local_scripts=curPage();

if($local_scripts[0] == "hr_index.php")
 {
    $pattern="&onglet=";
    $which_onglet=explode($pattern,$local_scripts[1]);
    if(empty($which_onglet) || $which_onglet[1] == "page_principale" || $which_onglet[0] == $local_scripts[1])
        {
        $select_all_cet = "SELECT u_nom,u_prenom,pc_jours_demandes FROM conges_users,conges_plugin_cet WHERE `conges_users`.`u_login`=`conges_plugin_cet`.`pc_u_login`";
        $exec_all_cet = SQL::query($select_all_cet);
        echo "<script>
        $(document).ready(function(){
            $('th:last-child').after('<th>CET</th>');";
        if($exec_all_cet->num_rows !=0)
            {
            while($user_cet=$exec_all_cet->fetch_array())
                {
                echo "
                var tableRow = $('tr:has(td:contains(\"".$user_cet['u_nom']."\")):has(td:contains(\"".$user_cet['u_prenom']."\"))');
                tableRow.css('color','blue');
                tableRow.append('<td>".$user_cet['pc_jours_demandes']."</td>');
                ";
                }
            }

        echo "    });
        </script>";
        }
 }


?>
