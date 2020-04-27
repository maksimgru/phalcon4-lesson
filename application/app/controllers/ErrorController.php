<?php

//namespace App\Controllers;

class ErrorController extends ControllerBase
{
    public function route400Action() {
        $this->tag->setTitle('400 | Phalcon');
    }

    public function route404Action() {
        $this->tag->setTitle('404 | Phalcon');
    }

    public function route500Action() {
        $this->tag->setTitle('500 | Phalcon');
    }
}
