<?php
session_start();

// variable declaration
$id = "";
$errors = array();
$_SESSION['success'] = "";

// connect to database
$db1 = mysqli_connect('localhost', 'root', '', 'dbms');
if (!$db1) {
    die('Database connection failed: ' . mysqli_connect_error());
}

if (isset($_POST['login_user1'])) {
    $id = mysqli_real_escape_string($db1, $_POST['id']);
    $password = mysqli_real_escape_string($db1, $_POST['password']);

    if (empty($id)) {
        array_push($errors, "Admin Id is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM admin WHERE id='$id' AND password='$password'";
        $results = mysqli_query($db1, $query);

        if (mysqli_num_rows($results) == 1) {
            $admin = mysqli_fetch_assoc($results);
            $_SESSION['username'] = $admin['username'];
            $_SESSION['success'] = "You are now logged in";
            header('location: admin_manage.php');
            exit();
        }

        array_push($errors, "Wrong admin_Id/password combination");
    }
}
?>
