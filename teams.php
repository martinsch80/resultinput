<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

require "models/User.php";
require "models/Discipline.php";
require "models/Round.php";
require "models/Team.php";

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

if(isset($_GET['roundId']))
{
    $roundId = $_GET['roundId'];
}

$discrictId = substr($user->getUsrCode(), 0, 3);
?>

<html>
<head>
    <title>Ergebniseingabe</title>
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
            <p class="text-center"><strong>Team</strong></p>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="disciplines.php">Diszipline</a></li>
                    <li class="breadcrumb-item"><a href="rounds.php?disciplineId=<?=$disciplineId?>">Runde</a></li>
                    <li class="breadcrumb-item">Gilde</li>
                    <li class="breadcrumb-item active" aria-current="page">Team</li>
                    <li class="breadcrumb-item">Ergebniseingabe</li>
                </ol>
            </nav>

            <div class="form-group">
                <table class="table table-striped">
            <?php

           
            $round = Round::get($roundId);

            
            echo '<tr>';
            echo '<th>RUNDE</th>';
            echo '<td>'.$round->getRound().'</td>';
            echo '</tr>';

            $discipline = Discipline::get($disciplineId);
            echo '<tr>';
            echo '<th>DISZIPLIN</th>';
            echo '<td>'.$discipline->getName().'</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th>Gilde</th>';
            echo '<td>'.$user->getUsrCode().'</td>';
            echo '</tr>';
            ?>
            </table>
            </div>
            <div class="form-group">
            <table class="table table-striped">
            <?php

            $teams = Team::getByDisciplineAndCode($disciplineId, $user->getUsrCode());

            if(count($teams)>0){
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Name</th>";
                echo "<th>Klasse</th>";
                echo "<th>Discipline</th>";
                echo "<th>Auswählen</th>";
                echo "</tr>";
                foreach ($teams as $team)
                {
                    echo "<tr>";
                    echo "<td>" . $team->getId() ."</td>";
                    echo "<td>" . $team->getName() ."</td>";                
                    echo "<td>" . $team->getClass() ."</td>";
                    echo "<td>" . $team->getDiscipline() ."</td>";
                    echo "<td>";
                    echo '<a class="btn btn-success" href="round_input.php?disciplineId='.$disciplineId.'&roundId='.$roundId.'&teamId=' . $team->getId() . '"><i class="fa fa-x fa-pincel"></i>Select</a>';
                    echo "&nbsp";
                    echo "</td>";
                    echo "</tr>";    
                }
            
            }
            else{
                echo "Keine Mannschaftswertung";
            }

            ?>
            
            </table>
            <a class="btn btn-success" href="#"><i class="fa fa-x fa-plus"></i> Einzelschützen</a>
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
