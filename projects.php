<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

require "models/Worker.php";
require "models/Project.php";
require "models/Activity.php";

//Worker::deleteCredentialsFromSession();
print_r($_SESSION['user']);

//echo Worker::getCredentialsFromSession()->getEmail();
//print_r(Project::getDuration());


if(isset($_SESSION['user'])) {
    $worker = Worker::getCredentialsFromSession();
    //die($worker->getEmail());
    if ($worker->checkCredentials())
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

if(isset($_POST['submit']))
{
    if(!empty($_POST['workerId']))
    {
        Worker::updateProject($_POST['workerId'], $_POST['project']);
    }
    else
    {
        Activity::updateProject($_POST['activityId'], $_POST['project']);
        //Worker::updateProject($_POST['worker'], $_POST['project']);
        //Worker::updateProject($_POST['worker'],$_POST['project']);
    }

}

?>

<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/indexStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body>





<style>
    .row{margin-top:10%}
</style>
<section class="container-fluid">
    <div class="row justify-content-center  ">
        <div class="col-8 rounded border shadow p-3 mb-5 bg-white " id="col-Login" >
            <p class="text-center"><strong>Projekte</strong></p>

            <div class="form-group">
                <table class="table table-striped">
            <?php

            $projects = Project::getAll();

            foreach ($projects as $project)
            {
                echo "<tr>";
                echo "<th>Identifikationsnummer</th>";
                echo "<th>Titel</th>";
                echo "<th>Kick-Off (Start)</th>";
                echo "<th>Dauer</th>";
                echo "<th>Ansehen / Editieren / Löschen</th>";
                echo "<th></th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>" . $project->getId() ."</td>";
                echo "<td>" . $project->getTitle() ."</td>";
                echo "<td>" . $project->getKickOff() ."</td>";
                echo "<td>" . $project->getDurati() ."</td>";
                echo "<td>";
                echo '<a class="btn btn-info" href="view.php?id=' . $project->getId() . '"><i class="fa fa-x fa-eye"></i></a>';
                echo "&nbsp";
                echo '<a class="btn btn-primary" href="update.php?id=' . $project->getId() . '"><i class="fa fa-x fa-pencil"></i></a>';
                echo "&nbsp";
                echo '<a class="btn btn-danger" href="delete.php?id=' . $project->getId() . '"><i class="fa fa-x fa-times"></i></a>';
                echo "&nbsp";
                echo "</td>";
                echo "</tr>";

            }

            ?>

                <tr>
                <td colspan="7">
                    <a class="btn btn-success float-left" href="create.php"><i class="fa fa-x fa-plus"></i></a>
                </td>
                </tr>


            </div>
            </table>
        </div>
    </div>
</section>



<section class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-10 rounded border shadow p-3 mb-5 bg-white " id="col-FreeAcitivity" >
            <p class="text-center"><strong>Freie Tätigkeiten</strong></p>


            <form action="projects.php" method="POST">
             <div class="form-group">
                <table class="table table-striped">
                    <?php

                    $activitys = Activity::getAllWithoutProject();
                    $workers = Worker::getAllWithoutProject();
                    $projects = Project::getAll();

                    foreach ($activitys as $activity)
                    {
                        echo '<form action="projects.php" method="post">';
                        echo '<input type="hidden" name="activityId" value='.$activity->getId().'>';
                        echo "<tr>";
                        echo "<th>ID</th>";
                        echo "<th>Beschreibung</th>";
                        echo "<th>Dauer</th>";
                        echo "<th>Datum</th>";
                        echo "<th>Zuteilen</th>";
                        echo "<th>Zugehöriges Projekt</th>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $activity->getId() ."</td>";
                        echo "<td>" . $activity->getDescription() ."</td>";
                        echo "<td>" . $activity->getDuration() ."</td>";
                        echo "<td>" . $activity->getDate() ."</td>";
                        echo "<td>";

                        echo "<select name='project'>";

                        foreach($projects as $project)
                        {
                            echo '<option value='.$project->getId().'>'.$project->getTitle().'</option>';

                        }
                        echo "</select>";

                        echo "&nbsp";
                        echo '<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-x fa-check"></i></button>';
                        echo "</td>";
                        echo "<td>Keines</td>";
                        echo "</tr>";

                        echo "</form>";

                    }
                    ?>
                </table>
            </div>
        </div>
        </div>
    </details>
</section>

<section class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-8 rounded border shadow p-3 mb-5 bg-white " id="col-FreeWorkers" >
            <p class="text-center"><strong>Freie Mitarbeiter</strong></p>


            <form action="projects.php" method="POST">
                <div class="form-group">
                    <table class="table table-striped">
                        <?php

                        $workers = Worker::getAllWithoutProject();
                        $projects = Project::getAll();

                        foreach ($workers as $worker)
                        {
                            echo '<form action="projects.php" method="post">';
                            echo '<input type="hidden" name="workerId" value='.$worker->getId().'>';
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Email</th>";
                            echo "<th>Zuteilen</th>";
                            echo "<th>Zugehöriges Projekt</th>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td>" . $worker->getId() ."</td>";
                            echo "<td>" . $worker->getName() ."</td>";
                            echo "<td>" . $worker->getEmail() ."</td>";
                            echo "<td>";

                            echo "<select name='project'>";

                            foreach($projects as $project)
                            {
                                echo '<option value='.$project->getId().'>'.$project->getTitle().'</option>';

                            }
                            echo "</select>";
                            echo "&nbsp";
                            echo '<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-x fa-check"></i></button>';
                            echo "</td>";
                            echo "<td>Keines</td>";
                            echo "</tr>";

                            echo "</form>";

                        }
                        ?>
                    </table>
                </div>
        </div>
    </div>
    </details>
</section>

<section class="col-12 text-center">
    <form action="login.php" type="GET">

        <input type="submit"  name="" value="Logout" class="btn btn-dark">
        <input type="hidden" name="logoff" value="y">

    </form>

</section>

</body>
</html>
