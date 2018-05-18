<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/5/2018
 * Time: 4:53 PM
 */
session_start();
header('Content-Encoding: UTF-8');
require_once "../db.php";
error_reporting(0);

if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin'])) // Kontrola ci je admin prihlásený
    header("location: ../login.php");

$sort='ASC';
$field = 'StartingPoint';

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
elseif($_GET['field']=='Email')
{
    $field = "Email";
}
elseif($_GET['field']=='Status')
{
    $field = "Status";
}
elseif($_GET['field']=='TravelMode')
{
    $field = "TravelMode";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit'])) {
        $useremail = $_POST['email'];
        $sql = "SELECT * FROM TraceInfo WHERE Email = '$useremail' ORDER BY $field $sort";
        $result = mysqli_query($connection, $sql);
    }}
else{
    $sql = "SELECT * FROM TraceInfo ORDER BY $field $sort";
    $result = mysqli_query($connection, $sql);
}

echo "<table class='table table-dark table-striped'><tr>
<th><a href=\"admintracelist.php?sorting=$sort&field=StartingPoint\">Starting Point</a></th>
<th><a href=\"admintracelist.php?sorting=$sort&field=EndPoint\">End Point</a></th>
<th><a href=\"admintracelist.php?sorting=$sort&field=Mode\">Mode</a></th>
<th><a href=\"admintracelist.php?sorting=$sort&field=Email\">Email</a></th>
<th><a href=\"admintracelist.php?sorting=$sort&field=Status\">Status</a></th>
<th><a href=\"admintracelist.php?sorting=$sort&field=TravelMode\">TravelMode</a></th>
</tr>";

while ($row=mysqli_fetch_row($result)):{
    echo "<tr>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "<td>" . $row[4] . "</td>";
    echo "<td>" . $row[5] . "</td>";
    echo "<td>" . $row[6] . "</td>";
    echo "</tr>";
} endwhile;
echo "</table>";

mysqli_close($connection);

?>

