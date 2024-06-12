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
use Slim\Exception\HttpForbiddenException;
use Slim\Views\Twig;

class GetPrestationsByOneBoxAction extends Action
{
    private string $template;
    private BoxServiceInterface $boxService;
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;


    public function __construct()
    {
        $this->template = 'prestationsByOneBox.html.twig';
        $this->boxService = new BoxService();
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if($this->authProvider->isSignedIn()){
            if($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $args['id'])){
                $id = $args['id'];
                $box = $this->boxService->getBoxById($id);
                $prestations = $this->boxService->getPrestationsFromBox($id);
                $view = Twig::fromRequest($rq);
                return $view->render($rs, $this->template, ['box' => $box, 'prestations' => $prestations, 'csrf'=> CsrfService::generate()]);
            }
            throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour effectuer cette action');

        }

        return $rs->withHeader('Location', '/signin')->withStatus(302);
    }
}