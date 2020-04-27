<?php

namespace App\Models;

class Role extends BaseModel
{
    public const ROLE_NAME_ADMIN = 'admin';
    public const ROLE_NAME_GUEST = 'guest';
    public const ROLE_NAME_USER = 'user';

    public const ROLE_NAMES = [
        self::ROLE_NAME_ADMIN,
        self::ROLE_NAME_GUEST,
        self::ROLE_NAME_USER,
    ];

    /**
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     * @var string
     * @Column(column="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @Column(column="description", type="string", nullable=true)
     */
    protected $description;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('phalcon_app');
        $this->setSource('roles');
        $this->hasMany('id', User::class, 'user_id', ['alias' => User::class]);
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
            'id'          => 'id',
            'name'        => 'name',
            'description' => 'description',
        ];
    }

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     *
     * @return Role
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
     * @return Role
     */
    public function setName(string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string|null $description
     *
     * @return Role
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the value of field description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $name
     *
     * @return int|null
     */
    public static function findIdByName(string $name): ?int
    {
        $roleInstance = static::findFirst([
            'name = :name:',
            'bind' => [
                'name' => $name,
            ]
        ]);

        return $roleInstance ? $roleInstance->getId() : null;
    }
}
