<?php


//$mes = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "GMT-0300 (Horário Padrão de Brasília)", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
//$mesn = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "", "", "", "", "", "", "", "");

$mes = array("GMT-0300 (Horário Padrão de Brasília)");
$mesn = array("");

$start2 = str_replace($mes, $mesn, 'Tue Dec 13 2022 00:00:00 GMT-0300 (Horário Padrão de Brasília)');
$start1 = strtotime($start2);
$start = date("Y-m-d\TH:i",$start1);

$end2 = str_replace($mes, $mesn, 'Tue Dec 13 2022 06:00:00 GMT-0300 (Horário Padrão de Brasília)');
$end1 = strtotime($end2);
$end = date("Y-m-d\T\H:i",$end1);

echo $start;
echo $end;


//echo date("Y-m-d H:i:s",strtotime($start2));

?>