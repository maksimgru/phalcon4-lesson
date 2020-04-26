<?php

//namespace App\Controllers;

use App\Forms\RegisterForm;
use App\Forms\LoginForm;
use Phalcon\Http\ResponseInterface;

class UserController extends ControllerBase
{
    public $loginForm;
    public $usersModel;

    public function initialize()
    {
        $this->loginForm = new LoginForm();
        $this->usersModel = new Users();
    }

    /**
     * Login Page View
     */
    public function loginAction()
    {
        /**
         * Changing dynamically the Document Title
         */
        $this->tag->setTitle('Login | Phalcon');
        // Login Form
        $this->view->form = new LoginForm();
    }

    /**
     * Login Action
     * @method: POST
     *
     * @param: email
     * @param: password
     *
     * @return ResponseInterface
     */
    public function loginSubmitAction()
    {
        // check is POST request
        if (!$this->request->isPost()) {
            return $this->response->redirect('user/login');
        }

        // Validate CSRF token
        if (!$this->security->checkToken()) {
            $this->flash->error('Invalid CSRF Token');

            return $this->dispatcher->forward([
                'controller' => $this->router->getControllerName(),
                'action'     => 'login',
            ]);
        }

        // Check form validation
        $this->loginForm->bind($this->request->getPost(), $this->usersModel);
        if (!$this->loginForm->isValid()) {
            foreach ($this->loginForm->getMessages() as $message) {
                $this->flash->error($message);
                break;
            }

            return $this->forwardTo('login');
        }

        // Find user with database
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = Users::findFirst([
            'email = :email:',
            'bind' => [
               'email' => $email,
            ]
        ]);

        // Check if User not exists
        if (!$user) {
            // To protect against timing attacks. Regardless of whether a user
            // exists or not, the script will take roughly the same amount as
            // it will always be computing a hash.
            $this->security->hash(rand());
            $this->flash->error('That username was not found - try again');

            return $this->forwardTo('login');
        }

        // Check password
        if (!$this->security->checkHash($password, $user->password)) {
            $this->flash->error('Your password is incorrect - try again.');

            return $this->forwardTo('login');
        }

        // Check User Active
        if (!$user->getActive()) {
            $this->flash->error('User Deactivate');

            return $this->forwardTo('login');
        }

        // Set a session
        $this->session->set('auth_user', $user);

        return $this->response->redirect('user/profile');
    }

    /**
     * Register Page View
     */
    public function registerAction()
    {
        $this->tag->setTitle('Register | Phalcon');
        $this->view->form = new RegisterForm();
    }

    /**
     * Register Action
     * @method: POST
     *
     * @param: name
     * @param: email
     * @param: password
     *
     * @return ResponseInterface|void
     */
    public function registerSubmitAction()
    {
        $form = new RegisterForm();

        // Check Post request
        if (!$this->request->isPost()) {
            return $this->response->redirect('user/register');
        }

        // Check form validation
        $form->bind($this->request->getPost(), $this->usersModel);
        if (!$form->isValid($this->request->getPost(), $this->usersModel)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
                break;
            }

            return $this->forwardTo('register');
        }

        // Populate user model
        $this->usersModel->setPassword($this->security->hash($this->request->getPost('password')));
        $this->usersModel->setActive(true);
        $now = new \DateTime();
        $this->usersModel->setCreatedAt($now);
        $this->usersModel->setUpdatedAt($now);

        // Check save
        if (!$this->usersModel->save()) {
            foreach ($this->usersModel->getMessages() as $message) {
                $this->flash->error($message);
                break;
            }

            return $this->forwardTo('register');
        }

        $this->flash->success('Thanks for registering!');

        return $this->response->redirect('user/login');
    }


    /**
     * User Profile
     */
    public function profileAction()
    {
        $this->authorized();
        $this->tag->setTitle('Profile | Phalcon');
        $this->view->user = $this->session->get('auth_user');
    }

    /**
     * User Logout
     */
    public function logoutAction()
    {
        // Destroy the whole session
        $this->session->destroy();

        return $this->response->redirect('user/login');
    }
}

