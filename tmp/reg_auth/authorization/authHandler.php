<?php
ini_set('display_errors', E_ALL);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/user/User.php';


use core\user\User;


$login = $_POST['loginAuth'];
$password = $_POST['passwordAuth'];

if (!empty($login) && !empty($password)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/db/db_conn.php';

    $user = new User(
        login: $login,
        password: $password,
        conn: $conn
    );

    if($user->UserCheck()){
        $_SESSION['login'] = $login;
        header('Location: /');
        die();
    }else {
        header('Location: /?auth=false&auth_err=true');
        die();
    }

}else {
   header('Location: /?auth=false&auth_err=true');
   die();
}