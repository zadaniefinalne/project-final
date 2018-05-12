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
            <a class="nav-link" href="login.php">Logout</a>
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
$teamname = $_GET['teamname'];

//

$sql= "SELECT email from Teams where teamname='$teamname'";
$result= mysqli_query($connection, $sql);
echo "<table class='table table-dark table-striped'><tr>
<th>Team members</th>
<th></th>
</tr>";

while ($row=mysqli_fetch_row($result)):{
    echo '<tr>
      <td> '.$row[0].' </td>
    <td><a  href="admindelete.php?teamname='.$teamname.'&delete='.$row[0].'"><img style="width: 22px" alt="delete_icon" src="delete.png"></a>
    </tr>';

} endwhile;
echo "</table>";



////////////////////////////Pridavanie člena do existujuceho tímu/////////////////
if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $find = "SELECT email from FinalZadanie where email='$email'"; //najde ci uz existuje email=user
    $result = mysqli_query($connection, $find);

    if ($result->num_rows > 0) {
        $count_members = "SELECT COUNT(email) as pocet from Teams WHERE teamname='$teamname'";
        $result2 = mysqli_query($connection, $count_members);
        $run_count_members= mysqli_fetch_row($result2); ///kolko je clenov v tíme
        if ($run_count_members[0] > 5) {

            echo "<p style='color: red'>Maximal number of members!</p>";
        } else {
            $sql = "INSERT INTO Teams (teamname,email) VALUES ('$teamname','$email')";
            mysqli_query($connection, $sql);
            header('Location: admindetailsteams.php?teamname='.$teamname);
        }
    } else {
        echo "<p style='color: red'>Member does not exist!</p>";
    }
}


mysqli_close($connection);

?>
<form action="" method="post" class="center">
    <input placeholder="Email" type="email" name="email">
    <input type="submit" name="submit" value="Add  member" class=" btn btn-secondary">
</form>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team</title>
</head>
<body>

</body>
</html>