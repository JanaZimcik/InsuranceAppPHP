<?php
session_start();
mb_internal_encoding("UTF-8");

function autoloadFunction(string $class): void
{
    if (preg_match('/Controller$/', $class))
        require ("controllers/" . $class . ".php");
    else
        require("models/" . $class . ".php");
}

spl_autoload_register("autoloadFunction");

Db::getConnection("127.0.0.1", "root", "", "insurance_app");

$router = new RouterController();
$router->process(array($_SERVER["REQUEST_URI"]));
$router->listView();