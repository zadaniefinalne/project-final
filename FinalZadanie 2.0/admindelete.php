<?php
require_once "db.php";
$teamname = $_GET['teamname'];
$email=$_GET['delete'];

error_reporting(0);



if(!empty($teamname)&& !empty($email))
{
    $sql = "DELETE FROM Teams WHERE teamname='$teamname' and email = '$email'";
    if (mysqli_query($connection, $sql)) {
        mysqli_close($connection);

        header('Location: admindetailsteams.php?teamname='.$teamname);
        exit;
    } else {
        echo "Error deleting record";
    }
}elseif(!empty($teamname)&& empty($email))
{
    $sql = "DELETE FROM Teams WHERE teamname='$teamname'";
    if (mysqli_query($connection, $sql)) {
        mysqli_close($connection);

        header('Location: adminteams.php');
        exit;
    } else {
        echo "Error deleting record";
    }


}






?>

