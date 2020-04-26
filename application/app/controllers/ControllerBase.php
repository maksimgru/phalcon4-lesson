<?php
declare(strict_types=1);

//namespace App\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function authorized()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->redirect('user/login');
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->session->has('auth_user');
    }

    /**
     * @param string      $action
     * @param null|string $controller
     *
     * @return void
     */
    protected function forwardTo(
        string $action,
        ?string $controller = null
    ) {
        return $this->dispatcher
            ->forward([
                'action'     => $action,
                'controller' => $controller ?: $this->router->getControllerName(),
            ])
        ;
    }
}
