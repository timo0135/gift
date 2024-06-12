<?php

namespace gift\appli\core\services\authorization;


interface AuthorizationServiceInterface
{

    public function isGranted(string $user_id, int $operation, string $ressource_id): bool;

}