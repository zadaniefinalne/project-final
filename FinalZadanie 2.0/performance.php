<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
<?php
echo "<nav class=\"navbar navbar-expand-sm bg-dark\">
        <ul class=\"navbar-nav\">
        <li class=\"nav-item\">
        <a class=\"nav-link\" href=\"tracelist.php\">TraceList</a>
        </li>
        <li class=\"nav-item\">
        <a class=\"nav-link\" href=\"userentrypage.php\">Add map</a>
        </li>        
        <li class=\"nav-item\">
        <a class=\"nav-link navbar-right\" href=\"performance.php\">Activities</a>
        </li>
        <li class=\"nav-item\">
        <a class=\"nav-link navbar-right\" href=\"login.php\">Logout</a>
        </li>
        </ul>
        </nav>
       
";
require "db.php";
session_start();

if (!isset($_SESSION['checktoken']) &&  empty($_SESSION['checktoken'])) // Check či je užívateľ prihlásený
    header("location: login.php");
$user = $_SESSION['loginemail'];


$sort='ASC';
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
$sql = "SELECT td.datum,td.pocetKM,td.zactreningu,td.koniectreningu,td.hodnotenie,ti.StartingPoint,ti.EndPoint,ti.TravelMode,ROUND((((td.pocetKM*1000)/(LEFT(TIMEDIFF(td.koniectreningu,td.zactreningu),2)*3600+RIGHT(LEFT(TIMEDIFF(td.koniectreningu,td.zactreningu),5),2)*60))*3.6),2) as avridz FROM TraceDetail as td
JOIN TraceInfo as ti ON td.cislo=ti.id
where ti.email='$user' or td.activemail='$user'
ORDER BY $field $sort";
$result =  mysqli_query($connection, $sql);
echo "<h1 style='text-align: center'>My trainings</h1>";
echo "<div id='users'><table class='table table-dark table-striped'>
<tr>
<th><a href=\"performance.php?sorting=$sort&field=Date\">Date</a></th>
<th><a href=\"performance.php?sorting=$sort&field=Distance\">Distance (km)</a></th>
<th><a href=\"performance.php?sorting=$sort&field=Startoftraining\">Start of training</a></th>
<th><a href=\"performance.php?sorting=$sort&field=Endoftraining\">End of training</a></th>
<th><a href=\"performance.php?sorting=$sort&field=Evaluation\">Evaluation</a></th>
<th><a href=\"performance.php?sorting=$sort&field=Startingpoint\">Starting point</a></th>
<th><a href=\"performance.php?sorting=$sort&field=EndPoint\">End Point</a></th>
<th><a href=\"performance.php?sorting=$sort&field=TravelMode\">Travel Mode</a></th>
<th><a href=\"performance.php?sorting=$sort&field=Avgs\">Average Speed (km/h)</a></th>
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
echo "<div class=' mx-auto1  p-2 bg-dark text-white '> Average passed distance: ".round($avg,2)." km</div>";
echo "<div class='mx-auto1' > <button class='button button-primarys btn1' onclick='javascript:demoFromHTML();'>Export to PDF</button></div>";
echo"<br>";
echo "<div class='mx-auto1'>  <a class='btn btn-primary ' href='adminusersoverview.php' role='button'>Back</a> </div>";
mysqli_close($connection);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activities</title>
</head>
<body>

</body>
<script>
    function demoFromHTML() {
        var pdf = new jsPDF('l', 'pt', [2000,1000]);
        // source can be HTML-formatted string, or a reference
        // to an actual DOM element from which the text will be scraped.
        source = $('#users')[0];

        // we support special element handlers. Register them with jQuery-style
        // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
        // There is no support for any other type of selectors
        // (class, of compound) at this time.
        specialElementHandlers = {
            // element with id of "bypass" - jQuery style selector
            '#bypassme': function (element, renderer) {
                // true = "handled elsewhere, bypass text extraction"
                return true
            }
        };
        margins = {
            top: 50,
            bottom: 60,
            left: 260,
            width: 700
        };
        // all coords and widths are in jsPDF instance's declared units
        // 'inches' in this case
        pdf.fromHTML(
            source, // HTML string or DOM elem ref.
            margins.left, // x coord
            margins.top, { // y coord
                'width': margins.width, // max width of content on PDF
                'elementHandlers': specialElementHandlers
            },

            function (dispose) {
                // dispose: object with X, Y of the last line add to the PDF
                //          this allow the insertion of new lines after html
                pdf.save('User_Data.pdf');
            }, margins);
    }
</script>
</html>