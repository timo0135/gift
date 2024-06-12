<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use gift\appli\core\domain\entities\Prestation;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceBadException;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use gift\appli\core\services\catalogue\CatalogueServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class GetOnePrestationAction extends Action
{
    private string $template;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->template = 'prestation.html.twig';
        $this->catalogueService = new CatalogueService();
    }
    public function __invoke(Request $rq, Response $rs, $args): Response
    {

        try {
            $_SESSION['confirmation_message'] = null;
            $id = $rq->getQueryParams()['id'] ?? null;
            if (is_null($id)) {
                throw new HttpBadRequestException($rq, 'Identifiant de prestation manquant');
            }
            $prestation = $this->catalogueService->getPrestationById($id);
        } catch (CatalogueServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
        $categorie = $this->catalogueService->getCategorieById($prestation['cat_id']);

        $view = Twig::fromRequest($rq);

        return $view->render($rs, $this->template, [
            'prestation' => $prestation,
            'categorie' => $categorie,
            'csrf' => CsrfService::generate()
        ]);
    }
}
