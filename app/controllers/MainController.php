<?php

namespace app\controllers;

class MainController extends Controller {
    public function homepage() {
        $this->returnView('main/homepage.html');
    }

    public function lemonsView() {
        $this->returnView('users/LemonsView.html');
    }

    public function limesView() {
        $this->returnView('users/LimesView.html');
    }
}
