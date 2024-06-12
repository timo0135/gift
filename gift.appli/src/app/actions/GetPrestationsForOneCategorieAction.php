<?php

namespace gift\appli\app\actions;

use gift\appli\core\domain\entities\Categorie;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class GetPrestationsForOneCategorieAction extends Action
{
    private string $template;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->template = 'prestations_by_categorie.html.twig';
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try {
            $categorie = $this->catalogueService->getCategorieById($args['id']);
            $order = $rq->getQueryParams()['order'] ?? null;
            if ($order == 'asc') {
                $prestations = $this->catalogueService->getPrestationsbyCategorieAsc($args['id']);

            } else if($order == 'desc') {
                $prestations = $this->catalogueService->getPrestationsbyCategorieDesc($args['id']);

            }
            else{
                $prestations = $this->catalogueService->getPrestationsByCategorie($args['id']);
            }

        } catch (CatalogueServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, $this->template, [
            'prestations' => $prestations,
            'categorie' => $categorie
        ]);
    }
}