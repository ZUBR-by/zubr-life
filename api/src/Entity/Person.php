<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 */
class Person
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
    private string $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $photoUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $attachments;

    /**
     * @var Organization[]
     * @ORM\ManyToMany(targetEntity="Organization")
     * @ORM\JoinTable(name="persons_organizations",
     *      joinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")}
     * )
     */
    private $organizations;
}
