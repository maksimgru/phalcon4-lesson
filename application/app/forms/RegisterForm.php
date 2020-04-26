<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Uniqueness;

class RegisterForm extends Form
{
    public function initialize()
    {
        /**
         * Name
         */
        $name = new Text('name', [
            'class'       => 'form-control',
            'placeholder' => 'Enter Full Name'
        ]);

        // form name field validation
        $name->addValidator(
            new StringLength(['max' => 255, 'message' => 'The name must be max 255 characters.'])
        );

        /**
         * Email Address
         */
        $email = new Text('email', [
            'class'       => 'form-control',
            'required'    => true,
            'placeholder' => 'Enter Email Address'
        ]);

        // form email field validation
        $email->addValidators([
            new PresenceOf(['message' => 'The email is required']),
            new Email(['message' => 'The e-mail is not valid']),
            new Uniqueness(['message' => 'Another user with same email already exists']),
        ]);

        /**
         * New Password
         */
        $password = new Password('password', [
            'class'       => 'form-control',
            'required'    => true,
            'placeholder' => 'Your Password'
        ]);

        $password->addValidators([
            new PresenceOf(['message' => 'Password is required']),
            new StringLength(['min' => 5, 'message' => 'Password is too short. Minimum 5 characters.']),
            new Confirmation(['with' => 'password_confirm', 'message' => 'Password doesn\'t match confirmation.']),
        ]);


        /**
         * Confirm Password
         */
        $passwordNewConfirm = new Password('password_confirm', [
            'class'       => 'form-control',
            'required'    => true,
            'placeholder' => 'Confirm Password'
        ]);

        $passwordNewConfirm->addValidators([
            new PresenceOf(['message' => 'The confirmation password is required']),
        ]);


        /**
         * Submit Button
         */
        $submit = new Submit('submit', [
            'value' => 'Register',
            'class' => 'btn btn-primary',
        ]);

        $this->add($name);
        $this->add($email);
        $this->add($password);
        $this->add($passwordNewConfirm);
        $this->add($submit);
    }
}
