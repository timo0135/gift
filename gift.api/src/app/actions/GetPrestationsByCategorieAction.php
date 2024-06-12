<?php

namespace gift\api\app\actions;

use gift\api\core\services\catalogue\CatalogueService;
use gift\api\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPrestationsByCategorieAction extends Action
{
    private CatalogueServiceInterface $categoryService;

    public function __construct()
    {
        $this->categoryService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $categoryId = $args['id'];
        $categorie = $this->categoryService->getCategorieById($categoryId);
        $prestationsData = $this->categoryService->getPrestationsByCategorie($categoryId);

        $formattedPrestations = array_map(function ($prestation) {
            return [
                'libelle' => $prestation['libelle'],
                'description' => $prestation['description'],
                'tarif' => $prestation['tarif'],
                'img' => $prestation['img'],
                'unite' => $prestation['unite'],
            ];
        }, $prestationsData);

        $formattedResponse = [
            'type' => 'resource',
            'name' => $categorie['libelle'],
            'count' => count($formattedPrestations),
            'prestations' => $formattedPrestations
        ];

        $rs->getBody()->write(json_encode($formattedResponse));
        return $rs->withHeader('Content-Type', 'application/json');



    }
}