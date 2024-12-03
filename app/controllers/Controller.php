<?php

namespace app\controllers;

class Controller {
    protected function returnView($viewPath) {
        $fullPath = __DIR__ . '/../../public/assets/views/' . $viewPath;

        // Removed debugging output
        if (!file_exists($fullPath)) {
            die("View file not found: " . $fullPath);
        }

        require_once $fullPath;
    }
}
