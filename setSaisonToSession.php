<?php
   session_start();
   $_SESSION['saison'] = $_POST['value'];
   echo "success";
?>