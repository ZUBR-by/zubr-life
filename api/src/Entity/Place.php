<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="place")
 */
class Place
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=4000, options={"default" : ""})
     */
    private string $description;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=11, scale=8)
     */
    private ?float $longitude;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=11, scale=8)
     */
    private ?float $latitude;

    /**
     * @ORM\Column(type="json", options={"default" : "[]"})
     */
    private array $attachments;

    /**
     * @ORM\Column(type="json", options={"default" : "{}"})
     */
    private array $params;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $hiddenAt;

    public function __construct(
        string $name,
        string $description = '',
        array $attachments = [],
        array $params = [],
        ?float $latitude = null,
        ?float $longitude = null,
        ?DateTime $hiddenAt = null
    ) {
        $this->name        = $name;
        $this->description = $description;
        $this->attachments = $attachments;
        $this->params      = $params;
        $this->latitude    = $latitude;
        $this->longitude   = $longitude;
        $this->hiddenAt    = $hiddenAt;
    }
}
