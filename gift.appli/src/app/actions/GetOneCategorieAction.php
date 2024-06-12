<?php

namespace gift\appli\app\actions;

use gift\appli\core\domain\entities\Categorie;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class GetOneCategorieAction extends Action
{
    private string $template;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->template = 'categorie.html.twig';
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try {
            $categorie = $this->catalogueService->getCategorieById($args['id']);
        } catch (CatalogueServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }


        // Utiliser Twig pour rendre le template
        $view = Twig::fromRequest($rq);

        return $view->render($rs, $this->template, $categorie);
    }
}
