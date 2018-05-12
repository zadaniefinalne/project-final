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
            <a class=" nav-link " href="login.php">Logout</a>
        </li>

    </ul>
</nav>
<link rel="stylesheet" type="text/css" href="style.css">
<?php
session_start();
header('Content-Encoding: UTF-8');
require_once "db.php";
error_reporting(0);

if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin'])) // Kontrola či je admin prihlásený
    header("location: login.php");

if(isset($_POST['submitteam'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data1 = $_POST['teamname'];
        $data2 = $_POST['m1'];
        $data3 = $_POST['m2'];
        $data4= $_POST['m3'];
        $data5 = $_POST['m4'];
        $data6 = $_POST['m5'];
        $data7 = $_POST['m6'];


       // $sql = "INSERT INTO Teams (teamname) VALUES('$data1')";
       // mysqli_query($connection, $sql);

if(!empty($data1)){

    $findteam= "SELECT teamname from Teams where teamname='$data1'";
    $result2= mysqli_query($connection, $findteam);

    if($result2->num_rows == 0) {

        if (!empty($data2)) {
            $find = "SELECT email from FinalZadanie where email='$data2'";

            $result = mysqli_query($connection, $find);

            if ($result->num_rows > 0) {
                $sql = "INSERT INTO Teams (teamname,email) VALUES('$data1','$data2')";
                mysqli_query($connection, $sql);
            } else {
                $error1 = "<p style='color: red'>Member does not exist!</p>";
            }
        }

        if (!empty($data3)) {
            $find = "SELECT email from FinalZadanie where email='$data3'";

            $result = mysqli_query($connection, $find);

            if ($result->num_rows > 0) {
                $sql = "INSERT INTO Teams (teamname,email) VALUES('$data1','$data3')";
                mysqli_query($connection, $sql);
            } else {
                $error2 = "<p style='color: red'>Member does not exist!</p>";
            }
        }

        if (!empty($data4)) {
            $find = "SELECT email from FinalZadanie where email='$data4'";

            $result = mysqli_query($connection, $find);

            if ($result->num_rows > 0) {
                $sql = "INSERT INTO Teams (teamname,email) VALUES('$data1','$data4')";
                mysqli_query($connection, $sql);
            } else {
                $error3 = "<p style='color: red'>Member does not exist!</p>";
            }
        }

        if (!empty($data5)) {
            $find = "SELECT email from FinalZadanie where email='$data5'";

            $result = mysqli_query($connection, $find);

            if ($result->num_rows > 0) {
                $sql = "INSERT INTO Teams (teamname,email) VALUES('$data1','$data5')";
                mysqli_query($connection, $sql);
            } else {
                $error4 = "<p style='color: red'>Member does not exist!</p>";
            }
        }

        if (!empty($data6)) {
            $find = "SELECT email from FinalZadanie where email='$data6'";

            $result = mysqli_query($connection, $find);

            if ($result->num_rows > 0) {
                $sql = "INSERT INTO Teams (teamname,email) VALUES('$data1','$data6')";
                mysqli_query($connection, $sql);
            } else {
                $error5 = "<p style='color: red'>Member does not exist!</p>";
            }
        }

        if (!empty($data7)) {
            $find = "SELECT email from FinalZadanie where email='$data7'";

            $result = mysqli_query($connection, $find);

            if ($result->num_rows > 0) {
                $sql = "INSERT INTO Teams (teamname,email) VALUES('$data1','$data7')";
                mysqli_query($connection, $sql);
            } else {
                $error6 = "<p style='color: red'>Member does not exist!</p>";
            }
        }
    }else {$error7 = "<p style='color: red'>Team already exists!</p>";}


}else{$error8 = "<p style='color: red'>Team name must be filled!</p>";}


    }}
?>
<link rel="stylesheet" type="text/css" href="style.css">
<form action="" method="POST" class="center">
    <label>Team name</label><br><input type="text" name= "teamname" class="input" autocomplete="off"/>
    <?php echo $error7?><?php echo $error8?><br>
    <label>Member #1</label><br><input type="email" name="m1" class="input" autocomplete="off" placeholder="Email"/>
    <?php echo $error1?> <br/>
    <label>Member #2</label><br><input type="email" name="m2" class="input" autocomplete="off" placeholder="Email"/>
    <?php echo $error2?><br/>
    <label>Member #3</label><br><input type="email" name="m3" class="input" autocomplete="off" placeholder="Email"/>
    <?php echo $error3?><br/>
    <label>Member #4</label><br><input type="email" name="m4" class="input" autocomplete="off" placeholder="Email"/>
    <?php echo $error4?><br/>
    <label>Member #5</label><br><input type="email" name="m5" class="input" autocomplete="off" placeholder="Email"/>
    <?php echo $error5?><br/>
    <label>Member #6</label><br><input type="email" name="m6" class="input" autocomplete="off" placeholder="Email"/>
    <?php echo $error6?><br/>
    <input type="submit" name="submitteam" class="button button-primarys btn1" value="Add team" />
</form>