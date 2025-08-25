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
if(isset($_POST['get_info'])){
    unset($_POST['get_info']);
    $auth->select("*",[...$_POST]);
    $datas = [];
    while($row = $auth->res->fetch_assoc()){
        array_push($datas, $row);
    }
    echo json_encode($datas);
}
if(isset($_POST['deposit'])){
    unset($_POST['deposit']);
    $auth->update([
        'balance' => $_POST['balance'],
        'id'      => $_POST['user_id']
    ]);
    unset($_POST['balance']); 
    $transaction->insert([...$_POST]);
}

if(isset($_POST['withdraw'])){
    unset($_POST['withdraw']);
    $auth->update([
        'balance' => $_POST['balance'],
        'id'      => $_POST['user_id']
    ]);
    unset($_POST['balance']); 
    $transaction->insert([...$_POST]);
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
if (isset($_POST['update_profile'])) {
    unset($_POST['update_profile']);

    if (isset($_POST['password']) && $_POST['password'] === '') {
        unset($_POST['password']);
    } elseif (isset($_POST['password']) && $_POST['password'] !== '') {
        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

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
