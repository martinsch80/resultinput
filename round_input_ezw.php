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
require "models/SingleResult.php";
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

if(isset($_SESSION['saison']))
{
    $saison = $_SESSION['saison'];
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

$singleResults = SingelResult::getBySeasonAndRoundIdAndUserCode($saison, $roundId, $verein);

if(!empty($_POST))
{
 
    $shooterNr = $_POST['shooter'];
    $result = $_POST['result'];
    var_dump($shooterNr);
    var_dump($result);
    die("TODO Create Single Result");

    $singleResult =  new SingelResult(
        null,
        $roundId,
        0,
        $shooterNr,
        $result,
        0,
        $saison,
        $obj[self::COLUMN_CHANGEDATE],
        $user->getId(),
        $obj[self::COLUMN_SEASIONSTATE],
        $disciplineId
    );
   //$teamResult->setData($homeTeamShooters, $homeTeamResults,  $guastTeamShooters, $guastTeamResults);

    if($singleResult->validate())
    {
        $singleResult->save();
    }
}


echo '<html>';
renderHeader("Ergebnisseingabe");
echo '<body>';

$discipline = Discipline::get($disciplineId); 

?>
<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-11 mb-11 bg-white " id="col-Login" >
            
            <?php
                headLine("Ergebnisseingabe Einzelwertung", $discipline, $saison);
                userLine($user);
                crumbBar(5, $user->getRight()>0, $disciplineId,$roundId);
            seasonSelector($discipline);
            infoTableStart();
            $round = Round::get($roundId);   
            infoTableRow("Saison", $saison);  
            infoTableRow("RUNDE", $round->getRound());
            infoTableRow("Eingabe", getRoundRange($round, $user));
                       
            infoTableRow("DISZIPLIN", $discipline->getName());

            infoTableRow("Gilde", Verein::get( $verein)->getName());  
            infoTableEnd();

            $shooters = Shooter::getAllByPassNr( $verein);

            $disabled = $user->getRight() == 1 || strtotime($round->getStart()) < strtotime('now') && strtotime($round->getStop()) > strtotime('now')?"":"disabled";

            ?>

            <form action="#" method="POST">
           
            <p class="text-center"><strong>Erfasste Ergebnisse</strong></p>

            <div class="container-fluid infoTable">
                <div class="row"> 
                    <div class="col-8 col-md-8">
                    <strong>Name</strong>
                    </div>
                    <div class="col-4 col-md-4">
                    <strong>Ergebnis</strong>
                    </div>
                </div>
            
            <?php

                foreach ($singleResults as $singleResult) {
                    $shooter = Shooter::getAllByPassNr($singleResult->getNumber());
                    ?>
                    <div class="row"> 
                        <div class="col-8 col-md-8">                        
                        <?= utf8_convert($shooter[0]->getName())?>
                        </div>
                        <div class="col-4 col-md-4">
                            <?=$singleResult->getResult()?>
                            <div class="modal fade" id="staticBackdrop<?=$singleResult->getId()?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Ergebnis löschen</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                     Wollen Sie das Ergebnis von <br/><strong>"<?= utf8_convert($shooter[0]->getName())?> (<?=$singleResult->getResult()?>) "</strong><br/>wirklich löschen?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                                        <button type="button" class="btn btn-danger">Löschen</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                          <?php  if(empty($disabled)){?>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#staticBackdrop<?=$singleResult->getId()?>">
                                <i class="fa fa-x fa-trash"></i>
                            </button>
                           <?php } ?> 
                        </div>

                    </div>
                    <?php
                }

                echo "</div>";

                if(empty($disabled)){
                    echo '<p class="text-center wordWrap"><strong>Einzelwertungsergebnis hinzufügen</strong></p>';
                    echo '<div class="row">'; 
                    echo "<div class='col-8 col-md-8'><select class='shooterSelect form-control' name='shooter'>";
                    echo "<option value=''>Schütze auswählen</option>";
                    foreach ($shooters as $shooter)
                    {
                        echo "<option ";
                        echo "value='".$shooter->getPassNr()."'>".utf8_convert($shooter->getName())."</option>";
                    }
                    echo "</select></div>";
                    echo "<div class='col-4 col-md-4'><input name='result' class='shooterResult form-control' type='number' min='0' max='".$discipline->getResultRange()."' value=''/></div>";
                    echo '</div><br/>';
                    echo '<input  type="submit" class="btn btn-success" href="#" value="Hinzufügen"/>';

                    echo '<input type="hidden" name="disciplineId" value="'.$disciplineId.'">';
                    echo '<input type="hidden" name="roundId" value="'.$roundId.'">';
                }
                backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId)
                ?>
                </div>
            </form>
        </div>
    </div>
</section>

<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>



