<?php
session_start();
spl_autoload_register(function ($class){
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

error_reporting(E_ALL);
$error_log_path = __DIR__.'/error_log.txt';
ini_set('error_log', $error_log_path);

header("Content-type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "forum");

function convertString($input) {
    $normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
    $noSpaces = str_replace(' ', '', $normalized);
    $lowercased = strtolower($noSpaces);

    return $lowercased;
}