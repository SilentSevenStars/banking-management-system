<?php
    require_once "User.php";

    class Auth extends User{
        private $userTable = "users";

        public function register($data)
        {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("SELECT * FROM $this->userTable WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $data['username'], $data['email']);
            $stmt->execute();
            $this->res = $stmt->get_result();

            if($this->res->num_rows > 0){
                die("Username or Email are already taken");
            } else {
                $stmt->close();
                $this->insert($data);
                header("Location: ../login.php");
            }
        }

        public function login($data)
        {
            $stmt = $this->conn->prepare("SELECT * FROM $this->userTable WHERE username = ?");
            $stmt->bind_param("s", $data['username']);
            $stmt->execute();
            $this->res = $stmt->get_result();

            if($this->res->num_rows > 0){
                $row = $this->res->fetch_assoc();
                if(password_verify($data['password'], $row['password'])){
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['fullname'] = $row['fullname'];
                    header("Location: ../index.php");
                } else {
                    die("Username or password are incorrect");
                }
            } else{
                die("Username or password are incorrect");
            }
        }
        public function logout()
        {
            session_start();
            session_destroy();
            header("Location: login.php");
        }
    }
?>