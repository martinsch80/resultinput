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
require "models/Shooter.php";

//User::deleteCredentialsFromSession();
//print_r($_SESSION['user']);
//echo Worker::getCredentialsFromSession()->getEmail();
//print_r(Project::getDuration());


if(isset($_SESSION['user'])) {
    $user = User::getCredentialsFromSession();
    
    if ($user->isLoggedIn())
    {

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


echo '<html>';
renderHeader("Schützen");
echo '<body>';

?>

<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-11 rounded border shadow p-3 mb-5 bg-white " id="col-Login" >
            <p class="text-center"><strong>Schützen</strong></p>

            <div class="form-group">
                <table class="table table-striped">
            <?php

            $shooters = Shooter::getAllByPassNr($user->getUsrCode());

           
                echo "<tr>";
                echo "<th>PassNr</th>";
                echo "<th>Name</th>";
                echo "<th>Auswählen</th>";
                echo "<th></th>";
                echo "</tr>";
                foreach ($shooters as $shooter)
                {
                echo "<tr>";
                echo "<td>" . $shooter->getPassNr() ."</td>";
                echo "<td>" . $shooter->getName() ."</td>";         
                echo "<td>";
                echo '<a class="btn btn-info" href="view_shooter.php?id=' . $shooter->getId() . '"><i class="fa fa-x fa-eye"></i></a>';
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
