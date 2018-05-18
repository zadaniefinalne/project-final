<?php
require_once "../db.php";
session_start();

if (!isset($_SESSION['checktoken']) &&  empty($_SESSION['checktoken']))
    header("location: ../login.php");
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/4/2018
 * Time: 6:03 PM
 */
error_reporting(0);
$user = $_SESSION['loginemail'];
$firstcheck = $_GET['changetoactive'];
$registratedbyadmin = "ALL";


$findteam="SELECT teamname from Teams WHERE email='$user'";
$result= mysqli_query($connection,$findteam);
$row=mysqli_fetch_row($result);
$_SESSION['team']=$row[0];


$sort='ASC';
$field = 'StartingPoint';
$field1 = 'StartingPoint';

if($_GET['field']=='StartingPoint')
{
    $field = "StartingPoint";
}
elseif($_GET['field']=='EndPoint')
{
    $field = "EndPoint";
}
elseif($_GET['field']=='Mode')
{
    $field = "Mode";
}
elseif($_GET['field']=='Status')
{
    $field = "Status";
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


if ($firstcheck > 0){

    $sql = "UPDATE TraceInfo SET Status = 'INACTIVE' WHERE Status = 'ACTIVE' AND Email = '$user'";
    mysqli_query($connection,$sql);

    $sql = "UPDATE TraceInfo SET Status = 'ACTIVE' WHERE id = '$firstcheck' AND Email = '$user'";
    mysqli_query($connection,$sql);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submittrace'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $status = "ACTIVE";
        $replace = "INACTIVE";
        $mode = "private";

        $travelmode = $_POST['travelmode'];

        $sql = "UPDATE TraceInfo SET Status = '$replace' WHERE Status = '$status' AND Email = '$user'";
        mysqli_query($connection,$sql);

        $sql = "INSERT INTO TraceInfo VALUES (0,'$start','$end','$mode','$user', '$status','$travelmode')";
        mysqli_query($connection,$sql);
    }}

$sql = "SELECT StartingPoint, EndPoint, Status, Mode, id FROM TraceInfo WHERE Email = '$user' ORDER BY $field $sort";
$result = mysqli_query($connection,$sql);


if($_GET['field1']=='StartingPoint')
{
    $field1 = "StartingPoint";
}
elseif($_GET['field1']=='EndPoint')
{
    $field1 = "EndPoint";
}
elseif($_GET['field1']=='Mode')
{
    $field1 = "Mode";
}
elseif($_GET['field1']=='Status')
{
    $field1 = "Status";
}

if(isset($_GET['sorting1']))
{
    if($_GET['sorting1']=='ASC')
    {
        $sort1='DESC';
    }
    else {
        $sort1 = 'ASC';
    }
}

$sql = "SELECT StartingPoint, EndPoint, Mode, Status, id FROM TraceInfo WHERE Email = '$registratedbyadmin'ORDER BY $field1 $sort1";
$result = mysqli_query($connection,$sql);

echo "<table class='table table-dark table-striped'><tr>
<th><a href=\"tracelist.php?sorting1=$sort1&field1=StartingPoint\">Starting Point</a></th>
<th><a href=\"tracelist.php?sorting1=$sort1&field1=EndPoint\">End Point</a></th>
<th><a href=\"tracelist.php?sorting1=$sort1&field1=Mode\">Mode</a></th>
<th><a href=\"tracelist.php?sorting1=$sort1&field1=Status\">Status</a></th>
<th>Route</th>
</tr>";

while ($row=mysqli_fetch_row($result)):{
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "<td>" . "<a href=\"tracedetail.php?cislo=$row[4]&status=$row[3]&mode=$row[2]\"> Show details</a>". "</td>";

    echo "</tr>";
} endwhile;
echo "</table>";

mysqli_close($connection);