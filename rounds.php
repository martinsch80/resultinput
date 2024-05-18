<?php
session_start();
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

include('Html.php');

require "models/User.php";
require "models/Discipline.php";
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


echo '<html>';
renderHeader("Runden");
echo '<body>';
$discipline = Discipline::get($disciplineId);

if(isset($_SESSION['saison']))
{
    $saison = $_SESSION['saison'];
}
else{
    $saison = date("Y");
    if(strtolower($discipline->getSeason()) == "w"){
        if(date("m")>8){
            $saison = $saison . " / " . $saison+1;
        }
        else{
            $saison = $saison-1 . " / " . $saison;
        }
    }
    $_SESSION['saison'] = $saison;
}
?>

<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-11 mb-11 bg-white " id="col-Login" >
            <?php
                headLine("Runden");
                userLine($user);
                crumbBar(2, $user->getRight()>0, $disciplineId);
            
            seasonSelector($discipline);
            infoTableStart();
            infoTableRow("Saison", $saison);  
            infoTableRow("DISZIPLIN", $discipline->getName());
            infoTableEnd();
            ?>
            <div class="form-group">
                <table class="table table-striped">
            <?php


            $rounds = Round::getAllByDistrictAndDiscipline($discrictId, $discipline);

            $selectLink = 'teams.php';
            if($user->getRight() == 1){
                $selectLink = 'vereins.php';
            }
            $selectLink .= '?disciplineId='.$disciplineId.'&roundId=';

            echo '</table>';
            echo '<table class="table table-striped">';

            echo "<tr>";
            echo "<th class='colID'>ID</th>";
            echo "<th>Runde</th>";
            echo "<th class='colSelect'>Auswählen</th>";
            echo "</tr>";
            foreach ($rounds as $round)
            {
            echo "<tr>";
            echo "<td>" . $round->getId() ."</td>";
            echo "<td><strong>Runde " . $round->getRound() ."</strong><br/>";                
            echo "Start: " . formatDateString($round->getStart()) ."<br/>";
            echo "Ende: " . formatDateString($round->getStop()) ."</td>";
            echo "<td>";
            if(true || strtotime($round->getStart()) < strtotime('now') && strtotime($round->getStop()) > strtotime('now'))  {
                echo '<a class="btn btn-success" href="'.$selectLink . $round->getId().'"><i class="fa fa-x fa-pencil"></i> Select</a>';
            }
            echo "&nbsp";
            echo "</td>";
            echo "</tr>";
            
            }
            echo "</div>";
            echo "</table>";        
            backButton("disciplines.php");
            ?>
        </div>
    </div>
</section>

<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>
