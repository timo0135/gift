<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetHomeAction extends Action
{
    private string $template;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->template = 'home.html.twig';
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {

        $view = Twig::fromRequest($rq);
        $prestationsByCategories = $this->catalogueService->getPrestationsByCategories();

        return $view->render($rs, $this->template, [
            'categories' => $prestationsByCategories,
        ]);
    }
}