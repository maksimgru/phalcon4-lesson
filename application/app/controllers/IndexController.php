<?php
declare(strict_types=1);

//namespace App\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction() {
        $this->tag->setTitle('Home | Phalcon');
    }

    public function show404Action() {
        $this->tag->setTitle('404 Not found | Phalcon');
    }

    public function show503Action() {
        $this->tag->setTitle('Home | Phalcon');
    }
}

