<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use gift\appli\core\services\box\BoxServiceNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;

class PostBoxDeliveredAction extends Action
{
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try{
            $data = $rq->getParsedBody();
            $csrf = $data['csrf'];
            CsrfService::check($csrf);
            $this->boxService->usedBox($data['id']);
            return $rs->withHeader('Location', '/')->withStatus(302);
        } catch (BoxServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, 'Box non trouvée');
        } catch (\Exception $e) {
            throw new HttpForbiddenException($rq, 'Données invalides');
        }
    }
}