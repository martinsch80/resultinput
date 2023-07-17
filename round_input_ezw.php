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
require "models/SingleResult.php";
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

$discrictId = substr($user->getUsrCode(), 0, 3);

$singleResults = SingelResult::getBySeasonAndRoundIdAndUserCode("2022 / 2023", $roundId, $user->getUsrCode());

if(!empty($_POST))
{
    
   //$teamResult->setData($homeTeamShooters, $homeTeamResults,  $guastTeamShooters, $guastTeamResults);

    if($singleResults->validate())
    {
        $singleResults->update();
    }
}


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
                    <p class="text-center"><strong>Ergebnisseingabe Einzelwertung</strong></p>
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

            infoTableRow("Gilde", $user->getUsrCode());

            $shooters = Shooter::getAllByPassNr($user->getUsrCode());

            ?>

            </div>
            </table>
            <form action="#" method="POST">
           
            <p class="text-center"><strong>Erfasste Ergebnisse</strong></p>

            <div class="container-fluid">
                <div class="row"> 
                    <div class="col-12 col-md-8">
                    Name
                    </div>
                    <div class="col-12 col-md-4">
                    Ergebnis
                    </div>
                </div>
            </div>
            <?php

                foreach ($singleResults as $singleResult) {
                    $shooter = Shooter::getAllByPassNr($singleResult->getNumber());
                    ?>
                    <div class="row"> 
                        <div class="col-12 col-md-8">                        
                        <?= utf8_convert($shooter[0]->getName())?>
                        </div>
                        <div class="col-12 col-md-4">
                        <?=$singleResult->getResult()?>
                        </div>
                    </div>
                    <?php
                }

                echo '<p class="text-center"><strong>Einzelwertungsergebnis hinzufügen</strong></p>';

                echo '<div class="row">'; 
                echo "<div class='col-12 col-md-8'><select name='shooter'>";
                echo "<option value=''>Schütze auswählen</option>";
                foreach ($shooters as $shooter)
                {
                    echo "<option ";
                    echo "value='".$shooter->getPassNr()."'>".utf8_convert($shooter->getName())."</option>";
                }
                echo "</select></div>";
                echo "<div class='col-12 col-md-4'><input name='result' class='shooterResult' type='number' value=''/></div>";
                echo '</div>';
                echo '<input  type="submit" class="btn btn-success" href="#"/>';

                echo '<input type="hidden" name="disciplineId" value="'.$disciplineId.'">';
                echo '<input type="hidden" name="roundId" value="'.$roundId.'">';
                backButton("teams.php?disciplineId=".$disciplineId."&roundId=".$roundId)?>
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



