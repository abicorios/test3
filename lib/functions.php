<?php
function er($m){
    die(json_encode(['error'=>$m]));
}
function out($o){
    echo json_encode(['result'=>$o,'error'=>false]);
    exit;
}

function is_key($k,$m){
    return in_array($k,array_keys($m));
}

function diff_days($d1,$d2){
    $d1=date_create($d1);
    $d2=date_create($d2);
    return date_diff($d1,$d2)->format('%a');
}

function validate_date($d){
    if(!strtotime($d))
        er("$d is not a valid date");
    return $d;
}
function format_date($d){
    return date('Y-m-d', strtotime($d));
}
function get_max_date($d){
    global $connection;
    $tmp=$d;
    $d=mysqli_query($connection,"select max(date) from currency where date <= '$d'")->fetch_all()[0][0];
    diff_days($tmp,$d);
    if($d && diff_days($tmp,$d)<=10)
        return $d;
    else
        er("$tmp is not presented");
}
function process_date($d){
    return get_max_date(format_date(validate_date($d)));
}

function validate_valueID($id){
    if(preg_match('!\W!',$id))
        er("$id is some sql injection try");
    global $connection;
    $check=mysqli_query($connection,"select valuteID from currency where valuteID='$id' limit 1")->fetch_all()[0][0];
    if($check)
        return $id;
    else
        er("valuteID $id is not exist");
}