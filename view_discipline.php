<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-17
 * Time: 20:06
 */

require "models/User.php";
require "models/Discipline.php";

if(!(User::isLoggedIn()))
{
   header("Location: login.php");
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
    header("Location: disciplines.php");
    exit();
}
else
{
    $discipline = Discipline::get($id);
}

?>

<html>
<head>
    <title>Disziplin</title>
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
        <div class="col-7 rounded border shadow p-3 mb-5 bg-white " id="col-Projects" >
            <p class="text-center"><strong>Disziplin</strong></p>

            <div class="form-group">

                <table class="table table-striped table-bordered detail-view">
                    <tbody>

                    <?php

                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<td>'.$discipline->getId().'</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<th>Name</th>';
                    echo '<td>'.$discipline->getName().'</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<th>Saison</th>';
                    echo '<td>'.$discipline->getSeason().'</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<th>Art</th>';
                    echo '<td>'.$discipline->getWeapon().'</td>';
                    echo '</tr>';

                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
 

<section class="col-12 text-center">
    <form action="login.php" type="GET">

        <input type="submit"  name="" value="Logout" class="btn btn-dark">
        <input type="hidden" name="logoff" value="y">
        <a class="btn btn-dark" href="disciplines.php">Zurück</a>

    </form>

</section>

</body>
</html>
