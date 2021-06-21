<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="rating_point",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="unique_rate_per_org",
 *             columns={"organization_id", "user_id"}
 *         )
 *     }
 * )
 */
class RatingPoint
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
    private string $type;

    /**
     * @ORM\ManyToOne(targetEntity="Organization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     * })
     */
    private ?Organization $organization;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     */
    private ?Person $person;

    /**
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private ?Event $event;

    /**
     * @ORM\ManyToOne(targetEntity="Ad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ad_id", referencedColumnName="id")
     * })
     */
    private ?Ad $ad;

    /**
     * @ORM\ManyToOne(targetEntity="Issue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     * })
     */
    private ?Issue $issue;

    /**
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     * })
     */
    private ?Place $place;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private ?User $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $hiddenAt;

    private function __construct(
        User $user,
        string $type,
        ?Person $person = null,
        ?Organization $org = null,
        ?Place $place = null,
        ?Ad $ad = null,
        ?Issue $issue = null,
        ?Event $event = null
    ) {
        $this->user         = $user;
        $this->createdAt    = new DateTime();
        $this->hiddenAt     = null;
        $this->person       = $person;
        $this->organization = $org;

        $this->place = $place;
        $this->ad    = $ad;
        $this->issue = $issue;
        $this->event = $event;
        $this->type  = $type;
    }

    public static function toPerson(string $type, Person $person, User $user) : self
    {
        return new self($user, $type, $person);
    }
    public static function toOrganization(string $type, Organization $org, User $user) : self
    {
        return new self($user, $type, null, $org);
    }
}
