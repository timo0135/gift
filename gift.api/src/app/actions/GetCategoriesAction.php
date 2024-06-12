<?php

namespace gift\api\app\actions;

use gift\api\core\services\catalogue\CatalogueService;
use gift\api\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetCategoriesAction extends Action
{
    private CatalogueServiceInterface $categoryService;

    public function __construct()
    {
        $this->categoryService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $categoriesData = $this->categoryService->getCategories();

        // Formater les données comme spécifié
        $categoriesFormatted = [];
        foreach ($categoriesData as $category) {
            $categoriesFormatted[] = [
                'categorie' => [
                    'id' => $category['id'],
                    'libelle' => $category['libelle'],
                    'description' => $category['description']
                ],
                'links' => [
                    'self' => ['href' => '/categories/' . $category['id'] . '/']
                ]
            ];
        }

        $responseContent = [
            'type' => 'collection',
            'count' => count($categoriesFormatted),
            'categories' => $categoriesFormatted
        ];

        $responseJson = json_encode($responseContent);
        $rs->getBody()->write($responseJson);

        return $rs->withHeader('Content-Type', 'application/json');
    }
}