<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\app\utils\CsrfService;
use gift\appli\core\services\authorization\AuthorizationService;
use gift\appli\core\services\authorization\AuthorizationServiceInterface;
use gift\appli\core\services\box\BoxService;
use gift\appli\core\services\box\BoxServiceInterface;
use gift\appli\core\services\catalogue\CatalogueServiceBadException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;

class PostAddPrestionToBoxAction extends Action
{
    private BoxServiceInterface $boxService;
    private AuthorizationServiceInterface $authorizationService;
    private AuthProviderInterface $authProvider;


    public function __construct()
    {
        $this->boxService = new BoxService();
        $this->authorizationService = new AuthorizationService();
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        $prestation_id = $data['prestation_id'];
        $token = $data['csrf'];
        $quantity = $data['quantity'];
        if ($this->authProvider->isSignedIn()){
            if (isset($_SESSION['box'])) {
                if ($this->authorizationService->isGranted($_SESSION['user']['id'], updateBox, $_SESSION['box']['id'])) {
                    $box_id = $_SESSION['box']['id'];
                    try {
                        CsrfService::check($token);
                        $this->boxService->addPrestationToBox($box_id, $prestation_id, $quantity);
                        $_SESSION['confirmation_message'] = 'Prestation ajoutée avec succès à la box !';

                    } catch (\Exception $e) {

                        throw new \Exception('Donnée suspecte');
                    }

                    return $rs->withHeader('Location', '/prestation?id='.$prestation_id)->withStatus(302);
                }
            }
            $_SESSION['confirmation_message'] = 'Il faut d\'abord créer une box ou définir une box courante pour ajouter une prestation';
            return $rs->withHeader('Location', '/prestation?id='.$prestation_id)->withStatus(302);

        }

        $_SESSION['confirmation_message'] = 'Il faut être connecté pour ajouter une prestation à la box';
        return $rs->withHeader('Location', '/prestation?id='.$prestation_id)->withStatus(302);
    }
}