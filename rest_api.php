<?php

require_once 'lib/database_connection.php';
require_once 'lib/check_auth.php';
require_once 'lib/functions.php';

$host=$_SERVER['HTTP_HOST'];

if(($method=$_SERVER['REQUEST_METHOD'])!='GET')
    er("$method is not implemented, use GET");

$help=['help'=>[
    'help'=>"For some help use the get request http://$host/api/help or http://$host/api",
    'max_date'=>"For a max date use the get request http://$host/api/max_date",
    'min_date'=>"For a min date use the get request http://$host/api/min_date",
    'list'=>"For the list of valutes use the get request http://$host/api/list",
    'valueID'=>"valueID use for example in the get request http://$host/api/valueID/R01010/date/01.01.2020 or http://$host/api/valueID/R01010/date_from/01.01.2020/date_to/10.01.2020",
    'date'=>"date use for example in the get request http://$host/api/valueID/R01010/date/01.01.2020",
    'date_from'=>"date_from use for example in the get request http://$host/api/valueID/R01010/date_from/01.01.2020/date_to/10.01.2020",
    'date_to'=>"date_to use for example in the get request http://$host/api/valueID/R01010/date_from/01.01.2020/date_to/10.01.2020"
]];

$request=$_SERVER['REQUEST_URI'];
if(preg_match('!^/api(/help)?/?$!',$request))
    out($help);
$request=preg_replace('!^/api/|/$!','',$request);
$request_words=explode('/',$request);
$request_map=[];
for($i=0;$i<count($request_words);$i+=2){
    $request_map[$request_words[$i]]=$request_words[$i+1];
}

if(is_key('max_date',$request_map)){
    $max_date=mysqli_query($connection,"select max(date) from currency")->fetch_all()[0][0];
    out(['max_date'=>$max_date]);
}
if(is_key('min_date',$request_map)){
    $min_date=mysqli_query($connection,"select min(date) from currency")->fetch_all()[0][0];
    out(['min_date'=>$min_date]);
}
if(is_key('list',$request_map)){
    $list=mysqli_query($connection,"select distinct valuteID,name,charCode from currency")->fetch_all(MYSQLI_ASSOC);
    $list_map=[];
    foreach ($list as $item){
        $list_map[$item['valuteID']]=$item;
        unset($list_map[$item['valuteID']]['valuteID']);
    }
    out($list_map);
}


$valueID=$request_map['valueID'];
$date_from=$request_map['date_from'];
$date_to=$request_map['date_to'];
$date=$request_map['date'];

if($valueID && (($date_from && $date_to) || $date)){
    $valueID=validate_valueID($valueID);
//    $bound_dates='';
    if($date){
        $date=process_date($date);
        $bound_dates="date = '$date'";
    }
    else {
        $date_to=process_date($date_to);
        $date_from=process_date($date_from);
        $bound_dates="date >= '$date_from' and date <= '$date_to";
    }
    $rates=mysqli_query($connection,"select date,value from currency where valuteID = '$valueID' and $bound_dates")->fetch_all(MYSQLI_ASSOC);
    $date_value=[];
    foreach($rates as $rate){
        $date_value[$rate['date']]=$rate['value'];
    }
    out(['valueID'=>$valueID,'rates'=>$date_value]);
} else {
    er("Incorrect request. See a format at http://$host/api/help");
}