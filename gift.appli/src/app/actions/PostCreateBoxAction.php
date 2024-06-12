<?php

namespace gift\appli\app\actions;

use gift\appli\app\utils\CsrfService;
use gift\appli\core\domain\entities\Box;
use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceBadException;
use gift\appli\core\services\box\BoxServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class PostCreateBoxAction extends Action
{

    private BoxServiceInterface $catalogueService;
    private AuthorizationServiceInterface $authorizationService;
    private BoxServiceInterface $boxService;

    public function __construct()
    {
        $this->catalogueService = new BoxService();
        $this->authorizationService = new AuthorizationService();
        $this->boxService = new BoxService();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try {
            if (isset($_SESSION['user'])) {
                if ($this->authorizationService->isGranted($_SESSION['user']['id'], createBox, '')) {
                    $data = $rq->getParsedBody();

                    CsrfService::check($data['csrf']);
                    if(isset($data['kdo']))
                        $data['kdo'] = 1;
                    else
                        $data['kdo'] = 0;
                    $box = $this->catalogueService->createBox($data);
                    if ($data['box_predefinie_id'] != 0) {
                        $predefinie = $data['box_predefinie_id'];
                        $prestations = $this->catalogueService->getPrestationsFromBox($predefinie);
                        foreach ($prestations as $prestation) {
                            $this->catalogueService->addPrestationToBox($box, $prestation['id'], $prestation['pivot']['quantite']);
                        }
                    }
                    $_SESSION['box'] = $this->boxService->getBoxById($box);
                    return $rs->withHeader('Location', '/box/'.$box.'/prestations')->withStatus(302);
                }
            }
            return $rs->withStatus(302)->withHeader('Location', '/signin');
        } catch (BoxServiceBadException $e) {
            throw new \Exception('Donnée suspecte');
        }


    }
}