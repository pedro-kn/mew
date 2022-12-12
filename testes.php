<?php
$count = 0;
$countarray = array(0,1,1,1,1,1);
//while($count<10){
    foreach($countarray as $s){
        $count = $count + 1;
        //$countarray[$count] = $countarray[$count-1] + $count;
        echo $count;
    }    
//}


?>