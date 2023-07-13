<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

require "models/User.php";
require "models/Round.php";

//Worker::deleteCredentialsFromSession();
//print_r($_SESSION['user']);

//echo Worker::getCredentialsFromSession()->getEmail();
//print_r(Project::getDuration());


if(isset($_SESSION['user'])) {
    $user = User::getCredentialsFromSession();
    //die($worker->getEmail());
    if ($user->isLoggedIn())
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

if(isset($_GET['disciplineId']))
{
    $disciplineId = $_GET['disciplineId'];
}

$discrictId = substr($user->getUsrCode(), 0, 3);
?>

<html>
<head>
    <title>Runden</title>
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
            <p class="text-center"><strong>Runden</strong></p>

            <div class="form-group">
                <table class="table table-striped">
            <?php

            $rounds = Round::getAllByDistrictAndDiscipline($discrictId, $disciplineId);

           
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Runde</th>";
                echo "<th>Start</th>";
                echo "<th>Ende</th>";
                echo "<th>Auswählen</th>";
                echo "<th></th>";
                echo "</tr>";
                foreach ($rounds as $round)
                {
                echo "<tr>";
                echo "<td>" . $round->getId() ."</td>";
                echo "<td>Runde " . $round->getRound() ."</td>";                
                echo "<td>" . $round->getStart() ."</td>";
                echo "<td>" . $round->getStop() ."</td>";
                echo "<td>";
                echo '<a class="btn btn-info" href="view_discipline.php?id=' . $round->getId() . '"><i class="fa fa-x fa-eye"></i>Details</a>';
                echo '<a class="btn btn-success" href="round_input.php?disciplineId='.$disciplineId.'&roundId=' . $round->getId() . '"><i class="fa fa-x fa-pencil"></i>Select</a>';
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
