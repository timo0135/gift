<?php

namespace gift\appli\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GetConnectionAction extends Action
{
    private string $template;

    public function __construct()
    {
        $this->template = 'connection.html.twig';
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $view = Twig::fromRequest($rq);
        return $view->render($rs, $this->template);
    }
}