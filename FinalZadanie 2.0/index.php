<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>

<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="registration.php"> Sign Up</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php"> Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dokum.php"> Documentation</a>
        </li>
    </ul>
</nav>

<script src="map/scriptlocationOfUsers.js"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3ZrhOr3gSOT0q8ezzVGPF6ngHt4683eQ&libraries=geometry,places&callback=initMap">
</script>

Zobrazenie registrovaných uživateľov podľa: <br>
<input type="radio" id="skola" name="check" onclick="vyberSkolaBydlisko()" value="skola" checked/> školy <br>
<input type="radio" id="mesto" name="check" onclick="vyberSkolaBydlisko()" value="mesto"/> bydliska


<div id="map"></div>