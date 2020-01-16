<?php
require_once 'session.php';
if(!$_SESSION['authorized']){
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/auth.html');
    exit;
}

//    echo "Location: http://".$_SERVER['HTTP_HOST'].'/auth.html';