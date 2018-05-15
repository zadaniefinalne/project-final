<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/3/2018
 * Time: 1:37 PM
 */
require_once "db.php";
session_start();
unset($_SESSION['checktokenadmin']);
unset($_SESSION['checktoken']);
if(isset($_POST['submit2'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
unset($_SESSION['checktokenadmin']);
}
}

if(isset($_POST['submit'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $_SESSION['loginemail'] = $_POST['email'];
    $_SESSION['loginheslo'] = md5($_POST['heslo']);
    $mail = $_SESSION['loginemail'];
    $heslo = $_SESSION['loginheslo'];

    if ($_POST['email'] == "admin@admin.admin" && $_POST['heslo'] == "123456")
    {
        $_SESSION['checktokenadmin'] = 'setadmin';
        header("location: adminentrypage.php");
        }

    $sql = "SELECT email, pass FROM FinalZadanie WHERE email = '$mail' AND TypUzivatela != 0";
    $result = mysqli_query($connection, $sql);
    $row= mysqli_fetch_row($result);

    if (mysqli_num_rows($result)>=1) { //ak existuje v DB

        if ($heslo == $row[1]) { // kontrola hesla
            mysqli_close($connection);
            $_SESSION['checktoken'] = 'set';
            header("location: changepassword.php");
        } else {
            mysqli_close($connection);
            header("location: login.php");
        }

    }
}

}
?>

<link rel="stylesheet" type="text/css" href="style.css">

<form action="" method="post" class="center">
    <label>Email</label><br><input type="email" name="email" class="input" autocomplete="off"/><br>
    <label>Password</label><br><input type="password" name="heslo" class="input" autocomplete="off"/><br/>
    <input type="submit" name="submit" class="button button-primary" value="Login" />
</form>

<div class="center">
<a href="registration.php">Not a member yet? Click to register</a>
</div>
