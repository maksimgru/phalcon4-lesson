<?php

namespace App\Plugins;

use App\Models\Role;
use Phalcon\Acl\Enum;
use Phalcon\Acl\Resource;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin
{
    /**
     * Returns an existing or new access control list
     *
     * @param DiInterface $di
     *
     * @returns AclList
     */
    public function getAcl(DiInterface $di)
    {
        $persistent = $di->get('persistent');

        if (!isset($persistent->acl)) {
            $acl = new AclList();
            $acl->setDefaultAction(Enum::ALLOW);

            $roles = Role::ROLE_NAMES;

            $components = [
                [
                    'name' => 'index',
                    'actions' => ['*', 'index'],
                ],
                [
                    'name' => 'error',
                    'actions' => ['*', 'route400', 'route403', 'route404', 'route500'],
                ],
                [
                    'name' => 'user',
                    'actions' => ['*', 'index', 'login', 'loginsubmit', 'register', 'registersubmit', 'profile', 'logout'],
                ]
            ];

            $aclItems = [
                // By Role
                [
                    'role' => Role::ROLE_NAME_GUEST,
                    'allow' => [
                        'user' => ['login', 'loginsubmit', 'register', 'registersubmit'],
                        // componentName => actions
                    ],
                    'deny' => [
                        'user' => ['index', 'profile'],
                        // componentName => actions
                    ],
                ],
                [
                    'role' => Role::ROLE_NAME_USER,
                    'allow' => [],
                    'deny' => [
                        'user' => ['index'],
                    ],
                ],
                [
                    'role' => Role::ROLE_NAME_ADMIN,
                    'allow' => [],
                    'deny' => [],
                ]
            ];

            // Register roles
            foreach($roles as $role) {
                $acl->addRole($role);
            }

            // Add components with roles and actions
            foreach($components as $component) {
                $acl->addComponent($component['name'], $component['actions']);
            }

            // Set Allow\Deny
            foreach ($aclItems as $aclItem){
                foreach ($aclItem['allow'] as $componentName => $allowActions) {
                    $acl->allow($aclItem['role'], $componentName, $allowActions);
                }
                foreach ($aclItem['deny'] as $componentName => $denyActions) {
                    $acl->deny($aclItem['role'], $componentName, $denyActions);
                }
            }

            //The acl is stored in session, APC would be useful here too
            $persistent->acl = $acl;
        }

        return $persistent->acl;
    }

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth = $dispatcher->getDI()->get('session')->get('auth_user');
        $role = $auth ? $auth->getRoleName() : Role::ROLE_NAME_GUEST;

        $controller = strtolower($dispatcher->getControllerName());
        $action = strtolower($dispatcher->getActionName());

        /** @var AclList $acl */
        $acl = $this->getAcl($dispatcher->getDI());
        if (!$acl->isComponent($controller)) {
            $dispatcher->forward([
                'controller' => 'error',
                'action'     => 'route404',
            ]);

            return false;
        }

        $allowed = $acl->isAllowed($role, $controller, $action);
        if (!$allowed) {
            $dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'route403',
            ));

            return false;
        }
    }
}
