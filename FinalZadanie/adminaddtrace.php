<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="adminentrypage.php">Add usersfrom CVS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admintracelist.php">TraceList</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminaddtrace.php">Add traces</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php">Logout</a>
        </li>

    </ul>
</nav>

<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/6/2018
 * Time: 12:56 AM
 */

session_start();
header('Content-Encoding: UTF-8');
require_once "db.php";
error_reporting(0);

if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin'])) // Kontrola či je admin prihlásený
    header("location: login.php");

if(isset($_POST['submittrace'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data1 = $_POST['start'];
  $data2 = $_POST['end'];
  $data3 = $_POST['mode'];
  $data4 = 'ACTIVE';
  $data5 = $_POST['travelmode'];
  $data6 = $_POST['email'];

  if ($data6 == '') { // Vlozenie novej trasy, ak zadal email ostava on, ak nezadal email 'ALL' co znamena udaj pridany adminom
      $data6 = "ALL";
  }

  if($data6 != ''){
      $sql = "UPDATE TraceInfo SET Status = 'INACTIVE' WHERE Email = '$data6'";
      mysqli_query($connection, $sql);
  }


  $sql = "INSERT INTO TraceInfo VALUES(0,'$data1','$data2','$data3','$data6', '$data4','$data5')";
  mysqli_query($connection, $sql);
}}

?>


<form action="" method="POST" class="center">
    <label>Start point</label><br><input type="text" name= "start" class="input" autocomplete="off"/><br>
    <label>Destination point</label><br><input type="text" name="end" class="input" autocomplete="off"/><br/>
    <select name="mode">
        <option value="private">Private mode</option>
        <option value="public">Public mode</option>
        <option value="relay">Relay mode</option>
    </select><br>

    <label>Email</label><br><input type="email" name="email" class="input" autocomplete="off"/><br/>
    <select name="travelmode">
        <option value="WALKING">Walking</option>
        <option value="BICYCLING">Bicycling</option>
    </select><br>
    <input type="submit" name="submittrace" class="button button-primarys btn1" value="Add new trace" />
</form>