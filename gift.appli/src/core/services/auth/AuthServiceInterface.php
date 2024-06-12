<?php

namespace gift\appli\core\services\auth;


use Faker\Core\Uuid;

interface AuthServiceInterface
{

    public function register(string $user_id, string $password): string;
    public function byCredentials(string $user_id, string $password): bool;
    public function getUserByEmail(string $email): array;



}