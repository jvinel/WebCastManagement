[<?php 
    $cpt=0;
    foreach ($results as $result) { 
        if ($cpt>0) {
            echo ",";
        }
        echo "[" . $result[0]["timevalue"] . "," . $result[0]["connected"] . "]";
        $cpt++;
    } 
?>]