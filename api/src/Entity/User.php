<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Column(name="id", type="string", nullable=false)
     * @ORM\Id
     */
    private string $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $bannedAt;

    /**
     * @ORM\Column(type="json", options={"default" : "{}"})
     */
    private array $params;

    public function __construct(string $id, array $params = [], ?DateTime $bannedAt = null)
    {
        $this->id       = $id;
        $this->params   = $params;
        $this->bannedAt = $bannedAt;
    }

    public function isBanned() : bool
    {
        return $this->bannedAt !== null || $this->id === '0';
    }

    public function id() : string
    {
        return $this->id;
    }
}
