<?php

namespace gift\appli\app\actions;

use gift\appli\core\domain\entities\Prestation;
use gift\appli\core\services\catalogue\CatalogueService;
use gift\appli\core\services\catalogue\CatalogueServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetPrestationsAction extends Action
{
    private string $template;
    private CatalogueServiceInterface $catalogueService;

    public function __construct()
    {
        $this->template = 'prestations.html.twig';
        $this->catalogueService = new CatalogueService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $order = $rq->getQueryParams()['order'] ?? null;
        if ($order == 'asc') {
            $prestations = $this->catalogueService->getPrestationsAsc();

        } else if($order == 'desc') {
            $prestations = $this->catalogueService->getPrestationsDesc();

        }
        else{
            $prestations = $this->catalogueService->getPrestations();
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, $this->template, [
            'prestations' => $prestations
        ]);
    }
}