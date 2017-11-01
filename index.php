<?php
session_start();

function __autoload($name) {
    require_once(str_replace('\\', '/', $name).'.class.php');
}

$ass = new \ass\Sess;
$ass->setRoute($_SERVER['REQUEST_URI']);
$ass->setMethod($_SERVER['REQUEST_METHOD']);
$ass->setPrefix('');
require_once('assets/path/route.php');
$ass->run();