<?php

//namespace App\Models;

use Phalcon\Mvc\Model\ResultInterface;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Users extends BaseModel
{
    /**
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     * @var string
     * @Column(column="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @Column(column="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * @var string
     * @Column(column="password", type="string", nullable=false)
     */
    protected $password;

    /**
     * @var integer
     * @Column(column="active", type="integer", length=4, nullable=false)
     */
    protected $active;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     *
     * @return Users
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string|null $name
     *
     * @return Users
     */
    public function setName(?string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field active
     *
     * @param bool $active
     *
     * @return Users
     */
    public function setActive(bool $active): self
    {
        $this->active = (int) $active;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the value of field name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Returns the value of field active
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return (bool) $this->active;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        $validator->add(
            'email',
            new UniquenessValidator(
                [
                    'model'   => $this,
                    'message' => 'Another user with same email already exists',
                    'cancelOnFail' => true,
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('phalcon_app');
        $this->setSource('users');
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return ResultsetInterface
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return Users|ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'id'         => 'id',
            'name'       => 'name',
            'email'      => 'email',
            'password'   => 'password',
            'active'     => 'active',
            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt',
        ];
    }
}
