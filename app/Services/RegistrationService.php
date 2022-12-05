<?php

namespace App\Services;

use App\Controllers\ErrorController;
use App\Template;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class RegistrationService
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

    public function execute(RegistrationServiceRequest $request): ?Template
    {
        $number = preg_match('@[0-9]@', $request->getPassword());
        $uppercase = preg_match('@[A-Z]@', $request->getPassword());
        $lowercase = preg_match('@[a-z]@', $request->getPassword());

        //IF EMAIL EXIST
        foreach ($this->connection->fetchAllAssociative("SELECT email FROM users") as $email) {
            if ($email['email'] == $request->getEmail()) {
                return (new ErrorController())->index(
                    'email you are trying to register already exists',
                    'Go Back',
                    '/registration'
                );
            }
        }
        //VALIDATION FOR PASSWORD
        if (strlen($request->getPassword()) < 8 || !$number || !$uppercase || !$lowercase)
            return (new ErrorController())->index(
                'password must be at least 8 characters in length and must contain at least one number, one upper case letter and one lower case letter',
                'Go Back',
                '/registration'
            );

        //CHECK FOR NAME VALIDATION
        if ($request->getPassword() == $request->getRepeatedPassword()) {
            if (preg_match("/^[a-zA-Z-' ]*$/", $request->getName())) {
                $this->connection->insert(
                    'users',
                    ['name' => $request->getName(), 'email' => $request->getEmail(), 'password' => $request->getPassword()]
                );
            } else {
                return (new ErrorController())->index(
                    'name contains wrong symbols',
                    'Go Back',
                    '/registration'
                );
            }
            header('Location: /login');
        } // IF PASSWORDS NOT EQUAL
        else
            return (new ErrorController())->index(
                'passwords are not equal!',
                'Go Back',
                '/registration'
            );
        return null;
    }
}