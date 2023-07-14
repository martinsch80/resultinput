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
        User::deleteCredentialsFromSession();
        exit();
    }
}
else
{
    header("Location: login.php");
    User::deleteCredentialsFromSession();
    exit();
}

echo '<html>';
renderHeader("Disziplinen");
echo '<body>';
?>

<style>
    .tab{
        display: none;
    }
</style>

<script>
    function display(name) {
        document.getElementById('winter').style.display = 'none';
        document.getElementById('summer').style.display = 'none';
        document.getElementById(name).style.display = 'block';
    }
</script>

<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-3 mb-5 bg-white " id="col-content" >
            <p class="text-center"><strong>Disziplinen</strong></p>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Diszipline</li>
                    <li class="breadcrumb-item">Runde</li>
                    <li class="breadcrumb-item">Gilde</li>
                    <li class="breadcrumb-item">Team</li>
                    <li class="breadcrumb-item">Ergebniseingabe</li>
                </ol>
            </nav>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#WinterBewerbe" data-toggle="tab" onclick="javascript:display('winter');">Winterbewerbe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#SomerBewerbe" data-toggle="tab" onclick="javascript:display('summer');">Somerbewerbe</a>
                </li>
            </ul>

            <div id="winter">
                <table class="table table-striped">
                    <?php
                        $disziplines = Discipline::getBySeason("W");

                        renderDisciplineTable($disziplines);
                    ?>
                </table>
            </div>
            <div id="summer" class="tab">
                <table class="table table-striped">
                    <?php
                        $disziplines = Discipline::getBySeason("S");

                        renderDisciplineTable($disziplines);
                    ?>
                </table>
            </div>
            <?php

            

            function renderDisciplineTable($disziplines){
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Name</th>";
                echo "<th>Auswählen</th>";
                echo "</tr>";
                foreach ($disziplines as $diszipline)
                {
                    echo "<tr>";
                    echo '<a alt="Select" href="rounds.php?disciplineId=' . $diszipline->getId() . '">';
                    echo "<td>" . $diszipline->getId() ."</td>";
                    echo "<td>" . $diszipline->getName() ."</td>";     
                    echo "<td>";
                    echo '<a alt="Select" class="btn btn-success btn-sm" href="rounds.php?disciplineId=' . $diszipline->getId() . '"><i class="fa fa-x fa-pincele"></i> SELECT</a>'; 
                    echo "</td>";
                    echo "</a>";
                    echo "</tr>";

                }
            }
                

            ?>
        </div>
    </div>
</section>

<?php

    renderLogoutSection();
    echo '</body>';
    echo '</html>';
?>
