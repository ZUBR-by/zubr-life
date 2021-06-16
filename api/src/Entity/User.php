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
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $bannedAt;

    /**
     * @ORM\Column(type="json", options={"default" : "{}"})
     */
    private array $params;

    public function __construct(int $id, array $params = [], ?DateTime $bannedAt = null)
    {
        $this->id       = $id;
        $this->params   = $params;
        $this->bannedAt = $bannedAt;
    }

    public function isBanned() : bool
    {
        return $this->bannedAt !== null;
    }

    public function id() : int
    {
        return $this->id;
    }
}
