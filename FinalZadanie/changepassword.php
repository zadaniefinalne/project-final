<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/3/2018
 * Time: 1:41 PM
 */

require_once "db.php";
session_start();
if (!isset($_SESSION['checktoken']) &&  empty($_SESSION['checktoken']))
    header("location: login.php");


$checkpw = $_SESSION['loginheslo'];
$name = $_SESSION['loginemail'];
if ($checkpw == 'c21f969b5f03d33d43e04f8f136e7682'){ // Ak heslo sa zhoduje s "default" (v resp. md5(default)) tak vyziadaj zmenu hesla
    echo "This account was created and added to database by admin. Please change your password immediately.";
    ?>
    <form action="" method="post">
        <label>New password</label> <input type="password" name="zmenheslo" class="input" autocomplete="off"/>
        <input type="submit" name="submit3" class="button button-primary" value="Login" />
    </form>
<?php
}
else header("location: userentrypage.php"); // klasicky redirect ak sa prihlasil normalny uzivatel

if(isset($_POST['submit3'])) {  // update noveho pw po vyplneni formy a taktiež presmerovanie
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $con = mysqli_connect($hostname, $username, $password, $dbname);
        mysqli_set_charset($con, 'utf8');
        $newpw = md5($_POST['zmenheslo']);
        $sql = "UPDATE FinalZadanie SET pass = '$newpw' WHERE email = '$name'";
        mysqli_query($con, $sql);
        mysqli_close($con);
        header("location: userentrypage.php");
    }
    }



?>