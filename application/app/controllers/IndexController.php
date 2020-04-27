<?php

//namespace App\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction() {
        $this->tag->setTitle('Home');
    }
}
