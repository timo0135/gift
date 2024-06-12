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

class GetPaiementAction extends Action
{
    private AuthorizationServiceInterface $authService;
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->authService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()) {
            if ($this->authService->isGranted($_SESSION['user']['id'], updateBox, $args['id'])) {
                $id = $args['id'];
                $view = Twig::fromRequest($rq);
                return $view->render($rs, 'form_paye_box.html.twig', ['id' => $id, 'csrf' => CsrfService::generate()]);
            }
            throw new HttpForbiddenException($rq, 'You are not allowed to access this page');
        }
        return $rs->withHeader('Location', '/signin')->withStatus(302);

    }
}