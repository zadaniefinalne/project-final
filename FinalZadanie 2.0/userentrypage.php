<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>


<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="tracelist.php"> Trace List</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="userentrypage.php">Add map</a>
        </li>
        <li class="nav-item">
            <a class="nav-link navbar-right" href="performance.php">Activities</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php"> Logout</a>
        </li>
    </ul>
</nav>

<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/3/2018
 * Time: 7:05 PM
 */
require_once "db.php";
session_start();
if (!isset($_SESSION['checktoken']) &&  empty($_SESSION['checktoken']))  // Kontrola či je užívateľ prihlásený
    header("location: login.php");
?>
<div id="map"></div>

<script>
    function initMap() {  //vykreslenie mapy
        var slovakia = new google.maps.LatLng(48.5, 19.1);
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: slovakia  // Slovakia
        });

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({
            draggable: true,
            map: map,
            panel: document.getElementById('right-panel')
        });

        document.getElementById('mode').addEventListener('change', function() {
            displayRoute(directionsService, directionsDisplay);
        });

        var onChangeHandler = function() {
            displayRoute(directionsService, directionsDisplay);
        };
        document.getElementById('A').addEventListener('change', onChangeHandler);
            document.getElementById('B').addEventListener('change', onChangeHandler);
    }

    function displayRoute(service, display) {
        var selectedMode = document.getElementById('mode').value;
        service.route({
            origin: document.getElementById('A').value,
            destination: document.getElementById('B').value,
            travelMode: google.maps.TravelMode[selectedMode],
            avoidTolls: true
        }, function(response, status) {
            if (status === 'OK') {
                display.setDirections(response);
            } else if(document.getElementById('B').value !== "" && status !== 'OK'){
                alert('Could not display directions due to: ' + status);
            }
        });
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlYOB9FDe53dD9uO9RA3ZGOBlvCLKdCyA&callback=initMap">
</script>

<form action="tracelist.php" method="POST" class="form-inline center">
    <div class="form-group">
    <label>Start point</label> <input type="text" name= "start" id="A" class="input" class="form-control" autocomplete="off"/><br>
    </div>
    <div class="form-group">
    <label>Destination point</label><input type="text" name="end" id="B" class="input" class="form-control" autocomplete="off"/><br/>
    </div>
    <div id="floating-panel" class="form-group">
        <b>Mode of Travel: </b>
        <select name ="travelmode" id="mode">
            <option value="WALKING">Walking</option>
            <option value="BICYCLING">Bicycling</option>
        </select>
    </div>
    <input type="submit" name="submittrace" class="button button-primary" value="Save Trace" />
</form>

<!--<form action="tracelist.php">
    <input type="submit" name="submit2" class="button button-secondary" value="View your traces" />
</form>-->
