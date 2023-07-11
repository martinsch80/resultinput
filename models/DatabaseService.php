<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-16
 * Time: 11:38
 */

require_once "models/Database.php";

interface DatabaseService
{
    public function update();
    public function create();
    public static function delete($id);
    public static function getAll();
    public static function get($id);
}