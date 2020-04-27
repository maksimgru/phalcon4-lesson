<?php

//namespace App\Controllers;

use Phalcon\Messages\Messages;
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

    /**
     * @param Messages|array|string $messages
     * @param string                $type
     *
     * @return void
     */
    protected function setFlashMessages(
        $messages,
        string $type = 'error'
    ): void {
        $output = [];

        if ($messages instanceof Messages) {
            foreach ($messages as $message) {
                $output[] = strtoupper($message->getField()) . ': ' . $message->getMessage();
            }
        } else {
            $output = \is_array($messages) ? $messages : (array) $messages;
        }

        foreach ($output as $message) {
            $this->flash->message($type, $message);
        }
    }
}
