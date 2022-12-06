<?php

namespace App\Controllers;

use App\Services\EditService;
use App\Template;

class EditController
{
    public function show(): Template
    {
        return new Template('edit.twig');
    }

    public function store(): void
    {
        if ($_POST['name'] && $_POST['email'])
            (new EditService())->changeUserProperties($_SESSION['id'], $_POST['name'], $_POST['email']);
        else if ($_POST['password'])
            (new EditService())->changeUserPassword($_SESSION['id'], $_POST['password_new'], $_POST['password_confirm'], $_POST['password']);
    }
}