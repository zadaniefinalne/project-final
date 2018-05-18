<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="adminentrypage.php">Add User's Form CVS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admintracelist.php">Trace List</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminaddtrace.php">Add Traces</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminteams.php">Teams</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminaddteam.php">Add Team</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminusersoverview.php">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminnewsletter.php"> Newsletter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php">Logout</a>
        </li>

    </ul>
</nav>

<form action="" method="post" enctype="multipart/form-data">
    Select CVS
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload csv file" name="submit2">
</form>

<link rel="stylesheet" type="text/css" href="style.css">
<?php
session_start();
header('Content-Encoding: UTF-8');
require_once "db.php";
error_reporting(0);
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/3/2018
 * Time: 2:35 PM
 */
$firstcheck = $_GET['changetoactive'];
$con = mysqli_connect($hostname, $username, $password, $dbname);
mysqli_set_charset($con, 'utf8');

if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin']))
    header("location: login.php");

if(isset($_POST["submit2"])){
    $nameoffile = $_FILES["fileToUpload"]["name"];
$handle = fopen($nameoffile, "r");
$control=0;
$defaultpw =  md5('default');
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
 if(!$control==0) {

     $udaj1 = utf8_encode($data[3]);
     $udaj2 = utf8_encode($data[2]);
     $udaj3 = utf8_encode($data[1]);
     $udaj4 = utf8_encode($data[4]);
     $udaj5 = utf8_encode($data[5]);
     $udaj6 = utf8_encode($data[6]);
     $udaj7 = utf8_encode($data[7]);
     $udaj8 = utf8_encode($data[8]);
    $sql = "INSERT INTO FinalZadanie VALUES(0,'','$defaultpw','$udaj1','1','$udaj2','$udaj3','$udaj4','$udaj5','$udaj6','$udaj7','$udaj8',1)";
    mysqli_query($con,$sql);
}
    $control=1;
}
fclose($handle);
mysqli_close($con);
}
?>
