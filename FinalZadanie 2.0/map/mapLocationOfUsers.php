<?php
/**
 * Created by PhpStorm.
 * User: frantisek.ff
 * Date: 19. 4. 2018
 * Time: 18:43
 */
require_once('config.php');


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>

    <title>2018| Domov Z7</title>

    <link rel="stylesheet" type="text/css" href="style.css">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script src="scriptlocationOfUsers.js"></script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3ZrhOr3gSOT0q8ezzVGPF6ngHt4683eQ&libraries=geometry,places&callback=initMap">
    </script>

    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 400px;
            width: 100%;
        }

        /* Optional: Makes the sample page fill the window. */

    </style>


</head>
<body>
Zobrazenie registrovaných uživateľov podľa: <br>
 <input type="radio" id="skola" name="check" onclick="vyberSkolaBydlisko()" value="skola"/> školy <br>
<input type="radio" id="mesto" name="check" onclick="vyberSkolaBydlisko()" value="mesto"/> bydliska


<div id="map"></div>
<!-- /.container -->


</body>

</html>
