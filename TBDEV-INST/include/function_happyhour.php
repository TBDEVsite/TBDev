<?php
//made by putyn @tbdev 06.11.2008
function happyHour($action) {
global $CACHE ;
//generate happy hour
if ($action == "generate")
{
$nextDay = date("Y-m-d" , time()+86400 );
$nextHoura = mt_rand(0,2);
if ($nextHoura == 2)
$nextHourb = mt_rand(0,3);
else
$nextHourb = mt_rand(0,9);
$nextHour = $nextHoura.$nextHourb;

$nextMina = mt_rand(0,5);
$nextMinb = mt_rand(0,9);
$nextMin = $nextMina.$nextMinb;

$happyHour = $nextDay." ".$nextHour.":".$nextMin."";

return $happyHour;
}

$file = "$CACHE/happyhour.txt";
$happy = unserialize(file_get_contents($file));
$happyHour = strtotime($happy["time"]);

$happyDate = $happyHour;
$curDate = time();
$nextDate= $happyHour + 3600;
//action check
if ($action == "check"){
if ($happyDate < $curDate && $nextDate >= $curDate )
return true;
}
//action time left
if ($action == "time"){
$timeLeft = mkprettytime(($happyHour + 3600)-time());
$timeLeft = explode(":",$timeLeft);
$time = ($timeLeft[0]." min : ".$timeLeft[1]." sec");
return $time;
}

//this will set all torrent free or just one category
if ($action == "todo")
{
$act = rand(1,2);
if ($act == 1)
$todo = 255; // this will mean that all the torrent are free
elseif ($act == 2)
$todo = rand(1,16); // only one cat will be free || remember to change the number of categories i have 16 but you may have more

return $todo ;
}
//this will generate the multiplier so every torrent downloaded in the happy hour will have upload multiplied but this
if ($action == "multiplier")
{
$multiplier = rand(11,55)/ 10; //max value of the multiplier will be 5,5 || you could change it to a higher or a lower value
return $multiplier;
}
}


function happyCheck($action, $id=NUll){
global $CACHE;
$file = "$CACHE/happyhour.txt";
$happy = unserialize(file_get_contents($file));
$happycheck = $happy["catid"];
if ($action == "check")
return $happycheck;

if ( $action == "checkid" && (($happycheck == "255") || $happycheck == $id ))
return true;
}

function happyFile($act){
global $CACHE;
$f = "$CACHE/happyhour.txt";
$happy = unserialize(file_get_contents($f));

if ($act == "set"){
$array_happy = array (
'time' => happyHour("generate"),
'status' => '1',
'catid' => happyHour("todo")
);
} elseif ($act == "reset") {
$array_happy = array (
'time' => $happy["time"],
'status' => '0',
'catid' => $happy["catid"]
);
}
$array_happy = serialize($array_happy);
$f = "$CACHE/happyhour.txt";
$file = fopen($f, 'w');
ftruncate($file, 0);
fwrite($file, $array_happy);
fclose($file);
}

function happyLog($userid,$torrentid, $multi)
{
$time = sqlesc(time());
mysql_query("INSERT INTO happylog (userid, torrentid,multi, date) VALUES($userid, $torrentid, $multi, $time)") or sqlerr(__FILE__, __LINE__);
}
?>