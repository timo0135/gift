<?php

namespace gift\appli\app\actions;

use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetBoxesPredefiniesAction extends Action
{
    private string $template;
    private BoxServiceInterface $boxService;


    public function __construct()
    {
        $this->template = 'predefinedBox.html.twig';
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $boxes = $this->boxService->getPredifinedBoxes();
        Twig::fromRequest($rq)->render($rs, $this->template, ['boxes' => $boxes]);
        return $rs;
    }
}