<?php

namespace gift\appli\app\providers\auth;

use gift\appli\core\services\auth\AuthService;
use gift\appli\core\services\auth\AuthServiceBadException;
use gift\appli\core\services\auth\AuthServiceInterface;

class SessionAuthProvider implements AuthProviderInterface
{
    private AuthServiceInterface $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register(string $email, string $password): void
    {
        try {
            $this->authService->register($email, $password);
        }catch (AuthServiceBadException $e) {
            throw new AuthServiceBadException($e->getMessage());
        }
    }

    public function signin(string $email, string $password): void
    {
        if ($this->authService->byCredentials($email, $password)) {
            $data = $this->authService->getUserByEmail($email);
            $_SESSION['user'] = $data;
        }
    }

    public function signout(): void
    {
        session_destroy();
    }

    public function isSignedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public function getSignedInUser(): array
    {
        return $_SESSION['user'];
    }
}