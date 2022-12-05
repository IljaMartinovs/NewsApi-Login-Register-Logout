<?php

namespace App\Controllers;

use App\Template;

class ErrorController
{
    public function index(string $errorMessage, string $buttonName, string $buttonLink) //: Template
    {
        return (new Template("error.twig", [
                'errorMessage' => $errorMessage,
                'buttonName' => $buttonName,
                'buttonLink' => $buttonLink]
        ))->render();
    }
}