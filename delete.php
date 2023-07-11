<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-16
 * Time: 13:31
 */

require "models/Worker.php";
require "models/Project.php";
require "models/Activity.php";

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

//AUTH done


$id = 0;

if(!empty($_GET['id']))
{
    $id = $_GET['id'];
}

if(!empty($_POST))
{
    Project::delete($_POST['id']);
    header("Location: projects.php");
    exit();
}
else if($id != null)
{
    $project = Project::get($id);
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
<section class="container-fluid">
    <div class="row justify-content-center text-center">

        <div class="col-7 rounded border shadow p-3 mb-5 bg-white " id="col-Projects" >
        <div class="form-group">


            <table class="table table-striped table-bordered detail-view">
                <tbody>

                <?php

                echo '<tr>';
                echo '<th>ID</th>';
                echo '<td>'.$project->getId().'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<th>Titel</th>';
                echo '<td>'.$project->getTitle().'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<th>Kick-Off Datum</th>';
                echo '<td>'.$project->getKickoff().'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<th>Gesamtdauer</th>';
                echo '<td>'.$project->getDurati().'</td>';
                echo '</tr>';

                ?>


                </tbody>
            </table>
        </div>
            <?= "Wollen Sie dieses Projekt wirklick loeschen?";?>
</div>
</div>
</section>
<section class="col-12 text-center">
    <form action="delete.php?id=<?=$project->getId()?>" method="post">

        <button type="submit" class="btn btn-dark">Löschen</button>
        <input type="hidden" name="id" value="<?=$id?>"/>
        <a class="btn btn-dark" href="projects.php">Zurück</a>

    </form>

</section>
</body>
</html>



