<?php

namespace App\Models\Traits;

trait Timestampable
{
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
     * Returns the value of field created_at
     *
     * @param null|string $format
     *
     * @return \DateTime|string
     */
    public function getCreatedAt(?string $format = null)
    {
        $datetime =  (new \DateTime())
            ->createFromFormat(
                $this->di->get('config')['date_format']['datetime'],
                $this->createdAt
            )
        ;

        if ($format) {
            $datetime = $datetime->format($format);
        }

        return $datetime;
    }

    /**
     * Returns the value of field created_at
     *
     * @param null|string $format
     *
     * @return string
     */
    public function getCreatedAtFormatted(?string $format = null): string
    {
        return $this->getCreatedAt($format ?: $this->di->get('config')['date_format']['datetime']);
    }

    /**
     * Method to set the value of field created_at
     *
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt->format($this->di->get('config')['date_format']['datetime']);

        return $this;
    }

    /**
     * Returns the value of field updated_at
     *
     * @param null|string $format
     *
     * @return \DateTime|string
     */
    public function getUpdatedAt(?string $format = null)
    {
        $datetime =  (new \DateTime())
            ->createFromFormat(
                $this->di->get('config')['date_format']['datetime'],
                $this->updatedAt
            )
        ;

        if ($format) {
            $datetime = $datetime->format($format);
        }

        return $datetime;
    }

    /**
     * Returns the value of field updated_at
     *
     * @param null|string $format
     *
     * @return string
     */
    public function getUpdatedAtFormatted(?string $format = null): string
    {
        return $this->getUpdatedAt($format ?: $this->di->get('config')['date_format']['datetime']);
    }

    /**
     * Method to set the value of field updated_at
     *
     * @param \DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt->format($this->di->get('config')['date_format']['datetime']);

        return $this;
    }
}
