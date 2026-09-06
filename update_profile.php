<?php
session_start();

// variable declaration
$username = "";
$email = "";
$roomno = "";
$errors = array();
$_SESSION['success'] = "";
$aid = isset($_GET['Student_Id']) ? $_GET['Student_Id'] : (isset($_SESSION['Student_Id']) ? $_SESSION['Student_Id'] : '');

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'dbms');
if (!$db) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Update user
if (isset($_POST['reg_user3'])) {
    $Student_Id = mysqli_real_escape_string($db, $_POST['Student_Id']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    $roomno = mysqli_real_escape_string($db, $_POST['roomno']);

    if (empty($Student_Id)) { array_push($errors, "Student_Id is required"); }
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }

    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    if (count($errors) == 0) {
        $password = md5($password_1);
        $query = "UPDATE registration SET username='$username', email='$email', password='$password', roomno='$roomno' WHERE Student_Id='$Student_Id'";

        if (mysqli_query($db, $query)) {
            $_SESSION['success'] = "Your data successfully updates";
            $_SESSION['username'] = $username;
            header('location: student_manage.php');
            exit();
        }

        array_push($errors, "Error updating record");
    }
} else {
    $query = "SELECT username, email, roomno FROM registration WHERE Student_Id='$aid'";
    $result = mysqli_query($db, $query);
    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $username = $user['username'];
        $email = $user['email'];
        $roomno = $user['roomno'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration system PHP and MySQL</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Update Registration Details</h2>
    </div>

    <form method="post" action="update_profile.php">
        <?php include('errors.php'); ?>

        <div class="input-group">
            <label>Student Id</label>
            <input type="text" name="Student_Id" value="<?php echo htmlspecialchars($aid); ?>">
        </div>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password_1">
        </div>
        <div class="input-group">
            <label>Confirm password</label>
            <input type="password" name="password_2">
        </div>
        <div class="input-group">
            <label>Room No.</label>
            <input type="text" name="roomno" value="<?php echo htmlspecialchars($roomno); ?>">
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="reg_user3">Register</button>
        </div>
    </form>
</body>
</html>