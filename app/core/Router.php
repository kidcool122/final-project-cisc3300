<?php

namespace app\core;

use app\controllers\MainController;
use app\controllers\UserController;

class Router {
    public $urlArray;

    function __construct()
    {
        $this->urlArray = $this->routeSplit();
        $this->handleRoutes();
    }

    protected function routeSplit() {
        $removeQueryParams = strtok($_SERVER["REQUEST_URI"], '?');
        return explode("/", trim($removeQueryParams, "/")); 
    }

    protected function handleRoutes() {
        if (empty($this->urlArray[0]) || $this->urlArray[0] === 'home') {
            $mainController = new MainController();
            $mainController->homepage();
            return;
        }

        if ($this->urlArray[0] === 'lemons') {
            $mainController = new MainController();
            $mainController->lemonsView();
            return;
        }

        if ($this->urlArray[0] === 'limes') {
            $mainController = new MainController();
            $mainController->limesView();
            return;
        }

       // API routes
if ($this->urlArray[0] === 'api') {
    $userController = new UserController();

    if ($this->urlArray[1] === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->registerUser();
        return;
    }

    if ($this->urlArray[1] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->loginUser();
        return;
    }

    if ($this->urlArray[1] === 'logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->logoutUser();
        return;
    }

    if ($this->urlArray[1] === 'profile' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $userController->getProfile();
        return;
    }

    if ($this->urlArray[1] === 'profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->updateProfile();
        return;
    }
}

        http_response_code(404);
        echo "Page not found.";
    }
}
