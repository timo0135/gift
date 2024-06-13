<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class GetBoxDeliveredAction extends Action
{
    private string $template ;
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->template = 'box_delivered.html.twig';
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $token = $rq->getQueryParams()['token'] ?? null;
        if ($token === null) {
            throw new HttpNotFoundException($rq, 'Token manquant');
        }
        /*        $token = str_replace(' ', '+', $token);*/

        $box = $this->boxService->getBoxByToken($token);
        $prestation = $this->boxService->getPrestationsFromBox($box['id']);
        if ($box['statut'] == 4){
            $view = Twig::fromRequest($rq);
            return $view->render($rs, $this->template, ['box' => $box, 'prestations' => $prestation, 'csrf' => CsrfService::generate()]);
        }

        throw new HttpForbiddenException($rq, 'Vous n\'avez pas les droits pour consulter cette box car elle n\'est pas encore délivrée');

    }
}