<?php

//namespace App\Controllers;

use App\Constants\RouteConst;
use App\Forms\RegisterForm;
use App\Forms\LoginForm;
use App\Models\Role;
use App\Models\User;
use Phalcon\Http\ResponseInterface;

class UserController extends ControllerBase
{
    public $loginForm;
    public $usersModel;

    public function initialize()
    {
        $this->loginForm = new LoginForm();
        $this->usersModel = new User();
    }

    /**
     * User list
     */
    public function indexAction()
    {
        $this->tag->setTitle('All Users');
        $this->view->users = User::find();
    }

    /**
     * Login Page View
     */
    public function loginAction()
    {
        if ($this->isLoggedIn()) {
            return $this->redirectTo();
        }
        $this->tag->setTitle('Login');
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
    public function loginSubmitAction(): ?ResponseInterface
    {
        // check is POST request
        if (!$this->request->isPost()) {
            return $this->redirectTo(RouteConst::ROUTE_NAME_LOGIN);
        }

        // Validate CSRF token
        if (!$this->security->checkToken()) {
            $this->setFlashMessages('Invalid CSRF Token');

            return $this->forwardTo('login');
        }

        // Check form validation
        $this->loginForm->bind($this->request->getPost(), $this->usersModel);
        if (!$this->loginForm->isValid()) {
            $this->setFlashMessages($this->loginForm->getMessages());

            return $this->forwardTo('login');
        }

        // Find user with database
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = User::findFirst([
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
            $this->setFlashMessages('That username was not found - try again');

            return $this->forwardTo('login');
        }

        // Check password
        if (!$this->security->checkHash($password, $user->password)) {
            $this->setFlashMessages('Your password is incorrect - try again.');

            return $this->forwardTo('login');
        }

        // Check User Active
        if (!$user->getActive()) {
            $this->setFlashMessages('User Deactivate');

            return $this->forwardTo('login');
        }

        // Set a session
        $this->session->set('auth_user', $user);

        return $this->redirectTo(RouteConst::ROUTE_NAME_PROFILE);
    }

    /**
     * Register Page View
     */
    public function registerAction()
    {
        if ($this->isLoggedIn()) {
            return $this->redirectTo();
        }
        $this->tag->setTitle('Register');
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
     * @return ResponseInterface
     */
    public function registerSubmitAction(): ?ResponseInterface
    {
        $form = new RegisterForm();

        // Check Post request
        if (!$this->request->isPost()) {
            return $this->redirectTo(RouteConst::ROUTE_NAME_REGISTER);
        }

        // Check form validation
        $form->bind($this->request->getPost(), $this->usersModel);
        if (!$form->isValid($this->request->getPost(), $this->usersModel)) {
            $this->setFlashMessages($form->getMessages());

            return $this->forwardTo('register');
        }
        // Populate user model
        $this->usersModel->setPassword($this->security->hash($this->request->getPost('password')));
        $this->usersModel->setActive(true);
        $this->usersModel->setRole(Role::ROLE_NAME_USER);
        $now = new \DateTime();
        $this->usersModel->setCreatedAt($now);
        $this->usersModel->setUpdatedAt($now);

        // Check save
        if (!$this->usersModel->save()) {
            $this->setFlashMessages($this->usersModel->getMessages());

            return $this->forwardTo('register');
        }

        $this->setFlashMessages('Thanks for registering! Please enter your Email and Password to login.', 'success');

        return $this->redirectTo(RouteConst::ROUTE_NAME_LOGIN);
    }


    /**
     * User Profile
     */
    public function profileAction()
    {
        $this->authorized();
        $this->tag->setTitle('Profile');
        $this->view->user = $this->session->get('auth_user');
    }

    /**
     * User Logout
     */
    public function logoutAction()
    {
        // Destroy the whole session
        $this->session->destroy();

        return $this->redirectTo(RouteConst::ROUTE_NAME_LOGIN);
    }
}

