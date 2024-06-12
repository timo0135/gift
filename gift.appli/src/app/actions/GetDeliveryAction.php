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

class GetDeliveryAction extends Action
{
    private AuthorizationServiceInterface $authorizationService;
    private BoxServiceInterface $boxService;
    private AuthProviderInterface $authProvider;
    private string $template;

    public function __construct()
    {
        $this->authorizationService = new AuthorizationService();
        $this->boxService = new BoxService();
        $this->authProvider = new SessionAuthProvider();
        $this->template = 'delivery_box.html.twig';

    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()) {
            $id = $args['id'];
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $id)) {
                $token = $this->boxService->getTokenbyBox($id);
                $this->boxService->deliverBox($id);

                $uri = $rq->getUri();
                $baseUrl = $uri->getScheme() . '://' . $uri->getHost();
                if ($uri->getPort()) {
                    $baseUrl .= ':' . $uri->getPort();
                }

                $view = Twig::fromRequest($rq);
                return $view->render($rs, $this->template, ['id' => $id, 'token' => urlencode($token), 'baseUrl' => $baseUrl]);
            }
            throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour délivrer cette box');

        }
        return $rs->withHeader('Location', '/signin')->withStatus(302);

    }
}