<?php
require_once "class/user.php";
require_once "class/Auth.php";
require_once "class/Transaction.php";

$auth = new Auth;
$transaction = new Transaction;

if (isset($_POST['register'])) {
    unset($_POST['register']);

    if ($_POST['password'] === $_POST['confirm_password']) {
        unset($_POST['confirm_password']);
        $auth->register($_POST);
    }
}
if (isset($_POST['login'])) {
    unset($_POST['login']);
    $auth->login($_POST);
}
if (isset($_POST['get_transaction'])) {
    unset($_POST['get_transaction']);
    if (isset($_POST['userid'])) {
        $user_id = ['user_id' => $_POST['userid']];
        $transaction->select("*", $user_id);
    }
    $datas = [];
    while ($row = $transaction->res->fetch_assoc()) {
        array_push($datas, $row);
    }
    echo json_encode($datas);
}
if (isset($_POST['get_profile'])) {
    if (isset($_POST['userId'])) {
        $user_id = ['id' => $_POST['userId']];
        $auth->select("*", $user_id);
        $datas = [];
        while ($row = $auth->res->fetch_assoc()) {
            array_push($datas, $row);
        }
        echo json_encode($datas);
    }
}
if(isset($_POST['update_profile'])){
    unset($_POST['update_profile']);
    $auth->update([...$_POST]);
}
if(isset($_GET['get_transaction'])){
    unset($_GET['get_transaction']);
    $transaction->filter("*", [...$_GET]);
    $datas = [];
    while ($row = $transaction->res->fetch_assoc()) {
        array_push($datas, $row);
    }
    echo json_encode($datas);
}
if(isset($_POST['export_csv'])){
    unset($_POST['export_csv']);
    $transaction->exportCSV("type, amount, status, date", [...$_POST]);
}
