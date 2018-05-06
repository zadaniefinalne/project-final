<!DOCTYPE html>

<?php
require "db.php";
session_start();

if (!isset($_SESSION['checktoken']) &&  empty($_SESSION['checktoken'])) // Check či je užívateľ prihlásený
    header("location: login.php");

$status = $_GET['status'];
$datax = $_GET['cislo'];

$sql = "SELECT StartingPoint, EndPoint, TravelMode FROM TraceInfo WHERE id = '$datax'";
$result = mysqli_query($connection, $sql);
$row=mysqli_fetch_row($result);

$start = $row[0];
$end = $row[1];
$mode = $row[2];

$sql = "SELECT SUM(pocetKM) from TraceDetail WHERE cislo= $datax";
$result = mysqli_query($connection,$sql);
$KM= mysqli_fetch_row($result);

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Final Zadanie</title>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="v3_epoly.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>



    <script type="text/javascript">
        var start = '<?php echo $start; ?>';
        var end = '<?php echo $end; ?>';
        var travelmode = '<?php echo $mode; ?>';
        var directionsService = new google.maps.DirectionsService();
        var gmarkers = [];
        var map = null;
        var startLocation = null;
        var endLocation = null;



        var kmdone = '<?php echo $KM[0]; ?>';
        var kmdone1 = kmdone *1000;
        //Okno ktore zobrazi nad markerom
        var infowindow = new google.maps.InfoWindow(
            {
                size: new google.maps.Size(150,50)
            });

        //Funkcia na vytvorenie markeru
        function createMarker(latlng, label, html, color) {
            var contentString = '<b>'+label+'</b><br>'+html;
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: label,
                zIndex: Math.round(latlng.lat()*-100000)<<5
            });
            marker.myname = label;
            gmarkers.push(marker);

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(contentString);
                infowindow.open(map,marker);
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
            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);

                    var bounds = new google.maps.LatLngBounds();

                    startLocation = new Object();
                    endLocation = new Object();
                    var polyline = new google.maps.Polyline({
                        path: [],
                    });


                    var legs = response.routes[0].legs;

                    for (i=0;i<legs.length;i++) {
                        if (i == 0) {
                            startLocation.latlng = legs[i].start_location;
                            startLocation.address = legs[i].start_address;
                        }
                        endLocation.latlng = legs[i].end_location;
                        endLocation.address = legs[i].end_address;
                        var steps = legs[i].steps;

                        for (j=0;j<steps.length;j++) {
                            var nextSegment = steps[j].path;
                            for (k=0;k<nextSegment.length;k++) {
                                polyline.getPath().push(nextSegment[k]);
                                bounds.extend(nextSegment[k]);
                            }
                        }
                    }

                    createMarker(polyline.GetPointAtDistance(kmdone1),"Na tejto trase ste prešli:" ,kmdone + " km");


                } else {
                    alert("directions response "+status);
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
            <a class="nav-link navbar-right" href="login.php">Logout</a>
        </li>
    </ul>
</nav>
<div id="map"></div>
<?php
if(isset($_POST['submit'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $sql = "INSERT INTO TraceDetail VALUES(0,'$data1','$data2','$data3','$data4','$data5','$data6','$data7','$data8','$data9','$data10','$data11')";
        mysqli_query($connection, $sql);
    }
}

if ($status == "ACTIVE"){ ?>
<div id ="forma">
<form action="" method="post">
    <label>KM done</label><input type="number" name="pocetKM" class="input" autocomplete="off" required/><br>
    <label>Date</label><input type="text" name="datum" class="input" autocomplete="off"/><br>
    <label>Start of training</label><input type="text" name="zactreningu" class="input" autocomplete="off"/><br>
    <label>End of traning</label><input type="text" name="koniectreningu" class="input" autocomplete="off"/><br>
    <label>Latitude of start</label><input type="text" name="latzac" class="input" autocomplete="off"/><br/>
    <label>Longtitude of start</label><input type="text" name="lngzac" class="input" autocomplete="off"/><br>
    <label>Latitude of finish</label><input type="text" name="latkon" class="input" autocomplete="off"/><br>
    <label>Longtitude of finish</label><input type="text" name="lngkon" class="input" autocomplete="off"/><br>
    <label>Evaluation</label><input type="number" name="hodnotenie" class="input" autocomplete="off"/><br>
    <label>Note</label><input type="text" name="poznamka" class="input" autocomplete="off"/><br>

    <input type="submit" name="submit" class="button button-secondary" value="Update Trace" />
</form></div>
<?php
}
?>
</div>
</body>
</html>