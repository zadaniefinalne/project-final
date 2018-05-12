<?php
include 'db.php';
$msg='';
if(!empty($_POST['email']) && isset($_POST['email']) &&  !empty($_POST['password']) &&  isset($_POST['password']) )
{
// username and password sent from Form
    $email=mysqli_real_escape_string($connection,$_POST['email']);
    $password=mysqli_real_escape_string($connection,$_POST['password']);
    $meno = mysqli_real_escape_string($connection,$_POST['meno']);
    $priezvisko = mysqli_real_escape_string($connection,$_POST['priezvisko']);
    $strednaskola = mysqli_real_escape_string($connection,$_POST['strednaskola']);
    $strednaskolaadresa = mysqli_real_escape_string($connection,$_POST['strednaskolaadresa']);
    $bydliskoulica = mysqli_real_escape_string($connection,$_POST['bydliskoulica']);
    $PSC = mysqli_real_escape_string($connection,$_POST['PSC']);
    $bydliskoobec = mysqli_real_escape_string($connection,$_POST['bydliskoobec']);
    $uzivatel = "1";   // admin = 0, uzivatel = 1, anonym = 2

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';

    if(preg_match($regex, $email))
    {
        $password=md5($password); // Encrypted password
        $activation=md5($email.time()); // Encrypted email+timestamp

        $count=mysqli_query($connection,"SELECT id FROM FinalZadanie WHERE email='$email'");
        if(mysqli_num_rows($count) < 1)
        {
            mysqli_query($connection,"INSERT INTO FinalZadanie(email,pass,activation,meno,priezvisko,strednaskola,strednaskolaadresa,bydliskoulica,PSC,bydliskoobec,TypUzivatela) VALUES('$email','$password','$activation','$meno','$priezvisko','$strednaskola','$strednaskolaadresa','$bydliskoulica','$PSC','$bydliskoobec', '$uzivatel');");

            include 'smtp/Send_Mail.php';
            $to=$email;
            $subject="Email verification";
            $body='Hi, <br/> <br/> We need to make sure you are human. Please verify your email and get started using your Website account. <br/> <br/> <a href="'.$base_url.'activation.php?code='.$activation.'">'.$base_url.'activation/'.$activation.'</a>';
            Send_Mail($to,$subject,$body);

            $msg= "Registration successful, please activate email.";

        }
        else
        {
            $msg= '<font color="#cc0000">The email is already taken, please try new.</font>';
        }



    }
    else
    {
        $msg = '<font color="#cc0000">The email you have entered is invalid, please try again.</font>';
    }


}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="main">
    <h1 class="center">Registration</h1>

    <form action="" method="post" class="center">
        <label>Email</label><br><input type="text" name="email" class="input" autocomplete="off"/><br>
        <label>Password </label><br><input type="password" name="password" class="input" autocomplete="off"/><br/>
        <label> Firstname</label><br><input type="text" name="meno" class="input" autocomplete="off"/><br>
        <label> Surname</label><br><input type="text" name="priezvisko" class="input" autocomplete="off"/><br>
        <label> High school</label><br><input type="text" name="strednaskola" class="input" autocomplete="off"/><br>
        <label> High school - address</label><br><input type="text" name="strednaskolaadresa" class="input" autocomplete="off"/><br>
        <label> Address - street</label><br><input type="text" name="bydliskoulica" class="input" autocomplete="off"/><br>
        <label> Postal code</label><br><input type="text" name="PSC" class="input" autocomplete="off"/><br>
        <label> Address - city</label><br><input type="text" name="bydliskoobec" class="input" autocomplete="off"/><br>
        <input type="submit" class="button button-primary" value="Registration" /> <span class='msg'><?php echo $msg; ?></span>
    </form>

    <div class="center">
    <a href="login.php"> <br>Already a member? Click to login.</a>
    </div>
    </div>
</body>
</html>