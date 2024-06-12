<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use gift\appli\core\services\auth\AuthServiceBadException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostInscriptionAction extends Action
{
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        if ($data['password'] == $data['password2']) {
            try {
                $this->authProvider->register($data['user_id'], $data['password']);
                $this->authProvider->signin($data['user_id'], $data['password']);
                return $rs->withHeader('Location', '/')->withStatus(302);
            }catch (AuthServiceBadException $e) {
                return $rs->withHeader('Location', '/signin')->withStatus(302);
            }

        }

        return $rs->withHeader('Location', '/signup')->withStatus(302);

    }
}