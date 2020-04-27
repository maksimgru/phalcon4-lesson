<?php

//namespace App\Controllers;

class ErrorController extends ControllerBase
{
    public function route400Action() {
        $this->tag->setTitle('400 Bad Request');
    }

    public function route403Action() {
        $this->tag->setTitle('403 Forbidden');
    }

    public function route404Action() {
        $this->tag->setTitle('404 Not Found');
    }

    public function route500Action() {
        $this->tag->setTitle('500 Server Error');
    }
}
