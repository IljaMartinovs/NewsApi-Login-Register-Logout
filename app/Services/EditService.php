<?php

namespace App\Services;

use App\Database;
use App\Validation;

class EditService
{
    public function changeUserProperties(?string $id, ?string $name, ?string $email): void
    {
        Validation::validateEmail($email);
        Validation::validateName($name);
        header('Location: /edit');
        if ($_SESSION['error'] == null) {
            Database::getConnection()->executeQuery("UPDATE users SET name = '{$name}', email = '{$email}' WHERE id = '{$id}'")->fetchAssociative();
            header('Location: /');
            session_destroy();
        }
    }

    public function changeUserPassword(string $id, string $passwordNew, string $passwordNewConfirm, string $passwordCurrent): void
    {
        Validation::validatePassword($passwordNew, $passwordNewConfirm, $passwordCurrent);
        header('Location: /edit');
        if ($_SESSION['error'] == null) {
            Database::getConnection()->executeQuery("UPDATE users SET password = '{$passwordNew}' WHERE id = '{$id}'")->fetchAssociative();
            header('Location: /');
            session_destroy();
        }
    }
}