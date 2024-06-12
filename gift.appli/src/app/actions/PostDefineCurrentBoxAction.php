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

class PostDefineCurrentBoxAction extends Action
{
    private BoxServiceInterface $boxService;
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->boxService = new BoxService();
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()){
            $data = $rq->getParsedBody();
            $box_id = $data['box_id'];
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $box_id)) {
                try {
                    CsrfService::check($data['csrf']);
                    $box = $this->boxService->getBoxById($data['box_id']);
                    if ($box['statut'] == 1) {
                        $_SESSION['box'] = $box;
                        return $rs->withHeader('Location', '/box/'.$data['box_id'].'/prestations')->withStatus(302);
                    }
                    throw new HttpForbiddenException($rq, 'Cette box ne peux pas être modifiée');

                } catch (\Exception $e) {
                    throw new \Exception('Donnée suspecte');
                }
            }
            throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour valider cette box');
        }
        return $rs->withHeader('Location', '/signin')->withStatus(302);
    }
}