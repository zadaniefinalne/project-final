<?php
session_start();
header('Content-Encoding: UTF-8');
require_once "../db.php";
error_reporting(0);

if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin'])) // Kontrola ci je admin prihlásený
    header("location: ../login.php");

if(isset($_GET['user']))
{$sort='ASC';
    $field = 'StartingPoint';

    if($_GET['field']=='Distance')
    {
        $field = "td.pocetKM";
    }
    elseif($_GET['field']=='Date')
    {
        $field = "td.datum";
    }
    elseif($_GET['field']=='Startoftraining')
    {
        $field = "td.zactreningu";
    }
    elseif($_GET['field']=='Endoftraining')
    {
        $field = "td.koniectreningu";
    }elseif($_GET['field']=='Evaluation')
    {
        $field = "td.hodnotenie";
    }
    elseif($_GET['field']=='Startingpoint')
    {
        $field = "ti.StartingPoint";
    }
    elseif($_GET['field']=='EndPoint')
    {
        $field = "ti.EndPoint";
    }elseif($_GET['field']=='TravelMode')
    {
        $field = "TravelMode";
    }elseif($_GET['field']=='Avgs')
    {
        $field = "ti.EndPoint";
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
    $user=$_GET['user'];

    $sql = "SELECT td.datum,td.pocetKM,td.zactreningu,td.koniectreningu,td.hodnotenie,ti.StartingPoint,ti.EndPoint,ti.TravelMode,ROUND((((td.pocetKM*1000)/(LEFT(TIMEDIFF(td.koniectreningu,td.zactreningu),2)*3600+RIGHT(LEFT(TIMEDIFF(td.koniectreningu,td.zactreningu),5),2)*60))*3.6),2) as avridz FROM TraceDetail as td
JOIN TraceInfo as ti ON td.cislo=ti.id
where ti.email='$user'  or td.activemail='$user'
ORDER BY $field $sort";
    $result =  mysqli_query($connection, $sql);

    echo "<div id='users'> <table class='table table-dark table-striped'>
<tr>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Date\">Date</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Distance\">Distance (km)</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Startoftraining\">Start of training</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Endoftraining\">End of training</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Evaluation\">Evaluation</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Startingpoint\">Starting point</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=EndPoint\">End Point</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=TravelMode\">Travel Mode</a></th>
<th><a href=\"adminusersoverview.php?user=$user&sorting=$sort&field=Avgs\">Average Speed (km/h)</a></th>
</tr>";

    $i=0;
    $sumakm=0;
    while ($row=mysqli_fetch_row($result)):{
        $i=$i+1;
        $sumakm=$sumakm+$row[1];
        echo"<tr>
     <td>". $row[0] ."</td>
     <td>". $row[1] . "</td>
     <td>". $row[2] . "</td>
     <td>". $row[3] . "</td>
     <td>". $row[4] . "</td>
     <td>". $row[5] . "</td>
     <td>". $row[6] . "</td>
     <td>". $row[7]. "</td>
     <td>". $row[8]. "</td>
     </tr>";
    } endwhile;
    echo "</table></div>";
    $avg = $sumakm/$i;

}
else{
    $sql= "SELECT email FROM FinalZadanie";
    $result= mysqli_query($connection, $sql);
    echo "<h1 style='text-align: center'>All Users</h1>";
    echo "<table class='table table-dark table-striped'><tr>
<th>Users</th>
<th></th>
</tr>";

    while ($row=mysqli_fetch_row($result)):{
        echo '<tr>
      <td> '.$row[0].' </td>
    <td><a  href="adminusersoverview.php?user='.$row[0].'"><img style="width: 22px" alt="delete_icon" src="arrow.png"></a>
    </tr>';

    } endwhile;
    echo "</table>";

}

mysqli_close($connection);


?>
