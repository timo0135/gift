<?php

namespace gift\api\app\actions;

use gift\api\core\services\catalogue\CatalogueService;
use gift\api\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPrestationsAction extends Action
{
    private CatalogueServiceInterface $categoryService;

    public function __construct()
    {
        $this->categoryService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $prestationsData = $this->categoryService->getPrestations();

        // Format the response data
        $formattedPrestations = [
            'type' => 'collection',
            'count' => count($prestationsData),
            'prestations' => []
        ];

        foreach ($prestationsData as $prestation) {
            $formattedPrestations['prestations'][] = [
                'prestation' => [
                    'id' => $prestation['id'],
                    'libelle' => $prestation['libelle'],
                    'description' => $prestation['description'],
                    'unite' => $prestation['unite'],
                    'tarif' => $prestation['tarif'],
                ],
                'links' => [
                    'self' => [
                        'href' => '/prestation?id=' . $prestation['id']
                    ],
                    'categorie' => [
                        'href' => '/categorie/' . $prestation['categorie']['id']
                    ]
                ]
            ];
        }

        $responseJson = json_encode($formattedPrestations);
        $rs->getBody()->write($responseJson);

        return $rs->withHeader('Content-Type', 'application/json');
    }
}