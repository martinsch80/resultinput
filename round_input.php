<?php
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

if(isset($_GET['teamId']))
{
    $teamId = $_GET['teamId'];
}

$teamResult = TeamResults::getBySeasonAndTeamIdAndRoundId("2022 / 2023",  $roundId, $teamId)[0];
$discrictId = substr($user->getUsrCode(), 0, 3);


echo '<html>';
renderHeader("Ergebnisseingabe");
echo '<body>';

?>
<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-11 mb-11 bg-white " id="col-Login" >
            

            
            <div class="container-fluid">
                <div class="row">
                    <div class="col col-lg-2 col-md-4">
                        <strong>Saison: 
                            <select>
                                <option>2023 / 2024</option>
                                <option>2023</option>
                                <option>2022 / 2023</option>
                                <option>2022</option>
                                <option>2021 / 2022</option>                        
                            </select>
                        </strong>   
                    </div> 
                    <div class="col">
                    <p class="text-center"><strong>Ergebnisseingabe</strong></p>
                    </div>
                    <div class="col col-lg-1">
                    <p class="text-center"><i class="fa fa-x fa-info"></i></p>
                    </div>
                </div>
            </div>

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

            $round = Round::get($roundId);     
            infoTableRow("RUNDE", $round->getRound());
            
            $discipline = Discipline::get($disciplineId);            
            infoTableRow("DISZIPLIN", $discipline->getName());

            $team = Team::get($teamId);
            infoTableRow("TEAM", $team->getName());

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
            $visit =  $homeTeam->getName() . " vs " . $guestTeamName ;

            infoTableRow("Begegnung", $visit);


            $shooters = Shooter::getAllByPassNr($homeTeam->getCode());

            ?>

            </div>
            </table>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                    Schütze
                    </div> 
                    <div class="col-12 col-md-4">
                    Name
                    </div>
                    <div class="col-12 col-md-4">
                    Ergebnis
                    </div>
                </div>
            </div>

            <p class="text-center"><strong>HOME: <?=$homeTeam->getName()?></strong></p>
            <div class="form-group">
                <table class="table table-striped">
            <?php

                echo "<tr>";
                echo "<th>Schütze</th>";
                echo "<th>Name</th>";
                echo "<th>Ergebnis</th>";
                echo "</tr>";

                for($i=1; $i <= $discipline->getPsize(); $i++) {
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td><select>";
                    echo "<option value=''>Schütze auswählen</option>";
                    foreach ($shooters as $shooter)
                    {
                        echo "<option ";
                        if($teamResult->getShooterNr($i, $homeTeamId) == $shooter->getPassNr()) echo "selected ";                        
                        echo "value='".$shooter->getPassNr()."'>".$shooter->getName()."</option>";
                    }
                    echo "</select></td>";
                    echo "<td><input class='shooterResult' type='number' value='".$teamResult->getShooterResult($i, $homeTeamId)."'/></td>";
                    echo "</tr>";
                 }
                 echo "<tr>";
                 echo "<td></td>";
                 echo "<td>Gesamt:</td>";
                 echo "<td id='total'>".$teamResult->getTeamResult($homeTeamId)."</td>";
                 echo "</tr>";
            ?>
                </table>
            <?php
            if( $guaestTeamId){
                $shooters = Shooter::getAllByPassNr($guaestTeam->getCode());
                ?>
                <p class="text-center"><strong>Gäste: <?=$guaestTeam->getName()?></strong></p>
            <div class="form-group">
                <table class="table table-striped">
            <?php

                echo "<tr>";
                echo "<th>Schütze</th>";
                echo "<th>Name</th>";
                echo "<th>Ergebnis</th>";
                echo "</tr>";

                for($i=1; $i <= $discipline->getPsize(); $i++) {
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td><select>";
                    echo "<option value=''>Schütze auswählen</option>";
                    foreach ($shooters as $shooter)
                    {
                        echo "<option ";
                        if($teamResult->getShooterNr($i,  $guaestTeamId) == $shooter->getPassNr()) echo "selected ";                        
                        echo "value='".$shooter->getPassNr()."'>".$shooter->getName()."</option>";
                    }
                    echo "</select></td>";
                    echo "<td><input class='shooterResult' type='number' value='".$teamResult->getShooterResult($i,  $guaestTeamId)."'/></td>";
                    echo "</tr>";
                 }
                 echo "<tr>";
                 echo "<td></td>";
                 echo "<td>Gesamt:</td>";
                 echo "<td id='total'>".$teamResult->getTeamResult( $guaestTeamId)."</td>";
                 echo "</tr>";
                echo "</table>";
            }
            ?>
            
                <a class="btn btn-success" href="#"><i class="fa fa-x fa-plus"></i> Speichern</a>
                <?=backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId)?>
            </div>
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



