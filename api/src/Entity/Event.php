<?php

namespace App\Entity;

use App\EmptyField;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use function Psl\Str\length;
use function Symfony\Component\Translation\t;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Event
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
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $hidden = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $approved = null;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private ?User $user;

    public function __construct(
        User $user,
        string $name,
        string $description = '',
        ?DateTime $createdAt = null,
        array $attachments = [],
        ?float $longitude = null,
        ?float $latitude = null
    ) {
        if (length($name) === 0) {
            throw new EmptyField('Название');
        }
        $this->user        = $user;
        $this->name        = $name;
        $this->description = $description;
        $this->createdAt   = $createdAt ?: new DateTime();
        $this->attachments = $attachments;
        $this->longitude   = $longitude;
        $this->latitude    = $latitude;
        $this->params      = [];
    }

    public function addAttachments(array $attachments) : void
    {
        $this->attachments = array_merge($this->attachments, $attachments);
    }
}
