<?php

namespace App;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Users
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getById(int $id): User
    {
        $user = $this->em->getRepository(User::class)->find($id);
        if (! $user) {
            $user = new User($id);
            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }
}
