<?php

namespace App\Services;

use App\Controllers\ErrorController;
use App\Template;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class LoginService
{
    private Connection $connection;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => 'news-api',
            'user' => $_ENV['USER'],
            'password' => $_ENV['PASSWORD'],
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function execute(LoginServiceRequest $request): ?Template
    {
        $name = '';
        $index = 0;
        foreach ($this->connection->fetchAllAssociative("SELECT id,email,password,name FROM users") as $email) {
            if ($email['email'] == $request->getEmail() && $email['password'] == $request->getPassword()) {
                $index++;
                $name = $email['name'];
            }
        }
        if ($index == 1) {
            $_SESSION['name'] = $name;
            header('Location: /');
        } else {
            return (new ErrorController())->index(
                'wrong email or password!',
                'Go Back',
                '/login'
            );
        } return null;
    }
}