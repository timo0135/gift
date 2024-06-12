<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use gift\appli\core\services\box\BoxServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;

class PostUpdatePrestationAction extends Action
{
    private BoxServiceInterface $boxService;
    private AuthorizationServiceInterface $authorizationService;

    public function __construct()
    {
        $this->boxService = new BoxService();
        $this->authorizationService = new AuthorizationService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try {
            if (isset($_SESSION['user'])) {
                if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $args['box_id'])) {
                    $boxId = $args['box_id'];
                    $prestationId = $args['prestation_id'];
                    $quantity = $rq->getParsedBody();
                    $quantity = $quantity['quantity'];

                    $totalPrice = $this->boxService->updatePrestationQuantity($boxId, $prestationId, $quantity);

                    $data = ['success' => true, 'total_price' => $totalPrice];
                    $rs->getBody()->write(json_encode($data));
                    return $rs->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
                }
            }

        }catch (BoxServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
        throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour effectuer cette action');


    }
}