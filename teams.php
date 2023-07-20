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
require "models/Team.php";
require "models/Verein.php";

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

if(isset($_SESSION['saison']))
{
    $saison = $_SESSION['saison'];
}

if(isset($_GET['verein']))
{
    $_SESSION['verein'] = $_GET['verein'];
}

if(isset($_SESSION['verein']))
{
    $verein = $_SESSION['verein'];
}else{
    $verein = $user->getUsrCode();
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


echo '<html>';
renderHeader("Teams");
echo '<body>';
$discipline = Discipline::get($disciplineId);
?>

<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-11 mb-11 bg-white " id="col-Login" >
            <?php
            headLine("Team");
            userLine($user);
            crumbBar(4, $user->getRight()>0, $disciplineId,$roundId);
                      
            $round = Round::get($roundId);
            seasonSelector($discipline);
            infoTableStart();   
            infoTableRow("Saison", $saison);  
            infoTableRow("DISZIPLIN", $discipline->getName());
            infoTableRow("RUNDE", $round->getRound());      
            infoTableRow("Eingabe", getRoundRange($round, $user));              
            infoTableRow("Gilde", Verein::get($verein)->getName());
            infoTableEnd();
            ?>
            <div class="form-group">
            <table class="table table-striped">
            <?php

            $teams = Team::getByDisciplineAndCode($disciplineId, $verein);

            if(count($teams)>0){
                echo "<tr>";
                echo "<th class='colID'>ID</th>";
                echo "<th>Name</th>";
                echo "<th class='colSelect'>Auswählen</th>";
                echo "</tr>";
                foreach ($teams as $team)
                {
                    echo "<tr>";
                    echo "<td>" . $team->getId() ."</td>";
                    echo "<td>" . utf8_convert($team->getName()) ."</td>";       
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
            echo "</table>";
            echo '<a class="btn btn-success" href="round_input_ezw.php?disciplineId='.$disciplineId.'&roundId='.$roundId.'"><i class="fa fa-x fa-plus"></i> Einzelschützen</a>';
            $backLink = "rounds.php?disciplineId=".$disciplineId;
            if($user->getRight()>0) $backLink = "vereins.php?disciplineId=".$disciplineId.'&roundId='.$roundId;
            backButton($backLink);
            ?>
            
        </div>
    </div>
</section>

<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>
