<?php
declare(strict_types=1);


use gift\appli\app\actions\GetPrestationsAction;

use gift\appli\app\actions\GetOnePrestationAction;

use gift\appli\app\actions\GetPrestationsForOneCategorieAction;

return function(\Slim\App $app): \Slim\App {

    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');

    $app->get('/prestation',GetOnePrestationAction::class)->setName('prestation');

    $app->get('/categorie/{id}/prestations', GetPrestationsForOneCategorieAction::class)->setName('prestationsOneCategorie');


    return $app;

};
