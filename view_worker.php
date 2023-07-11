<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

require "models/Worker.php";
require "models/Project.php";

if(!(Worker::isLoggedIn()))
{
    header("Location: projects.php");
    //Worker::deleteCredentialsFromSession();
    exit();
}

//AUTH done


$id = "";

if(!empty($_GET['id']))
{
    $id = $_GET['id'];
}
if($id == null)
{
    header("Location: projects.php");
    exit();
}
else
{
    $worker = Worker::get($id);
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
    <div class="row justify-content-center  ">

        <div class="col-7 rounded border shadow p-3 mb-5 bg-white" id="col-Workers" >
            <p class="text-center"><strong>Mitarbeiter</strong></p>

            <div class="form-group">


                <table class="table table-striped">
                    <tbody>
                        <?php

                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<td>'.$worker->getId().'</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<th>Name</th>';
                        echo '<td>'.$worker->getName().'</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<th>Email</th>';
                        echo '<td>'.$worker->getEmail().'</td>';
                        echo '</tr>';

                        ?>
                        </tbody>
                </table>
            </div>
        </div>
</section>
<section class="col-12 text-center">
    <form action="login.php" type="GET">

        <input type="submit"  name="" value="Logout" class="btn btn-dark">
        <input type="hidden" name="logoff" value="y">
        <a class="btn btn-dark" href="projects.php">Zurück</a>

    </form>

</section>

</body>
</html>
