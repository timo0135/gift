<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use gift\appli\core\services\box\BoxServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;

class GetBoxesPredefiniesDetailsAction extends Action
{
    private string $template;
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->template = 'predefinedBoxDetails.html.twig';
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try {
            $box = $this->boxService->getBoxById($args['id']);
            if ($box['predefinie'] == 1) {
                $prestations = $this->boxService->getPrestationsFromBox($args['id']);
                Twig::fromRequest($rq)->render($rs, $this->template, ['box' => $box, 'prestations' => $prestations]);
                return $rs;
            }
            throw new HttpForbiddenException($rq, 'Vous n\'avez pas le droit de consulter cette page');
        } catch (BoxServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, 'La box n\'existe pas');
        }



    }
}