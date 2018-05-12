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

if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin'])) // Kontrola ci je admin prihlásený
    header("location: login.php");

$sort='ASC';
$field = 'TeamName';

if($_GET['field']=='TeamName')
{
    $field = "TeamName";
}
elseif($_GET['field']=='Number')
{
    $field = "pocet";
}


if(isset($_GET['sorting']))
{
    if($_GET['sorting']=='ASC')
    {
        $sort='DESC';
    }
    else {
        $sort = 'ASC';
    }
}

$sql="SELECT DISTINCT(teamname),COUNT(teamname) as pocet FROM Teams GROUP BY teamname ORDER BY $field $sort";
$result= mysqli_query($connection, $sql);

echo "<h1 style='text-align: center'>All Teams</h1>";
echo "<table class='table table-dark table-striped'><tr>
<th><a href=\"adminteams.php?sorting=$sort&field=TeamName\">Team Name</a></th>
<th><a href=\"adminteams.php?sorting=$sort&field=Number\">Number</a></th>
<th></th>
</tr>";

while ($row=mysqli_fetch_row($result)):{
    echo"<tr>
     <td> <a href='admindetailsteams.php?teamname=".$row[0]."'>$row[0] </a></td>
     <td>". $row[1] ." / 6". "</td>
     <td><a  href='admindelete.php?teamname=".$row[0]."'><img style=\"width: 22px\" alt=\"delete_icon\" src=\"delete.png\"></a>
     </tr>";
} endwhile;
echo "</table>";

mysqli_close($connection);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teams</title>
</head>
<body>

</body>
</html>