<?php

namespace App\Core\Middlewares;

class CryptPassword
{
    public function __invoke()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !empty($_POST['password'])) 
        {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
    }
}