<?php
declare(strict_types=1);

use gift\appli\app\actions\GetBoxDeliveredAction;
use gift\appli\app\actions\GetBoxesAction;
use gift\appli\app\actions\GetBoxesPredefiniesAction;
use gift\appli\app\actions\GetBoxesPredefiniesDetailsAction;
use gift\appli\app\actions\GetCategoriesAction;
use gift\appli\app\actions\GetConnectionAction;
use gift\appli\app\actions\GetCreateBoxAction;
use gift\appli\app\actions\GetCreateCategorieAction;
use gift\appli\app\actions\GetDeconnexionAction;
use gift\appli\app\actions\GetDeliveryAction;
use gift\appli\app\actions\GetHomeAction;
use gift\appli\app\actions\GetInscriptionAction;
use gift\appli\app\actions\GetOneCategorieAction;
use gift\appli\app\actions\GetOnePrestationAction;
use gift\appli\app\actions\GetPaiementAction;
use gift\appli\app\actions\GetPrestationsByOneBoxAction;
use gift\appli\app\actions\GetPrestationsAction;
use gift\appli\app\actions\GetPrestationsForOneCategorieAction;
use gift\appli\app\actions\PostAddPrestionToBoxAction;
use gift\appli\app\actions\PostBoxDeliveredAction;
use gift\appli\app\actions\PostConnectionAction;
use gift\appli\app\actions\PostCreateBoxAction;
use gift\appli\app\actions\PostCreateCategorieAction;
use gift\appli\app\actions\PostDefineCurrentBoxAction;
use gift\appli\app\actions\PostDelPrestionToBoxAction;
use gift\appli\app\actions\PostInscriptionAction;
use gift\appli\app\actions\PostPaiementAction;
use gift\appli\app\actions\PostUpdatePrestationAction;
use gift\appli\app\actions\PostValidateBoxAction;

return function( \Slim\App $app): \Slim\App {

    $app->get('/categories',GetCategoriesAction::class)->setName('categories');

    $app->get('/categorie/{id}', GetOneCategorieAction::class)->setName('categorie');

    $app->get('/prestation',GetOnePrestationAction::class)->setName('prestation');

    $app->get('/box/create', GetCreateBoxAction::class)->setName('GetcreateBox');

    $app->get('/boxes', GetBoxesAction::class)->setName('GetBoxes');

    $app->post('/box/create', PostCreateBoxAction::class)->setName('PostcreateBox');

    $app->get('/categorie/{id}/prestations', GetPrestationsForOneCategorieAction::class)->setName('prestationsOneCategorie');

    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');

    $app->get('/', GetHomeAction::class)->setName('home');

    $app->get('/signin', GetConnectionAction::class)->setName('signin');

    $app->get('/signup', GetInscriptionAction::class)->setName('signup');

    $app->post('/signin', PostConnectionAction::class)->setName('Postsignin');

    $app->post('/signup', PostInscriptionAction::class)->setName('Postsignup');

    $app->get('/logout',GetDeconnexionAction::class )->setName('logout');

    $app->get('/box/{id}/prestations', GetPrestationsByOneBoxAction::class)->setName('GetPrestationsByOneBox');

    $app->post('/box/{box_id}/prestation/{prestation_id}/update', PostUpdatePrestationAction::class)->setName('PostUpdatePrestation');

    $app->get('/categories/create', GetCreateCategorieAction::class)->setName('GetCreateCategorie');

    $app->post('/categories/create', PostCreateCategorieAction::class)->setName('PostCreateCategorie');

    $app->post('/box/add/prestation', PostAddPrestionToBoxAction::class)->setName('PostAddPrestationToBox');

    $app->post('/box/courante', PostDefineCurrentBoxAction::class)->setName('PostDefineCurrentBox');

    $app->get('/box/predefinie', GetBoxesPredefiniesAction::class)->setName('GetBoxesPredefinies');

    $app->get('/box/predefinie/{id}', GetBoxesPredefiniesDetailsAction::class)->setName('GetBoxesPredefiniesDetails');

    $app->post('/box/del/prestation', PostDelPrestionToBoxAction::class)->setName('PostDelPrestationFromBox');

    $app->post('/box/validate', PostValidateBoxAction::class)->setName('PostValidateBox');

    $app->get('/box/payed/{id}', GetPaiementAction::class)->setName('GetPaiement');

    $app->post('/box/payed', PostPaiementAction::class)->setName('PostPaiement');

    $app->get('/box/delivery/{id}', GetDeliveryAction::class)->setName('GetDelivery');

    $app->get('/box/show', GetBoxDeliveredAction::class)->setName('GetBoxDelivered');

    $app->post('/box/show', PostBoxDeliveredAction::class)->setName('PostBoxDelivered');



    return $app;

};
