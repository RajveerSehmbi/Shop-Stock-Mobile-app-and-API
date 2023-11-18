<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__."/$class.php";
});

header("Content-type: application/json; charset=UTF-8");

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] === "inventory") {

    $id = $parts[2] ?? NULL; 

    $database = new Database(getenv('POSTGRES_HOST'), getenv('POSTGRES_DATABASE'), getenv('POSTGRES_USER'), getenv('POSTGRES_PASSWORD'));
    $gateway = new InventoryGateway($database);
    $controller = new InventoryController($gateway);

    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
}
?>