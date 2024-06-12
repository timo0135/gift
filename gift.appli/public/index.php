<?php
declare(strict_types=1);


use gift\appli\infrastructure\Eloquent;

require_once __DIR__ . '/../src/vendor/autoload.php';

/* application boostrap */
$app = require_once __DIR__ . '/../src/conf/bootstrap.php';
Eloquent::init(__DIR__ . '/../src/conf/gift.db.conf.ini');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define("createBox", 10);
define("updateBox", 20);
define("updateCatalogue", 100);
$app->run();

