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

class PostDelPrestionToBoxAction extends Action
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
        try {
            if ($this->authProvider->isSignedIn()) {
                $data = $rq->getParsedBody();
                $box_id = $data['box_id'];
                if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $box_id)) {

                    CsrfService::check($data['csrf']);
                    $presta_id = $data['presta_id'];
                    $this->boxService->delPrestationFromBox($box_id, $presta_id);
                    return $rs->withStatus(302)->withHeader('Location', '/box/' . $box_id . '/prestations');
                }
                throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour effectuer cette action');
            }
            return $rs->withStatus(403)->withHeader('Location', '/signin');
        }catch (\Exception $e) {
            throw new \Exception('Donnée suspecte');
        }
    }
}