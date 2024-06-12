<?php

namespace gift\appli\app\actions;

use gift\appli\app\providers\auth\AuthProviderInterface;
use gift\appli\app\providers\auth\SessionAuthProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;

class GetDeconnexionAction extends Action
{
    private AuthProviderInterface $authProvider;

    public function __construct()
    {
        $this->authProvider = new SessionAuthProvider();
    }

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        if ($this->authProvider->isSignedIn()) {
            $this->authProvider->signout();
            return $rs->withHeader('Location', '/')->withStatus(302);
        }
        throw new HttpForbiddenException($rq, 'You are not signed in');

    }
}