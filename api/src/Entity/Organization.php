<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="organization")
 */
class Organization
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @ORM\Column(type="json", options={"default" : "{}"})
     */
    private array $params;

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

}
