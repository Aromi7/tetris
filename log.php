<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    ob_start();
    session_start();

    $servername = "sql6.freesqldatabase.com";
    $username = "sql6440216";
    $password = "sLj9jRX9ns";
    $dbname = "sql6440216";

    $conn = new mysqli($servername, $username, $password, $dbname);

    //register
    if(isset($_POST['uemail']) && isset($_POST['uname']) && isset($_POST['upass'])){
        
        $name = $_POST['uname'];
        $email = $_POST['uemail'];
        $pass = $_POST['upass'];
        
        $conn->query("insert into game_users (name, email, pass) values('$name', '$email', '$pass')");
        $last_id = $conn->insert_id;
        
        $_SESSION['id'] = $last_id;
        $_SESSION['json'] = null;
        $_SESSION['login'] = true;

        $conn->query("insert into game_games (user_id) values( $last_id )");

        header("location: qubegame.php");

    }

    //login
    if(isset($_POST['iname']) && isset($_POST['ipass'])){
       
        $iname = $_POST['iname'];
        $ipass = $_POST['ipass'];

        $result = $conn->query("select id from game_users where name = '$iname' and pass = '$ipass'");
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];

            $json_res = $conn->query("select game_inf from game_games where user_id = $_SESSION[id]");
            $inf = $json_res->fetch_assoc();

            $_SESSION['json'] = json_decode($inf['game_inf']);

            $_SESSION['login'] = true;

            header("location: qubegame.php");

        } else {

            $_SESSION['login'] = false;

            header("location: panel.html");

        }

    }

    $act = $_GET['act'];

    if ($act == "save") {

        $json = $_GET['json'];

        $conn->query("update game_games set game_inf = '$json' where user_id = $_SESSION[id]");

    }

?>