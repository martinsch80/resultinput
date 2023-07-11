<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

require "models/Worker.php";
require "models/Discipline.php";

//Worker::deleteCredentialsFromSession();
//print_r($_SESSION['user']);

//echo Worker::getCredentialsFromSession()->getEmail();
//print_r(Project::getDuration());


if(isset($_SESSION['user'])) {
    $worker = Worker::getCredentialsFromSession();
    //die($worker->getEmail());
    if ($worker->checkCredentials())
    {
        //
    }
    else
    {
        header("Location: login.php");
        //Worker::deleteCredentialsFromSession();
        exit();
    }
}
else
{
    header("Location: login.php");
    //Worker::deleteCredentialsFromSession();
    exit();
}

if(isset($_POST['submit']))
{
    if(!empty($_POST['workerId']))
    {
        Worker::updateProject($_POST['workerId'], $_POST['project']);
    }
    else
    {
        Activity::updateProject($_POST['activityId'], $_POST['project']);
        //Worker::updateProject($_POST['worker'], $_POST['project']);
        //Worker::updateProject($_POST['worker'],$_POST['project']);
    }

}

?>

<html>
<head>
    <title>Diszipline</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/indexStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body>





<style>
    .row{margin-top:10%}
</style>
<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-8 rounded border shadow p-3 mb-5 bg-white " id="col-Login" >
            <p class="text-center"><strong>Disziplinen</strong></p>

            <div class="form-group">
                <table class="table table-striped">
            <?php

            $disziplines = Discipline::getAll();

           
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Name</th>";
                echo "<th>Sasison</th>";
                echo "<th>Auswählen</th>";
                echo "<th></th>";
                echo "</tr>";
                foreach ($disziplines as $diszipline)
                {
                echo "<tr>";
                echo "<td>" . $diszipline->getId() ."</td>";
                echo "<td>" . $diszipline->getName() ."</td>";                
                echo "<td>" . $diszipline->getSeason() ."</td>";
                echo "<td>";
                echo '<a class="btn btn-info" href="view_discipline.php?id=' . $diszipline->getId() . '"><i class="fa fa-x fa-eye"></i></a>';
                echo "&nbsp";
                echo "</td>";
                echo "</tr>";

            }

            ?>

            </div>
            </table>
        </div>
    </div>
</section>

<section class="col-12 text-center">
    <form action="login.php" type="GET">

        <input type="submit"  name="" value="Logout" class="btn btn-dark">
        <input type="hidden" name="logoff" value="y">

    </form>

</section>

</body>
</html>
