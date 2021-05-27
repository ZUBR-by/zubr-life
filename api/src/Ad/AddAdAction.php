<?php

namespace App\Ad;

use App\Auth\ActionNeedAuthorization;
use App\Entity\Ad;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AddAdAction extends AbstractController implements ActionNeedAuthorization
{
    public function __invoke(User $user, array $payload, EntityManagerInterface $em): Response
    {
        $ad = $em->transactional(function (EntityManagerInterface $em) use ($payload, $user) : Ad {
            $ad = new Ad($user, $payload['name'], $payload['description']);
            $em->persist($ad);
            return $ad;
        });


        return new JsonResponse([]);
    }
}
