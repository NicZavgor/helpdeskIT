<?php

session_start();

require_once getcwd() . "/models/auth.php";

if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $select = "select id, pass, itrole, name FROM users WHERE name = :logn and blocking=0";//
    $stmt = getDb()->prepare($select);
    $stmt->execute([
        ":logn" => $login
    ]);
    $userDb = $stmt->fetchAll();
    //die(print_r($userDb));

/*
    $conn = getDb();
    $stmt = sqlsrv_prepare($conn, $select, array(&$login));//
    sqlsrv_execute($stmt);
    $userDb = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    sqlsrv_free_stmt( $stmt);
    sqlsrv_close( $conn);
    */
    if (count($userDb) !=0){
        if (password_verify($password, $userDb[0]['pass'])){//($password === $userDb[0]['pass']){ //временно убрал
            $_SESSION['login'] = $userDb[0]['name'];
            $_SESSION['role'] = $userDb[0]['itrole'];
            $_SESSION['userid'] = $userDb[0]['id'];
            //die(print_r('111111111111'));
            header('Location: index.php');
            exit();
        }
        $message="Неверный пользователь, пароль";
        header('Location: index.php');
        exit();
    }else{

        $message="Неверный пользователь, пароль";
        header('Location: index.php');
        exit();

    }
    
    //SELECT * FROM users WHERE login = $login ==> $hash
    //if ($login === 'admin' && $password === '123') {
    //  $_SESSION['login'] = 'admin';
    //    $_SESSION['role'] = 'admin';
    //    header('Location: index.php');
    //    exit();
    //}
}


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['login']);
    session_destroy();
    header('Location: index.php');
    exit();
}
