<?php

namespace App\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class ExceptionHandlerPlugin
{
    /**
     * This action is executed before perform any action in the application
     *
     * @param Event         $event
     * @param MvcDispatcher $dispatcher
     * @param \Exception    $exception
     *
     * @return bool
     * @throws \Exception
     */
    public function beforeException(
        Event $event,
        MvcDispatcher $dispatcher,
        \Exception $exception
    ): bool {
        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case DispatcherException::EXCEPTION_INVALID_PARAMS:
                    $action = 'route400';
                    break;
                case DispatcherException::EXCEPTION_ACTION_NOT_FOUND:
                case DispatcherException::EXCEPTION_HANDLER_NOT_FOUND:
                    $action = 'route404';
                    break;
                case DispatcherException::EXCEPTION_INVALID_HANDLER:
                case DispatcherException::EXCEPTION_CYCLIC_ROUTING:
                default:
                    $action = 'route500';
                    break;
            }
        }

        $dispatcher->forward([
            'controller' => 'error',
            'action'     => $action ?? 'route500'
        ]);

        return false;
    }
}
