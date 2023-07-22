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
    die($teamResult);

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
            crumbBar(5, $user->getRight()>0, $disciplineId,$roundId);

            echo '<div class="form-group">';
            echo '<table class="table table-striped">';

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
            infoTableRow("Eingabe", getRoundRange($round, $user));
            infoTableRow("Gilde", Verein::get( $verein)->getName()); 
            
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

            infoTableEnd();           


           
            
            $disabled = $user->getRight() == 1 || strtotime($round->getStart()) < strtotime('now') && strtotime($round->getStop()) > strtotime('now')?"":"disabled";

            ?>
            <form action="#" method="POST">
                       
            <?php

                renderTeamTable($teamResult, $homeTeam, $discipline, $disabled, "Heim", "homeTeam");
                if( $guaestTeamId){
                    renderTeamTable($teamResult, $guaestTeam, $discipline, $disabled, "Gast", "guastTeam");
                }

                function renderTeamTable($teamResult, $team, $discipline, $disabled, $title, $prefix){
                    $shooters = Shooter::getAllByPassNr($team->getCode(), $discipline->getWeapon());
                    echo '<p class="text-center"><strong>'.$title.': '.utf8_convert($team->getName()).'</strong></p>';
                    echo '<div class="form-group">';
                    echo '<table class="table table-striped">';
                    echo "<tr>";
                    echo "<th class='colID'>Nr</th>";
                    echo "<th>Name</th>";
                    echo "<th class='colResult'>Ergebnis</th>";
                    echo "</tr>";

                    for($i=1; $i <= $discipline->getPsize(); $i++) {
                        echo "<tr>";
                        echo "<td>".$i."</td>";
                        echo "<td><select ". $disabled ." class='shooterSelect form-control' name='".$prefix."Shooter[]'>";
                        echo "<option value=''>Schütze auswählen</option>";
                        foreach ($shooters as $shooter)
                        {
                            echo "<option ";
                            if($teamResult->getShooterNr($i, $team->getId()) == $shooter->getPassNr()) echo "selected ";                        
                            echo "value='".$shooter->getPassNr()."'>".utf8_convert($shooter->getName())."</option>";
                        }
                        echo "</select></td>";
                        echo "<td>";
                        echo "<input name='".$prefix."Result[]' ". $disabled ." class='".$prefix."Result shooterResult form-control' type='number'";
                        echo " value='".$teamResult->getShooterResult($i, $team->getId())."' min='0' max='".$discipline->getResultRange()."'/></td>";
                        echo "</tr>"; 
                    }
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td><strong>Gesamt:</strong></td>";
                    echo "<td id='".$prefix."total' class='shooterResult total'>".$teamResult->getTeamResult($team->getId())."</td>";
                    echo "</tr>";
                    echo "</table>";
                    renderUpdateJS($prefix);
                    echo "</div>";
                }
                if(empty($disabled)){
                    echo '<input  type="submit" class="btn btn-success" href="#"/>';
                }

                echo '<input type="hidden" name="disciplineId" value="'.$disciplineId.'">';
                echo '<input type="hidden" name="roundId" value="'.$roundId.'">';
                echo '<input type="hidden" name="teamId" value="'.$teamId.'">';
                backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId);

                function renderUpdateJS($prefix){
                    ?>
                        <script type="text/javascript">
                            $(".<?=$prefix?>Result").change(function(){
                                var amount = 0;
                                $(".<?=$prefix?>Result").each( function() {
                                    var val = parseFloat($(this)[0].value)
                                    if(val) amount += val;
                                })
                                $("#<?=$prefix?>total").text(amount)
                            });
                        </script>
                    <?php
                };
                
            ?>
            </form>
        </div>
    </div>
</section>





<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>



