<?php

namespace gift\api\app\actions;

use gift\api\core\services\box\BoxService;
use gift\api\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetBoxAction extends Action
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $boxId = $args['id'];
        $box = $this->boxService->getBoxById($boxId);
        $prestations = $this->boxService->getPrestationsFromBox($boxId);

        $formattedPrestations = array_map(function ($prestation) {
            return [
                'libelle' => $prestation['libelle'],
                'description' => $prestation['description'],
                'contenu' => [
                    'box_id' => $prestation['pivot']['box_id'],
                    'presta_id' => $prestation['pivot']['presta_id'],
                    'quantite' => $prestation['pivot']['quantite'],
                ]
            ];
        }, $prestations);

        $formattedBox = [
            'type' => 'resource',
            'box' => [
                'id' => $box['id'],
                'libelle' => $box['libelle'],
                'description' => $box['description'],
                'message_kdo' => $box['message_kdo'],
                'statut' => $box['statut'],
                'prestations' => $formattedPrestations
            ]
        ];

        $rs->getBody()->write(json_encode($formattedBox));
        return $rs->withHeader('Content-Type', 'application/json');

    }
}