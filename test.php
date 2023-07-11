<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-16
 * Time: 16:34
 */

require_once "models/Worker.php";


$worker = new Worker(200, 'OktayEKC','okiiiiii@gmail.com','hallo123',2);
//$worker->create();
$worker = Worker::get(8);

$worker->setProjectId(4);
$worker->update();

?>