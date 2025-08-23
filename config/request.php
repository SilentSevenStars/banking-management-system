<?php
    require_once "class/user.php";

    $user = new User;

    if(isset($_POST['register'])){
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if($password !== $confirm_password){
            die("Password do not match");
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $check = $conn->query("SELECT * FROM users WHERE username = '$username' OR email='$email'");
            if($check->num_rows > 0){
                die("Username or Email already exists");
            }
            $sql = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$hashed_password')";
        }
    }
    if(isset($_POST['login'])){
        $password = $_POST['password'];

        $user->select("*", $_POST['username']);

        if($user->res->num_rows === 1){
            $result = $user->res->fetch_assoc();
            if(password_verify($password, $result['password'])){
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Incorrect email or password";
            }
        } else {
            $_SESSION['error_message'] = "Incorrect email or password";
        }
    }
?>