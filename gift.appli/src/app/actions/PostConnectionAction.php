<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\core\services\auth\AuthService;
use gift\appli\core\services\auth\AuthServiceInterface;
use gift\appli\core\services\user\UserService;
use gift\appli\core\services\user\UserServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class PostConnectionAction extends Action
{
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        $this->authProvider->signin($data['user_id'], $data['password']);
        if ($this->authProvider->isSignedIn()){
            return $rs->withHeader('Location', '/')->withStatus(302);
        }
        return $rs->withHeader('Location', '/signin')->withStatus(302);

    }
}