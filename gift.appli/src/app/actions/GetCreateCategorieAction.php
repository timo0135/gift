<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Views\Twig;

class GetCreateCategorieAction extends Action
{
    private string $template;
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;


    public function __construct()
    {
        $this->template = 'create_categorie.html.twig';
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()) {
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateCatalogue, '')) {
                $view = Twig::fromRequest($rq);

                return $view->render($rs, $this->template, [
                    'csrf' => CsrfService::generate()
                ]);
            }
        }
        throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour effectuer cette action');

    }
}