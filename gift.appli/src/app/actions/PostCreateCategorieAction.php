<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceBadException;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;

class PostCreateCategorieAction extends Action
{
    private CatalogueServiceInterface $catalogueService;
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->catalogueService = new CatalogueService();
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()) {
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateCatalogue, '')) {
                $data = $rq->getParsedBody();
                $token = $data['csrf'];
                $libelle = $data['libelle'];
                $description = $data['description'];
                $img = $data['img'];

                try {
                    CsrfService::check($token);
                    $this->catalogueService->createCategorie([
                        'libelle' => $libelle,
                        'description' => $description,
                        'img' => $img
                    ]);

                } catch (CatalogueServiceBadException $e) {

                    throw new \Exception('Donnée suspecte');
                }
                return $rs->withHeader('Location', '/categories')->withStatus(302);
            }
        }
        throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour effectuer cette action');


    }
}