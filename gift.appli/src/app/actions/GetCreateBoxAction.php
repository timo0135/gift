<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetCreateBoxAction extends Action
{
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;
    private string $template;
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->template = 'create_box.html.twig';
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $view = Twig::fromRequest($rq);
        if ($this->authProvider->isSignedIn()) {
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], createBox, '')) {
                $id = $rq->getQueryParams()['id'] ?? null;
                $box = $this->boxService->getPredifinedBoxes();
                return $view->render($rs, $this->template, [
                    'csrf' => CsrfService::generate(),
                    'boxes' => $box,
                    'id' => $id
                ]);
            }
        }

        return $rs->withStatus(302)->withHeader('Location', '/signin');


    }
}