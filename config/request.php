<?php
    require_once "class/user.php";
    require_once "class/Auth.php";

    $auth = new Auth;

    if(isset($_POST['register'])){
        unset($_POST['register']);

        if($_POST['password'] === $_POST['confirm_password']){
            unset($_POST['confirm_password']);
            $auth->register($_POST);
        }
    }
    if(isset($_POST['login'])){
        unset($_POST['login']);
        $auth->login($_POST);
    }
?>