<?php
function utf8_convert($string){
    //return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
    return $string;
}

function renderHeader($title){

    ?>

        <head>
            <title><?=$title?></title>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="css/indexStyle.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.7.0.min.js" ></script>
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

function infoTableStart(){
    echo '<div class="infoTable">';
}

function infoTableRow($th, $td){
    ?>

    <div class="form-group row align-items-center">                    
        <label for="saisonSelect" class="col-sm-2 col-form-label"><strong><?=$th?></strong></label>
        <div class="col-sm-10">
            <?=$td?>
        </div>   
    </div>
    <?php
}

function infoTableEnd(){
    echo '</div>';
}

function headline($title){
    ?>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-11">
                <p class="text-center"><strong><?=$title?></strong></p>
            </div>
            <div class="col-1">
                <p class="text-center"><i class="fa fa-x fa-info"></i></p>
            </div>
        </div>                    
    </div>
    <?php
}

function userLine($user){
    ?>
        <div class="userInfoLine">Willkommen, Sie sind angemeldet als <strong><?=$user->getName()?></strong> mit der Berechtigung <strong><?=$user->getRightAsString()?></strong> </div>
    <?php
}

function seasonSelector($discipline){

    $options = "";
    $saison = "";
    if(isset($_SESSION['saison']))
    {
        $saison = $_SESSION['saison'];
    }

    $year = strtolower($discipline->getSeason()) == "w" && date("m")<=8 ? date("Y"):date("Y")+1;

    for ($i= $year; $i > 2016; $i--) { 
        if(strtolower($discipline->getSeason()) == "w") $value = $i-1 . " / " . $i;
        if(strtolower($discipline->getSeason()) == "s") $value = $i-1;
        $selected = $saison==$value ? "selected" : "";
        $options = $options . "<option ".$selected.">". $value ."</option>";
    }
 
    ?>
            <div class="form-group row">                    
                <label for="saisonSelect" class="col-sm-2 col-form-label"><strong>Saisonsauswahl</strong></label>
                <div class="col-sm-10">
                    <select id="saisonSelect" class="form-control">
                        <?=$options?>                        
                    </select>
                </div>   
            </div>
            <script type="text/javascript">
                $( document ).ready(function() {
                    $('#saisonSelect').on("change",function(){
                        var thevalue = $(this).val();

                        $.ajax({
                            url:'setSaisonToSession.php',
                            type: "post",
                            data:{"value":thevalue},
                            dataType:"html",
                            success:function(data){
                                location.reload();
                            } 
                        });
                    });
                });

            </script>
    <?php
}

function formatDateString($dateString){
    return date_format(date_create($dateString), 'd.m.Y');
}


function renderLogoutSection(){
    ?>
    <section class="col-12 text-center logoffSection">
        <form action="login.php" type="GET">
            <input type="submit"  name="" value="Logout" class="btn btn-dark">
            <input type="hidden" name="logoff" value="y">
        </form>
    </section>
    <?php
}
    
?>