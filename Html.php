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
    echo '<div class="container-fluid infoTable">';
}

function infoTableRow($th, $td){
    ?>

    <div class="row align-items-center">                    
        <label for="saisonSelect" class="col-sm-2 col-form-label"><strong><?=$th?></strong></label>
        <div class="col-sm-10">
            <?=$td?>
        </div>   
    </div>
    <?php
}

function getRoundRange($round, $user){
    $text = "Start: " . formatDateString($round->getStart()). " Ende: " . formatDateString($round->getStop());
    if($user->getRight()==0 && (strtotime($round->getStart()) > strtotime('now') || strtotime($round->getStop()) < strtotime('now'))){
        $text .= '<div class="alert alert-warning" role="alert"> Runde außerhalb des Eingabebereichs! Änderungen nur mit Sportleiter Berechtigung möglich!</div>';
    }
    return $text;
}

function infoTableEnd(){
    echo '</div>';
}

function headline($title){
    ?>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-11 col-sm-11 mr-auto p-6">
                <p class="text-center"><strong><?=$title?></strong></p>
            </div>
            <div class="p-1">
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

function crumbBar($index, $rights=false, $disciplineId=null, $roundId=null){
    ?>

    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php
                echo crumbBarItem("Disziplin", $index==1, "disciplines.php", $index>=1);
                echo crumbBarItem("Runde", $index==2, "rounds.php?disciplineId=".$disciplineId, $index>=2);
                echo crumbBarItem("Gilde", $index==3, "vereins.php?disciplineId=". $disciplineId. "&roundId=". $roundId, $index>=3 && $rights);
                echo crumbBarItem("Team", $index==4, "teams.php?disciplineId=". $disciplineId. "&roundId=". $roundId, $index>=4);
                echo crumbBarItem("Ergebniseingabe", $index==5);
            ?>
        </ol>
    </nav>
    <?php
}

function crumbBarItem($title, $active=false, $href=null, $rights=false){
    if($active){
        echo '<li class="breadcrumb-item active" aria-current="page">'.$title.'</li>';
    }
    else if($href && $rights){
        echo '<li class="breadcrumb-item"><a href="'.$href.'">'.$title.'</a></li>';
    }
    else{
        echo '<li class="breadcrumb-item">'.$title.'</li>';
    }

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
            <input type="submit"  name="" value="Abmelden" class="btn btn-dark">
            <input type="hidden" name="logoff" value="y">
        </form>
    </section>
    <?php
}
    
?>