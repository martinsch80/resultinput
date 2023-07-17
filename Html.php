<?php
function utf8_convert($string){
    return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
}

function renderHeader($title){

    ?>

        <head>
            <title><?=$title?></title>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="css/indexStyle.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
            <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">


            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

        </head>

    <?php
}

function backButton($href){

    ?>
        <a class="btn btn-outline-secondary" href="<?=$href?>"><i class="fa fa-x fa-caret-left"></i> Zurück</a>
    <?php
}

function infoTableRow($th, $td){
    echo '<tr>';
    echo '<th>'. $th .'</th>';
    echo '<td>'. $td .'</td>';
    echo '</tr>';
}


function renderLogoutSection(){
    ?>
    <section class="col-12 text-center">
        <form action="login.php" type="GET">
            <input type="submit"  name="" value="Logout" class="btn btn-dark">
            <input type="hidden" name="logoff" value="y">
        </form>
    </section>
    <?php
}
    
?>