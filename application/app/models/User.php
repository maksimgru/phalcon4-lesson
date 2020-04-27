<?php

namespace App\Models;

use App\Models\Traits\Timestampable;

class User extends BaseModel
{
    use Timestampable;

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
     * @var integer
     * @Column(column="role_id", type="integer", length=11, nullable=true)
     */
    protected $role_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('phalcon_app');
        $this->setSource('users');
        $this->belongsTo('role_id', Role::class, 'id', ['alias' => Role::class]);
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
            'role_id'    => 'role_id',
            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt',
        ];
    }

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     *
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
     */
    public function setActive(bool $active): self
    {
        $this->active = (int) $active;

        return $this;
    }

    /**
     * @param int $roleId
     *
     * @return User
     */
    public function setRoleId(int $roleId): self
    {
        $this->role_id = $roleId;

        return $this;
    }

    /**
     * @param Role|string|int $role
     *
     * @return User
     */
    public function setRole($role): self
    {
        if ($role instanceof Role) {
            $this->role_id = $role->getId();
        } else {
            $this->role_id = Role::findIdByName(
                \in_array($role, Role::ROLE_NAMES, true)
                    ? $role
                    : Role::ROLE_NAME_USER
            );
        }

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
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->getRelated(Role::class)->getName();
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->getRelated(Role::class);
    }
}
