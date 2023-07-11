<?php
//session_start();
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-08
 * Time: 14:00
 */

require "models/Worker.php";



//print_r($_SESSION['user']);
//print_r($incomingWorker);
//Worker::deleteCredentialsFromSession();

//print_r($_SESSION['user']);

//print_r($_SESSION);

if(!empty($_GET['logoff']))
{
    Worker::deleteCredentialsFromSession();
    header("Location: login.php");
}

Worker::isLoggedIn();

if(isset($_POST['submit']))
{
    $incomingWorker = new Worker(null, "", "", "", null);
    $incomingWorker->setEmail(isset($_POST['email']) ? $_POST['email'] : "");
    $incomingWorker->setPassword(isset($_POST['password']) ? $_POST['password'] : "");

    $validated = $incomingWorker->validate();
    //print_r($incomingWorker->getErrors());
    //$credentialsChecked = $incomingWorker->checkCredentials();

    if($validated)
    {
        $incomingWorker->saveCredentialsToSession();
        header("Refresh:0");
        header("Location: shooters.php");
        exit();
    }
    else
    {

    }
}





?>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<!------ Include the above in your HEAD tag ---------->

<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/indexStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>
<style>
    .row{margin-top:10%}
</style>
<section class="container-fluid">
    <div class="row justify-content-center  ">

        <div class="col-6 rounded border shadow p-3 mb-5 bg-white " id="col-Login" >
            <p class="text-center"><strong><?php
                    if(isset($incomingWorker))
                    {
                        if($incomingWorker->getErrors() != null & isset($incomingWorker->getErrors()['login']))
                        {
                            echo $incomingWorker->getErrors()['login'];
                        }
                    }
                    else
                    {
                        echo "RWK All IN Erfassung";
                    }
                    ?> </strong></p>

            <form class="login-form" action="login.php" method="POST">
                <div class="form-group" id="errorLogin" >
                </div>
                <div class="form-group">
                    <label>Benutzername</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Benutzername" required>
                    <?php if(isset($incomingWorker) && isset($incomingWorker->getErrors()['email'])) echo "<div class='error'>" . $incomingWorker->getErrors()['email'] . "</div>"?>
                </div>

                <div class="form-group">
                    <label>Passwort</label>
                    <input type="password" class="form-control"  id="password" name="password"  placeholder="Passwort" required>
                    <?php if(isset($incomingWorker) && isset($incomingWorker->getErrors()['password'])) echo "<div class='error'>" . $incomingWorker->getErrors()['password'] . "</div>"?>

                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary float-right"></input>
                </div>
                <div class="form-group">
                    <?php //if(isset($incomingWorker->getErrors()['login'])) echo "<div class='error'" .$incomingWorker->getErrors()['login']. "</div>" ?>
                </div>
                </div>
            </form>

        </div>
    </div>
</section>
</body>
</html>


