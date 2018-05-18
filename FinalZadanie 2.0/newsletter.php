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
            <a class="nav-link" href="newsletter.php"> Newsletter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php"> Logout</a>
        </li>

    </ul>
</nav>
<?php
require_once "db.php";
session_start();
if (!isset($_SESSION['checktoken']) && empty($_SESSION['checktoken']))  // Kontrola či je užívateľ prihlásený
    header("location: login.php");

require_once('map/config.php');
$data = $pdo->query("SELECT * FROM `NewsLetter` ORDER BY time DESC LIMIT 3 ");
?>


<div class="col-md-4 offset-4 " style="word-wrap: break-word;">
    <h1>Aktuality</h1>

    <?php

    foreach ($data as $row) {
        echo
            "<h2>" . $row["caption"] . "</h2> </br>" .
            "<div >" . $row["content"] . "</div> </br>";

    }
    ?>

</div>

<br>
<div class="row">

    <div class="col-md-4 offset-4 " style="word-wrap: break-word;">
        <label for="">Odoberanie noviniek na mail</label>
        <div class="col-md-4 offset-4 ">
            <form action="newsletter.php" method="post">
                <br>Prihlasit sa  <input type="radio" id="prihlasit" name="newsletter" value="Prihlasit sa" checked><br>
                <br>Odhlasit s odberu  <input type="radio" id="odhlasit" name="newsletter" value="Odhlasit s odberu"><br>
                <br>
                <label></label><input type="submit" value="Odoslat ziadost" name="odoslat">


            </form>
        </div>
    </div>
</div>


<?php

require_once('map/config.php');

if (isset($_POST['odoslat'])){
    $inputvalue = trim($_POST['newsletter']);
    if($inputvalue == "Prihlasit sa")
        $inputvalue = 1;
    else{

        $inputvalue = 0;
    }

    $date = date("Y-m-d H:i:s", time());
    $mail = $_SESSION['loginemail'];


        $sql = "UPDATE `FinalZadanie` SET newsletter = 1 WHERE email = ? ";
        $pdo->prepare($sql)->execute([$mail]);
        //header("Location: ../index.php");

}

$data = $pdo->query("SELECT * FROM `NewsLetter` ORDER BY time DESC LIMIT 3 ");
?>
