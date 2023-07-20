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
require "models/TeamResults.php";
require "models/Shooter.php";
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

if(isset($_GET['disciplineId']))
{
    $disciplineId = $_GET['disciplineId'];
}

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

if(isset($_SESSION['verein']))
{
    $verein = $_SESSION['verein'];
}else{
    $verein = $user->getUsrCode();
}

if(isset($_GET['roundId']))
{
    $roundId = $_GET['roundId'];
}

if(isset($_GET['teamId']))
{
    $teamId = $_GET['teamId'];
}

$teamResults = TeamResults::getBySeasonAndTeamIdAndRoundId($saison,  $roundId, $teamId);
if(count($teamResults)>0){
    $teamResult = $teamResults[0];
}

$discrictId = substr($user->getUsrCode(), 0, 3);

if(!empty($_POST))
{
    $homeTeamShooters = $_POST['homeTeamShooter'];
    $homeTeamResults = $_POST['homeTeamResult'];
    
    $guastTeamShooters = $_POST['guastTeamShooter'];
    $guastTeamResults = $_POST['guastTeamResult'];
    
    $teamResult->setData($homeTeamShooters, $homeTeamResults,  $guastTeamShooters, $guastTeamResults);

    if($teamResult->validate())
    {
        $teamResult->update();
    }
}


echo '<html>';
renderHeader("Ergebnisseingabe");
echo '<body>';



?>
<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-11 mb-11 bg-white " id="col-Login" >
            
            <?php 
            headLine("Ergebnisseingabe");
            userLine($user);
            ?>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="disciplines.php">Diszipline</a></li>
                    <li class="breadcrumb-item"><a href="rounds.php?disciplineId=<?=$disciplineId?>">Runde</a></li>
                    <li class="breadcrumb-item">Gilde</li>
                    <li class="breadcrumb-item"><a href="teams.php?disciplineId=<?=$disciplineId?>&roundId=<?=$roundId?>">Team</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ergebniseingabe</li>
                </ol>
            </nav>


            <div class="form-group">
                <table class="table table-striped">
            <?php

            if(!isset($teamResult)){
                echo "Keine Begegnung für die gewählte Manschaft gefunden";
                echo "<br/>";
                echo "<br/>";
                
                backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId);
                return;
            }

            seasonSelector($discipline);
            infoTableStart();

            $round = Round::get($roundId);    
            infoTableRow("Saison", $saison);   
            infoTableRow("DISZIPLIN", $discipline->getName());
            infoTableRow("RUNDE", $round->getRound());
            infoTableRow("Eingabe", "Start: " . formatDateString($round->getStart()). " Ende: " . formatDateString($round->getStop()));
            infoTableRow("Gilde", Verein::get( $verein)->getName()); 
            infoTableEnd();           

            $homeTeamId = $teamResult->getHomeTeamId();
            $guaestTeamId = $teamResult->getGuestTeamId();
            $homeTeam = Team::get($homeTeamId);
            if($guaestTeamId){
                $guaestTeam = Team::get($guaestTeamId);
                $guestTeamName = $guaestTeam->getName();
            }
            else{
                $guestTeamName = "Freilos";
            }
            $visit =  utf8_convert($homeTeam->getName()) . " vs " . utf8_convert($guestTeamName) ;

            infoTableRow("Begegnung", $visit);


            $shooters = Shooter::getAllByPassNr($homeTeam->getCode(), $discipline->getWeapon());
            
            $disabled = $user->getRight() == 1 || strtotime($round->getStart()) < strtotime('now') && strtotime($round->getStop()) > strtotime('now')?"":"disabled";

            ?>

            </div>
            </table>
            <form action="#" method="POST">
           
            <p class="text-center"><strong>Heim: <?=utf8_convert($homeTeam->getName())?></strong></p>

            <div class="form-group">
                <table class="table table-striped">
            <?php

                echo "<tr>";
                echo "<th class='colID'>Schütze</th>";
                echo "<th>Name</th>";
                echo "<th class='colResult'>Ergebnis</th>";
                echo "</tr>";

                for($i=1; $i <= $discipline->getPsize(); $i++) {
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td><select ". $disabled ." class='shooterSelect form-control' name='homeTeamShooter[]'>";
                    echo "<option value=''>Schütze auswählen</option>";
                    foreach ($shooters as $shooter)
                    {
                        echo "<option ";
                        if($teamResult->getShooterNr($i, $homeTeamId) == $shooter->getPassNr()) echo "selected ";                        
                        echo "value='".$shooter->getPassNr()."'>".utf8_convert($shooter->getName())."</option>";
                    }
                    echo "</select></td>";
                    echo "<td><input name='homeTeamResult[]' ". $disabled ." class='shooterResult form-control' type='number' value='".$teamResult->getShooterResult($i, $homeTeamId)."'/></td>";
                    echo "</tr>"; 
                 }
                 echo "<tr>";
                 echo "<td></td>";
                 echo "<td><strong>Gesamt:</strong></td>";
                 echo "<td id='total' class='shooterResult'><strong>".$teamResult->getTeamResult($homeTeamId)."</strong></td>";
                 echo "</tr>";
            ?>
                </table>
            <?php
            if( $guaestTeamId){
                $shooters = Shooter::getAllByPassNr($guaestTeam->getCode(), $discipline->getWeapon());
                ?>
                <p class="text-center"><strong>Gast: <?=utf8_convert($guaestTeam->getName())?></strong></p>
            <div class="form-group">
                <table class="table table-striped">
            <?php

                echo "<tr>";
                echo "<th class='colID'>Schütze</th>";
                echo "<th>Name</th>";
                echo "<th class='colResult'>Ergebnis</th>";
                echo "</tr>";

                for($i=1; $i <= $discipline->getPsize(); $i++) {
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td><select ". $disabled ." class='shooterSelect form-control' name='guastTeamShooter[]'>";
                    echo "<option value=''>Schütze auswählen</option>";
                    foreach ($shooters as $shooter)
                    {
                        echo "<option ";
                        if($teamResult->getShooterNr($i,  $guaestTeamId) == $shooter->getPassNr()) echo "selected ";                        
                        echo "value='".$shooter->getPassNr()."'>".utf8_convert($shooter->getName())."</option>";
                    }
                    echo "</select></td>";
                    echo "<td><input name='guastTeamResult[]' ". $disabled ." class='shooterResult form-control' type='number' value='".$teamResult->getShooterResult($i,  $guaestTeamId)."'/></td>";
                    echo "</tr>";
                 }
                 echo "<tr>";
                 echo "<td></td>";
                 echo "<td><strong>Gesamt:</strong></td>";
                 echo "<td id='total' class='shooterResult'><strong>".$teamResult->getTeamResult( $guaestTeamId)."</strong></td>";
                 echo "</tr>";
                echo "</table>";
            }
                if(empty($disabled)){
                    echo '<input  type="submit" class="btn btn-success" href="#"/>';
                }

                echo '<input type="hidden" name="disciplineId" value="'.$disciplineId.'">';
                echo '<input type="hidden" name="roundId" value="'.$roundId.'">';
                echo '<input type="hidden" name="teamId" value="'.$teamId.'">';
                backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId);
                ?>
                </div>
            </form>
        </div>
    </div>
</section>


<script type="text/javascript">
    $("input").change(function(){
        var amount = 0;
        $(".shooterResult").each( function() {
            var val = parseFloat($(this)[0].value)
            if(val) amount += val;
        })
        $("#total").text(amount)
    });
</script>
<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>



