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
    if ($user->isLoggedIn())
    {
        //
    }
    else
    {
        header("Location: login.php");
        exit();
    }
}
else
{
    header("Location: login.php");
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

if($discipline->isSummerState()){
    $teamResults = TeamResults::getSummerBySeasonAndTeamIdAndRoundId($saison,  $roundId, $teamId);
}
else{
    $teamResults = TeamResults::getBySeasonAndTeamIdAndRoundId($saison,  $roundId, $teamId);
}

if(count($teamResults)>0){
    $teamResult = $teamResults[0];
}

$discrictId = substr($user->getUsrCode(), 0, 3);

if(!empty($_POST))
{
    $homeTeamShooters = $_POST['homeTeamShooter'];
    $homeTeamResults = $_POST['homeTeamResult'];
    
    $guastTeamShooters = [];
    if(!empty($_POST['guastTeamShooter'])) $guastTeamShooters = $_POST['guastTeamShooter'];
    $guastTeamResults = [];
    if(!empty($_POST['guastTeamResult'])) $guastTeamResults = $_POST['guastTeamResult'];
        
    $teamResult->setUserId($user->getId());
    $teamResult->setData($homeTeamShooters, $homeTeamResults,  $guastTeamShooters, $guastTeamResults);
    $teamResult->setDiscipline($discipline);

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

            // Eingabe nur erlaubt, wenn User Recht hat und Zeitraum passt
            $disabled = $user->getRight() == 1 || strtotime($round->getStart()) < strtotime('now') && strtotime($round->getStop() . " 23:59:59") > strtotime('now') ? "" : "disabled";

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
                        $step = $discipline->getZiroOne()? 0.1: 1;
                        echo " value='".$teamResult->getShooterResult($i, $team->getId())."' step='".$step."'/></td>";
                        echo "</tr>"; 
                    }
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td><strong>Gesamt:</strong></td>";
                    echo "<td id='".$prefix."total' class='shooterResult total'>".$teamResult->getTeamResult($team->getId())."</td>";
                    echo "</tr>";
                    echo "</table>";
                    // HIER: $disabled mitgeben, damit JS weiß, ob es überhaupt eingreifen darf
                    renderUpdateJS($prefix, $disabled);
                    echo "</div>";
                }

                if(empty($disabled)){
                    echo '<input  type="submit" class="btn btn-success" href="#"/>';
                }

                echo '<input type="hidden" name="disciplineId" value="'.$disciplineId.'">';
                echo '<input type="hidden" name="roundId" value="'.$roundId.'">';
                echo '<input type="hidden" name="teamId" value="'.$teamId.'">';
                backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId);

                function renderUpdateJS($prefix, $disabled){
                    ?>
                        <script type="text/javascript">
                            (function($){
                                // Summe für dieses Team neu berechnen
                                function update<?= $prefix ?>Total() {
                                    var amount = 0;
                                    $(".<?= $prefix ?>Result").each(function() {
                                        var val = parseFloat($(this).val());
                                        if (!isNaN(val)) {
                                            amount += val;
                                        }
                                    });
                                    $("#<?= $prefix ?>total").text(parseFloat(amount.toFixed(1)));
                                }

                                <?php if (empty($disabled)) : ?>
                                // Ergebnisfeld nur aktiv, wenn ein Schütze gewählt ist
                                function update<?= $prefix ?>Row(selectEl) {
                                    var $row   = $(selectEl).closest("tr");
                                    var $input = $row.find("input.<?= $prefix ?>Result");

                                    if ($(selectEl).val()) {
                                        // Schütze gewählt → Eingabe erlauben
                                        $input.prop("disabled", false);
                                    } else {
                                        // Kein Schütze → Wert löschen und sperren
                                        $input.val("");
                                        $input.prop("disabled", true);
                                    }
                                }

                                // Initial alle Zeilen korrekt setzen
                                $("select[name='<?= $prefix ?>Shooter[]']").each(function(){
                                    update<?= $prefix ?>Row(this);
                                });

                                // Bei Änderung des Schützen die Zeile aktualisieren
                                $("select[name='<?= $prefix ?>Shooter[]']").on("change", function(){
                                    update<?= $prefix ?>Row(this);
                                    update<?= $prefix ?>Total();
                                });
                                <?php endif; ?>

                                // Summe aktualisieren, wenn ein Ergebnis geändert wird
                                $(".<?= $prefix ?>Result").on("change keyup", function(){
                                    update<?= $prefix ?>Total();
                                });

                                // Initiale Summe setzen
                                update<?= $prefix ?>Total();

                            })(jQuery);
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
