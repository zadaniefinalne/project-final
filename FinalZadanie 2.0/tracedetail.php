<!DOCTYPE html>

<?php
require "db.php";
session_start();

if (!isset($_SESSION['checktoken']) && empty($_SESSION['checktoken'])) // Check či je užívateľ prihlásený
    header("location: login.php");
$user = $_SESSION['loginemail'];
$team = $_SESSION['team'];

$status = $_GET['status'];
$datax = $_GET['cislo'];
$modetype = $_GET['mode'];

/*
$sql1="SELECT mode from TraceInfo WHERE id = '$datax'";  //zisti ci je to public/private/relay
$result1 = mysqli_query($connection, $sql1);
$row1=mysqli_fetch_row($result1);
*/

$sql2 = "SELECT COUNT(DISTINCT(activemail)) FROM TraceDetail where cislo='$datax'"; //kolko je uzivatelov na danej trase(2 pre 30)
$result2 = mysqli_query($connection, $sql2);
$row2 = mysqli_fetch_row($result2);
$pocet = $row2[0];

$sql3 = "SELECT sum(pocetKM),activemail FROM TraceDetail WHERE cislo='$datax' AND NOT activemail='NULL' GROUP BY activemail, cislo";
$result3 = mysqli_query($connection, $sql3); //pocet km daneho uzivatela na danej trase
//$row3=mysqli_fetch_row($result3);
//print_r($row3[0]);

///////////////////////////////////////////////////RELAY MODE//////////////////////////////////////////////////////


//kolko je timov na danej trase
$sql20="SELECT COUNT(DISTINCT(t.teamname)) FROM TraceDetail as td  INNER JOIN Teams as t ON t.email=td.activemail where td.cislo='$datax'";
$result20 = mysqli_query($connection, $sql20);
$row20 = mysqli_fetch_row($result20);
$pocetrelay = $row20[0];
//echo $pocetrelay;

$sql21="SELECT sum(td.pocetKM),t.teamname FROM TraceDetail as td 
INNER JOIN Teams as t ON t.email=td.activemail
WHERE td.cislo='$datax' AND NOT td.activemail='NULL' GROUP BY t.teamname, td.cislo";


$sql = "SELECT ROUND(SUM(td.pocetKM)/Count(t.teamname),0) ,t.teamname FROM TraceDetail as td 
     INNER JOIN Teams AS t ON t.teamname=td.team
     where td.cislo='$datax' GROUP BY t.teamname, td.activemail";

$result21 = mysqli_query($connection, $sql21);

while ($row21 = mysqli_fetch_row($result21)):{
$list2[] = $row21;

} endwhile;

//print_r($list2);


////////////////////////////////////////////////////////////END OF RELAY MODE////////////////////////////////////


$i = 0;//asi teda netreba
$a = array();//asi netreba
$arraypushed = array();//asi netreba


while ($row3 = mysqli_fetch_row($result3)):{
    $list[] = $row3;/*
array_push($a,$row3[0],$row3[1]);
    array_push($arraypushed,$a[$i]);*/
//$i=$i+1;

} endwhile;

//print_r($list);

$sql = "SELECT StartingPoint, EndPoint, TravelMode FROM TraceInfo WHERE id = '$datax'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_row($result);

$start = $row[0];
$end = $row[1];
$mode = $row[2];

$sql = "SELECT SUM(pocetKM) from TraceDetail WHERE cislo= '$datax'";
$result = mysqli_query($connection, $sql);
$KM = mysqli_fetch_row($result);

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Final Zadanie</title>
   <!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyC3ZrhOr3gSOT0q8ezzVGPF6ngHt4683eQ&sensor=false"></script> -->

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3ZrhOr3gSOT0q8ezzVGPF6ngHt4683eQ&libraries=geometry,places">
    </script>

    <script type="text/javascript" src="v3_epoly.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>


    <script type="text/javascript">

       var numberofteams = <?php echo $pocetrelay; ?>;
        var pole2 = <?php echo json_encode($list2); ?>;


        var numberofusers =<?php echo $pocet; ?>;
        var pole = <?php echo json_encode($list); ?>;
        var mode = '<?php echo $modetype; ?>';

        var start = '<?php echo $start; ?>';
        var end = '<?php echo $end; ?>';
        var travelmode = '<?php echo $mode; ?>';
        var directionsService = new google.maps.DirectionsService();
        var gmarkers = [];
        var map = null;
        var startLocation = null;
        var endLocation = null;


        var kmdone = '<?php echo $KM[0]; ?>';
        var kmdone1 = kmdone * 1000;
        //Okno ktore zobrazi nad markerom


        var infowindow = new google.maps.InfoWindow(
            {
                size: new google.maps.Size(150, 50)
            });

        //Funkcia na vytvorenie markeru
        function createMarker(latlng, label, html, color) {
            var contentString = '<b>' + label + '</b><br>' + html;
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: label,
                zIndex: Math.round(latlng.lat() * -100000) << 5
            });
            marker.myname = label;
            gmarkers.push(marker);

            google.maps.event.addListener(marker, 'click', function () {
                infowindow.setContent(contentString);
                infowindow.open(map, marker);
            });
            return marker;
        }

        function initialize() {
            directionsDisplay = new google.maps.DirectionsRenderer();
            var slovakia = new google.maps.LatLng(48.5, 19.1);
            var myOptions = {
                zoom: 6,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                center: slovakia
            }
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            directionsDisplay.setMap(map);
            calcRoute();
        }

        function calcRoute() {
            var request = {
                origin: start,
                destination: end,
                travelMode: travelmode
            };
            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);

                    var bounds = new google.maps.LatLngBounds();

                    startLocation = new Object();
                    endLocation = new Object();
                    var polyline = new google.maps.Polyline({
                        path: [],
                    });


                    var legs = response.routes[0].legs;

                    for (i = 0; i < legs.length; i++) {
                        if (i == 0) {
                            startLocation.latlng = legs[i].start_location;
                            startLocation.address = legs[i].start_address;
                        }
                        endLocation.latlng = legs[i].end_location;
                        endLocation.address = legs[i].end_address;
                        var steps = legs[i].steps;

                        for (j = 0; j < steps.length; j++) {
                            var nextSegment = steps[j].path;
                            for (k = 0; k < nextSegment.length; k++) {
                                polyline.getPath().push(nextSegment[k]);
                                bounds.extend(nextSegment[k]);
                            }
                        }
                    }
                    if (mode == "public") {
                        for (var i = 0; i < numberofusers; i++) {

                            var kmdone2 = pole[i][0] * 1000;
                            createMarker(polyline.GetPointAtDistance(kmdone2), "Uzivatel: ", pole[i][1] + " (" + pole[i][0] + " km)");
                        }
                    }
                    else if (mode == "relay") {
                       // alert(pole2[0][0]);
                        for (var i = 0; i < numberofteams; i++) {

                            var kmdone3 = pole2[i][0] * 1000;
                            createMarker(polyline.GetPointAtDistance(kmdone3), "Tím: ", pole2[i][1] + " (" + pole2[i][0] + " km)");
                        }
                    }
                    else {
                        createMarker(polyline.GetPointAtDistance(kmdone1), "Na tejto trase ste prešli:", kmdone + " km");
                    }


                } else {
                    alert("directions response " + status);
                }
            });
        }
    </script>
</head>
<body onload="initialize()">
<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="tracelist.php">TraceList</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="userentrypage.php">Add map</a>
        </li>
        <li class="nav-item">
            <a class="nav-link navbar-right" href="performance.php">Activities</a>
        </li>
        <li class="nav-item">
            <a class="nav-link navbar-right" href="login.php">Logout</a>
        </li>
    </ul>
</nav>
<div id="map"></div>
<?php
if (isset($_POST['submit'])) {


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($modetype = "relay") {
            $sql1 = "SELECT DISTINCT(allow) from TraceDetail where cislo='$datax' and activemail='$user'";
            $result = mysqli_query($connection, $sql1);
            $row = mysqli_fetch_row($result);
            $data13 = $row[0];


            $data1 = $_POST['pocetKM'];
            $data2 = $_POST['datum'];
            $data3 = $_POST['zactreningu'];
            $data4 = $_POST['koniectreningu'];
            $data5 = $_POST['latzac'];
            $data6 = $_POST['lngzac'];
            $data7 = $_POST['latkon'];
            $data8 = $_POST['lngkon'];
            $data9 = $_POST['hodnotenie'];
            $data10 = $_POST['poznamka'];
            $data11 = $_GET['cislo'];
            $data12 = $user;

            $data14 = $team;
            if ($data13 == "no") {
                $alert="Uz ste pridali trasu a skoncili ste, jasna sprava!!!";
               echo $alert;
            } else {$data13 = "no";
                $sql = "INSERT INTO TraceDetail VALUES(0,'$data1','$data2','$data3','$data4','$data5','$data6','$data7','$data8','$data9','$data10','$data11','$data12','$data13','$data14')";
                mysqli_query($connection, $sql);

            }
        } else {

            $data1 = $_POST['pocetKM'];
            $data2 = $_POST['datum'];
            $data3 = $_POST['zactreningu'];
            $data4 = $_POST['koniectreningu'];
            $data5 = $_POST['latzac'];
            $data6 = $_POST['lngzac'];
            $data7 = $_POST['latkon'];
            $data8 = $_POST['lngkon'];
            $data9 = $_POST['hodnotenie'];
            $data10 = $_POST['poznamka'];
            $data11 = $_GET['cislo'];
            $data12 = $user;

            $sql = "INSERT INTO TraceDetail VALUES(0,'$data1','$data2','$data3','$data4','$data5','$data6','$data7','$data8','$data9','$data10','$data11','$data12')";
            mysqli_query($connection, $sql);
        }

    }

}

if ($status == "ACTIVE") { ?>
    <div id="forma">
        <form action="" method="post">
            <label>KM done</label><input type="number" name="pocetKM" class="input" autocomplete="off" required/><br>
            <label>Date</label><input type="text" name="datum" class="input" autocomplete="off"
                                      placeholder="DD.MM.YYYY"/><br>
            <label>Start of training</label><input type="text" name="zactreningu" class="input" autocomplete="off"
                                                   placeholder="HH:MM"/><br>
            <label>End of traning</label><input type="text" name="koniectreningu" class="input" autocomplete="off"
                                                placeholder="HH:MM"/><br>
            <label>Latitude of start</label><input type="text" name="latzac" class="input" autocomplete="off"
                                                   placeholder="48.14816N"/><br/>
            <label>Longtitude of start</label><input type="text" name="lngzac" class="input" autocomplete="off"
                                                     placeholder="17.10674E"/><br>
            <label>Latitude of finish</label><input type="text" name="latkon" class="input" autocomplete="off"
                                                    placeholder="48.14816N"/><br>
            <label>Longtitude of finish</label><input type="text" name="lngkon" class="input" autocomplete="off"
                                                      placeholder="17.10674E"/><br>
            <label>Evaluation</label><input type="number" name="hodnotenie" class="input" autocomplete="off"
                                            placeholder="1 - 10"/><br>
            <label>Note</label><input type="text" name="poznamka" class="input" autocomplete="off"/><br>

            <input type="submit" name="submit" class="button button-secondary" value="Update Trace"/>

        </form>
    </div>
    <?php
}
?>
</div>
</body>
</html>