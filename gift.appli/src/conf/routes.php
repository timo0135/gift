<?php
declare(strict_types=1);


use gift\appli\app\actions\GetBoxesPredefiniesAction;
use gift\appli\app\actions\GetBoxesPredefiniesDetailsAction;
use gift\appli\app\actions\GetCategoriesAction;
use gift\appli\app\actions\GetPrestationsAction;

use gift\appli\app\actions\GetOnePrestationAction;

use gift\appli\app\actions\GetPrestationsForOneCategorieAction;

use gift\appli\app\actions\GetCreateBoxAction;
use gift\appli\app\actions\PostCreateBoxAction;

return function(\Slim\App $app): \Slim\App {

    $app->get('/categories',GetCategoriesAction::class)->setName('categories');

    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');

    $app->get('/prestation',GetOnePrestationAction::class)->setName('prestation');

    $app->get('/categorie/{id}/prestations', GetPrestationsForOneCategorieAction::class)->setName('prestationsOneCategorie');

    $app->get('/box/predefinie', GetBoxesPredefiniesAction::class)->setName('GetBoxesPredefinies');

    $app->get('/box/predefinie/{id}', GetBoxesPredefiniesDetailsAction::class)->setName('GetBoxesPredefiniesDetails');

    $app->get('/box/create', GetCreateBoxAction::class)->setName('GetcreateBox');

    $app->post('/box/create', PostCreateBoxAction::class)->setName('PostcreateBox');


    return $app;

};
