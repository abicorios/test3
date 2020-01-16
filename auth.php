<?php
$password=md5($_POST['password']);
unset($_POST['password']);
$login=$_POST['login'];
unset($_POST['login']);
require_once 'lib/session.php';
if($login==='admin' && $password==='9f4ea23aaa08087da721670ae4c448c9'){
    $_SESSION['authorized']=1;
    header('Location: http://'.$_SERVER['HTTP_HOST']);
} else {
    unset($_SESSION['authorized']);
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/auth.html');
}