<?php

namespace gift\appli\core\services\authorization;

use gift\appli\core\domain\entities\Box;
use gift\appli\core\domain\entities\User;

class AuthorizationService implements AuthorizationServiceInterface
{

    public function isGranted(string $user_id, int $operation, string $ressource_id): bool
    {
        if ($operation === updateCatalogue) {
            return $this->isAdmin($user_id);
        }
        if ($operation === updateBox) {
            return $this->isOwner($user_id, $ressource_id) || $this->isAdmin($user_id);
        }
        if ($operation === createBox) {
            return $this->isAdmin($user_id) || isset($_SESSION['user']);
        }
        return false;

    }

    private function isAdmin(string $user_id): bool
    {
        $user = User::where('id', $user_id)->first();
        if ($user->role === 100) {
            return true;
        }
        return false;
    }

    private function isOwner(string $user_id, string $ressource_id): bool
    {
        $box = Box::where('id', $ressource_id)->first();
        if ($box->createur_id === $user_id) {
            return true;
        }
        return false;
    }
}