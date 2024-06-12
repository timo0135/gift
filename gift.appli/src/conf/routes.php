<?php
declare(strict_types=1);


use gift\appli\app\actions\GetPrestationsAction;

return function(\Slim\App $app): \Slim\App {

    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');

    return $app;

};
