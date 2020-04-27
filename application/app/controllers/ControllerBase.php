<?php

//namespace App\Controllers;

use App\Constants\RouteConst;
use Phalcon\Http\ResponseInterface;
use Phalcon\Messages\Messages;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Router\Route;

class ControllerBase extends Controller
{
    /**
     * @return ResponseInterface|null
     */
    public function authorized(): ?ResponseInterface
    {
        if (!$this->isLoggedIn()) {
            return $this->redirectTo(RouteConst::ROUTE_NAME_LOGIN);
        }

        return null;
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
     * @return null
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
     * @param string $routeName
     * @param bool   $externalRedirect
     * @param int    $statusCode
     *
     * @return ResponseInterface
     */
    protected function redirectTo(
        string $routeName = RouteConst::ROUTE_NAME_HOME,
        bool $externalRedirect = false,
        int $statusCode = 302
    ): ResponseInterface {
        /** @var Route $route */
        $route = $this->router->getRouteByName($routeName);
        $route = $route ?: $this->router->getRouteByName(RouteConst::ROUTE_NAME_HOME);
        $pattern = $route->getPattern();

        return $this->response->redirect($pattern, $externalRedirect, $statusCode);
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
