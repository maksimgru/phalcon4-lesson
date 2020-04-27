<?php

namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * Class BaseModel
 */
class BaseModel extends Model
{
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     * @Column(column="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var string
     * @Column(column="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * Returns the value of field created
     *
     * @return string
     */
    public function getCreatedAt(?string $format = null): string
    {
        $datetime =  (new \DateTime())
            ->createFromFormat(
                self::DATETIME_FORMAT,
                $this->createdAt
            )
        ;

        if ($format) {
            $datetime = $datetime->format($format);
        }

        return $datetime;
    }

    /**
     * Method to set the value of field created
     *
     * @param \DateTime $createdAt
     *
     * @return BaseModel
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt->format(self::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Returns the value of field updated
     *
     * @param null|string $format
     *
     * @return \DateTime|string
     */
    public function getUpdatedAt(?string $format = null)
    {
        $datetime =  (new \DateTime())
            ->createFromFormat(
                self::DATETIME_FORMAT,
                $this->updatedAt
            )
        ;

        if ($format) {
            $datetime = $datetime->format($format);
        }

        return $datetime;
    }

    /**
     * Method to set the value of field updated
     *
     * @param \DateTime $updatedAt
     *
     * @return BaseModel
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt->format(self::DATETIME_FORMAT);

        return $this;
    }
}
