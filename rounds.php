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

?>

<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-8 rounded border shadow p-3 mb-5 bg-white " id="col-Login" >
            <p class="text-center"><strong>Runden</strong></p>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="disciplines.php">Diszipline</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Runde</li>
                    <li class="breadcrumb-item">Gilde</li>
                    <li class="breadcrumb-item" aria-current="page">Team</li>
                    <li class="breadcrumb-item">Ergebniseingabe</li>
                </ol>
            </nav>


            <div class="form-group">
                <table class="table table-striped">
            <?php

            $rounds = Round::getAllByDistrictAndDiscipline($discrictId, $disciplineId);

            $discipline = Discipline::get($disciplineId);
            echo '<tr>';
            echo '<th>DISZIPLIN</th>';
            echo '<td>'.$discipline->getName().'</td>';
            echo '</tr>';

           
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Runde</th>";
                echo "<th>Start</th>";
                echo "<th>Ende</th>";
                echo "<th>Auswählen</th>";
                echo "</tr>";
                foreach ($rounds as $round)
                {
                echo "<tr>";
                echo "<td>" . $round->getId() ."</td>";
                echo "<td>Runde " . $round->getRound() ."</td>";                
                echo "<td>" . $round->getStart() ."</td>";
                echo "<td>" . $round->getStop() ."</td>";
                echo "<td>";
                echo '<a class="btn btn-success" href="teams.php?disciplineId='.$disciplineId.'&roundId=' . $round->getId() . '"><i class="fa fa-x fa-pencil"></i>Select</a>';
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

<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>
