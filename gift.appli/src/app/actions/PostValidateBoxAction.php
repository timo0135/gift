<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceBadException;
use gift\appli\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;

class PostValidateBoxAction extends Action
{
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()) {
            $data = $rq->getParsedBody();
            $box_id = $data['box_id'];
            if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $box_id)) {
                try {
                    CsrfService::check($data['csrf']);
                    $this->boxService->validateBox($box_id);
                    return $rs->withStatus(302)->withHeader('Location', '/box/' . $box_id . '/prestations');
                }catch (BoxServiceBadException $e) {
                    throw new HttpForbiddenException($rq, $e->getMessage());
                }catch (\Exception $e) {
                    throw new \Exception('Donnée suspecte');
                }
            }
            throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour valider cette box');
        }
        return $rs->withStatus(302)->withHeader('Location', '/signin');

    }
}