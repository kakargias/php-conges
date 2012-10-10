<?php

$local_scripts=curPage();

if($local_scripts[0] == "hr_index.php")
 {
    $pattern="&onglet=";
    $which_onglet=explode($pattern,$local_scripts[1]);
    if(empty($which_onglet) || $which_onglet[1] == "page_principale" || $which_onglet[0] == $local_scripts[1])
        {

        $text = 'nb days';
        //echo $text;
        echo "<script>
        $(document).ready(function(){
            $('th:last-child').after('<th>CET</th>');
            $('tr').each(function(){
                if($(this).children().is('td'))
                    {
                    name = $(this).children('td:first').text();
                    $(this).append('<td>$name</td>');
                    }
            });
        });
        </script>";
        }
 }


?>
