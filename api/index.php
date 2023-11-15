<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "src/$class.php";
});

header("Content-type: application/json; charset=UTF-8");

phpinfo();

?>