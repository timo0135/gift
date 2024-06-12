<?php

namespace gift\appli\app\actions;

use gift\appli\core\domain\entities\Categorie;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetCategoriesAction extends Action
{
    private string $template;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->template = 'categories.html.twig';
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $categories = $this->catalogueService->getCategories();
        $view = Twig::fromRequest($rq);
        return $view->render($rs, $this->template, ['categories'=>$categories]);
    }
}