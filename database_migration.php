<?php
require_once 'lib/database_connection.php';
mysqli_query($connection, 'drop table if exists currency');
mysqli_query($connection, 'create table currency (valuteID varchar(6), numCode varchar(3), charCode varchar(3), name varchar(50) character set utf8, value float, date date, unique key id_all(valuteID,numCode,charCode,name,value,date))');
for($i=0;$i<30;$i++) {
    echo "load day -$i\n";
    flush();
    $curl = curl_init();
    $date = date('d/m/Y', strtotime("-$i days"));
    curl_setopt($curl, CURLOPT_URL, "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$date");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $currency = curl_exec($curl);
    $currency = simplexml_load_string($currency);
    $date = date('Y-m-d', strtotime($currency['Date']));
    foreach ($currency->children() as $currency_item) {
        $valuteID = $currency_item['ID'];
        $numCode = $currency_item->NumCode;
        $charCode = $currency_item->CharCode;
        $name = $currency_item->Name;
        $value = str_replace(',', '.', $currency_item->Value);
        mysqli_query($connection, "insert ignore into currency(valuteID,numCode,charCode,name,value,date) values('$valuteID','$numCode','$charCode','$name',$value,'$date')");
    }
}
echo 'data loaded';