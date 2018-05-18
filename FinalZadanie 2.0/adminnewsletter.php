<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>


<nav class="navbar navbar-expand-sm bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="adminentrypage.php">Add User's Form CVS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admintracelist.php">Trace List</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminaddtrace.php">Add Traces</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminteams.php">Teams</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminaddteam.php">Add Team</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminusersoverview.php">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminnewsletter.php"> Newsletter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php">Logout</a>
        </li>

    </ul>
</nav>
<?php
require_once "db.php";
require_once 'smtp/Send_Mail.php';
session_start();
if (!isset($_SESSION['checktokenadmin']) && empty($_SESSION['checktokenadmin']))
    header("location: login.php");
?>
<br>
<div class="row">

    <div class="col-md-4 offset-4 ">
        <form action="adminnewsletter.php" method="post">
            <label>Nadpis:</label> <br><input type="text" name="nadpis" value=""><br>
            <label>Obsah:</label><br>
            <textarea name="content" rows="10" cols="30"></textarea>
            <br>
            <label></label><input type="submit" value="Vytvoriť" name="vytvorit">

        </form>
    </div>
</div>

<?php

require_once('map/config.php');

if (isset($_POST['vytvorit'])) {
    $nadpis = trim($_POST['nadpis']);
    $content = trim($_POST['content']);
    $date = date("Y-m-d H:i:s", time());

    if (!empty($content) && !empty($nadpis)) {
        $sql = "INSERT INTO NewsLetter( caption, content, time) VALUES (?, ?, ?)";


        $pdo->prepare($sql)->execute([$nadpis, $content, $date]);
        //header("Location: ../index.php");
    } else {
        echo "<p>Vyplnte udaje</p>";
    }
}

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
<div class="row">
    <div class="col-md-4 offset-4 " style="word-wrap: break-word;">
        <div class="col-md-4 offset-4 ">
            <form action="adminnewsletter.php" method="post">
                <input type="submit" value="Odoslat novinky na mail" name="odoslat">
            </form>
        </div>
    </div>
</div>
<?php
if (isset($_POST['odoslat'])) {

    $data = $pdo->query("SELECT * FROM `NewsLetter` ORDER BY time DESC LIMIT 3 ");
    $emails = $pdo->query("SELECT email FROM `FinalZadanie` WHERE newsletter = 1");
    $body = 'Dobrý deň, <br/> <br/> posielame Vám aktuality z našej stránky.';
    foreach ($data as $row2) {
        $body .=  "<h2>" . $row2["caption"] . "</h2> </br>" .
            "<div >" . $row2["content"] . "</div> </br>";
    };
echo 'Odoslane na tieto maily:' . '</br>';
foreach ($emails as $row) {
    $to = $row['email'] ;
    $subject = "Novinky s finalneho zadania";


    echo $row['email'] . '</br>';
    Send_Mail($to, $subject, $body);
}

}
?>

</body>
</html>