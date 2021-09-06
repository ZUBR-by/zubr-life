<?php

namespace App\Rating;

use App\Auth\ActionRequiresAuthorization;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UnrateAction extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(
        string $entity,
        string $id,
        EntityManagerInterface $em,
        User $user
    ) : JsonResponse {
        if (! in_array($entity, ['person', 'organization'])) {
            return new JsonResponse(['error' => 'invalid_entity']);
        }
        $em->transactional(
            function (EntityManagerInterface $em) use (
                $entity,
                $id,
                $user
            ) : void {
                $em->getConnection()->delete(
                    'rating_point',
                    [
                        $entity . '_id' => $id,
                        'user_id'       => $user->id(),
                    ]
                );
            }
        );

        return new JsonResponse([]);
    }
}
