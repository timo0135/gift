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

class PostPaiementAction extends Action
{
    private AuthorizationServiceInterface $authorizationService;
    private BoxServiceInterface $boxService;
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->authorizationService = new AuthorizationService();
        $this->boxService = new BoxService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        if ($this->authProvider->isSignedIn()) {
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $data['box_id'])) {
                try {
                    CsrfService::check($data['csrf']);
                    $this->boxService->payBox($data['box_id']);
                    return $rs->withHeader('Location', '/box/'.$data['box_id'].'/prestations')->withStatus(302);
                } catch (\Exception $e) {
                    throw new \Exception('Donnée suspecte');
                }
            }
            throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour payer cette box');
        }
        return $rs->withHeader('Location', '/signin')->withStatus(302);

    }
}