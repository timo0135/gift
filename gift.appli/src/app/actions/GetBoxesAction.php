<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetBoxesAction extends Action
{
    private string $template;
    private BoxServiceInterface $boxService;
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->template = 'boxes.html.twig';
        $this->boxService = new BoxService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if($this->authProvider->isSignedIn()){
            $boxes = $this->boxService->getBoxByUser($_SESSION['user']['id']);
            $view = Twig::fromRequest($rq);
            return $view->render($rs, $this->template, ['boxes'=>$boxes]);
        }else{
            return $rs->withHeader('Location', '/signin')->withStatus(302);
        }
    }
}