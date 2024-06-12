<?php
declare(strict_types=1);


use gift\appli\app\actions\GetPrestationsAction;

use gift\appli\app\actions\GetOnePrestationAction;

return function(\Slim\App $app): \Slim\App {

    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');

    $app->get('/prestation',GetOnePrestationAction::class)->setName('prestation');

    return $app;

};
