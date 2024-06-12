<?php
declare(strict_types=1);


use gift\appli\app\actions\GetBoxesPredefiniesAction;
use gift\appli\app\actions\GetBoxesPredefiniesDetailsAction;
use gift\appli\app\actions\GetCategoriesAction;
use gift\appli\app\actions\GetPrestationsAction;

use gift\appli\app\actions\GetOnePrestationAction;

use gift\appli\app\actions\GetPrestationsForOneCategorieAction;

return function(\Slim\App $app): \Slim\App {

    $app->get('/categories',GetCategoriesAction::class)->setName('categories');

    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');

    $app->get('/prestation',GetOnePrestationAction::class)->setName('prestation');

    $app->get('/categorie/{id}/prestations', GetPrestationsForOneCategorieAction::class)->setName('prestationsOneCategorie');

    $app->get('/box/predefinie', GetBoxesPredefiniesAction::class)->setName('GetBoxesPredefinies');

    $app->get('/box/predefinie/{id}', GetBoxesPredefiniesDetailsAction::class)->setName('GetBoxesPredefiniesDetails');

    return $app;

};
