<?php
session_start();

// variable declaration
$Student_Id = "";
$username = "";
$email = "";
$errors = array();
$_SESSION['success'] = "";

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'dbms');
if (!$db) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $Student_Id = mysqli_real_escape_string($db, $_POST['Student_Id']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    $roomno = mysqli_real_escape_string($db, $_POST['roomno']);

    // form validation: ensure that the form is correctly filled
    if (empty($Student_Id)) { array_push($errors, "Student_Id is required"); }
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }

    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    if (count($errors) == 0) {
        $existingUser = mysqli_query($db, "SELECT Student_Id FROM registration WHERE Student_Id = '$Student_Id'");
        if (mysqli_num_rows($existingUser) > 0) {
            array_push($errors, "Student_Id already exists");
        }
    }

    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1);
        $query = "INSERT INTO registration (Student_Id, username, email, password, roomno) 
                  VALUES('$Student_Id','$username', '$email', '$password','$roomno')";

        if (mysqli_query($db, $query)) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            $_SESSION['Student_Id'] = $Student_Id;
            header('location: main.php');
            exit();
        }

        array_push($errors, "Unable to register user");
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $Student_Id = mysqli_real_escape_string($db, $_POST['Student_Id']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($Student_Id)) {
        array_push($errors, "Student_Id is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM registration WHERE Student_Id='$Student_Id' AND password='$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);
            $_SESSION['username'] = $user['username'];
            $_SESSION['success'] = "You are now logged in";
            $_SESSION['Student_Id'] = $Student_Id;
            header('location: main.php');
            exit();
        }

        array_push($errors, "Wrong Student_Id/password combination");
    }
}
?>