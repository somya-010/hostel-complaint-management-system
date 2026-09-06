<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login2.php');
    exit();
}

if (isset($_GET['del'])) {
    $conn = mysqli_connect('localhost', 'root', '', 'dbms');
    $id = mysqli_real_escape_string($conn, $_GET['del']);
    $query = "DELETE FROM complaints WHERE Student_Id = '$id'";
    mysqli_query($conn, $query);
    mysqli_close($conn);
    header('location: admin_studentdetail.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;
}
body {
  margin: 0;
  font-family: "Lato", sans-serif;
}

.sidebar {
  margin: 0;
  padding: 0;
  width: 200px;
  background-color: #f1f1f1;
  position: fixed;
  height: 100%;
  overflow: auto;
}

.sidebar a {
  display: block;
  color: black;
  padding: 16px;
  text-decoration: none;
}

.sidebar a.active {
  background-color: #4CAF50;
  color: white;
}

.sidebar a:hover:not(.active) {
  background-color: #555;
  color: white;
}

div.content {
  margin-left: 200px;
  padding: 1px 16px;
  height: 1000px;
}

@media screen and (max-width: 700px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }
  .sidebar a {float: left;}
  div.content {margin-left: 0;}
}

@media screen and (max-width: 400px) {
  .sidebar a {
    text-align: center;
    float: none;
  }
}
</style>
</head>
<body>

<div class="sidebar">
  <a class="active" href="#home">Home</a>
  <a href="register2.php">Staff Registration</a>
  <a href="admin_studentdetail.php">Student Complaint Details</a>
  <a href="#about">About</a>
</div>

<div class="content">
<h2 class="page-title">Student Complaints Table</h2>
<?php
$conn = mysqli_connect("localhost", "root", "", "dbms");
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$query = "
    SELECT c.Student_Id, c.roomno, c.phoneno, c.complaint_date, c.complaint_type, c.description, s.staffname
    FROM complaints AS c
    LEFT JOIN staff AS s
    ON c.complaint_type = s.department
    ORDER BY c.complaint_date DESC";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
?>
<table id="zctb" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Student Id</th>
      <th>Room No</th>
      <th>Phone No</th>
      <th>Complaint Date</th>
      <th>Complaint Type</th>
      <th>Description</th>
      <th>Staff Name</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?php echo htmlspecialchars($row['Student_Id']); ?></td>
      <td><?php echo htmlspecialchars($row['roomno']); ?></td>
      <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
      <td><?php echo htmlspecialchars($row['complaint_date']); ?></td>
      <td><?php echo htmlspecialchars($row['complaint_type']); ?></td>
      <td><?php echo htmlspecialchars($row['description']); ?></td>
      <td><?php echo htmlspecialchars($row['staffname']); ?></td>
      <td><a href="delete.php?id=<?php echo urlencode($row['Student_Id']); ?>" onClick="return confirm('are you sure you want to delete this?');">x</a></td>
    </tr>
<?php } ?>
  </tbody>
</table>
</div>
<p> <a href="a.php?logout='1'" style="color: red;">logout</a> </p>

</body>
</html>