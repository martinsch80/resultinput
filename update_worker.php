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


if(!empty($_POST))
{
    $worker->setName(isset($_POST['name']) ? $_POST['name'] :"");
    $worker->setEmail(isset($_POST['email']) ? $_POST['email'] :"");
    if(!(empty($_POST['password'])))
    {
        $worker->setPassword($_POST['password']);
    }

    if($worker->validateNameEmailOptionalPassword())
    {
        $worker->save();
        header("Location: projects.php");
        exit();
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


<section class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-7 rounded border shadow p-3 mb-5 bg-white" id="col-update-Worker">
            <p class="text-center"><strong>Mitarbeiter ändern</strong></p>
            <form class="form-horizontal" action="update_worker.php?id=<?= $worker->getId() ?>" method="post">


                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="control-label">Name</label>
                        <input type="text" class=form-control name="name"
                               value="<?=$worker->getName()?>">
                        <?php if(isset($worker->getErrors()['name'])) echo "<div class='error'>" . $worker->getErrors()['name'] . "</div>"?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="control-label">Email</label>
                        <input type="text" class=form-control name="email"
                               value="<?= $worker->getEmail() ?>">
                        <?php if(isset($worker->getErrors()['email'])) echo "<div class='error'>" . $worker->getErrors()['email'] . "</div>"?>

                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="control-label">Passwort</label>
                        <input type="text" class=form-control name="password"
                               value="">
                        <?php if(isset($worker->getErrors()['password'])) echo "<div class='error'>" . $worker->getErrors()['password'] . "</div>"?>

                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="control-label">ID</label>
                        <input class="form-control" type="text" name="id"
                               value="<?= $worker->getId() ?>" disabled>

                    </div>
                </div>


                <br>
                <button class="btn btn-dark justify-content-center" type="submit" name="submit">Ändern</button>
            </form>
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
