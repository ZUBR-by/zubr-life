<?php

namespace App\Rating;

use App\Auth\ActionRequiresAuthorization;
use App\Entity\Organization;
use App\Entity\Person;
use App\Entity\RatingPoint;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class RateAction extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(
        string $type,
        string $entity,
        string $id,
        EntityManagerInterface $em,
        User $user
    ) : JsonResponse {
        if (! in_array($entity, ['person', 'organization'])) {
            return new JsonResponse(['error' => 'invalid_entity']);
        }
        if (! in_array($type, ['downvote', 'upvote'])) {
            return new JsonResponse(['error' => 'invalid_type']);
        }
        $em->transactional(
            function (EntityManagerInterface $em) use (
                $type,
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
                if ($entity === 'person') {
                    $person = $em->getRepository(Person::class)->find($id);
                    if (! $person) {
                        return;
                    }
                    $object = RatingPoint::toPerson($type, $person, $user);
                } else {
                    $org = $em->getRepository(Organization::class)->find($id);
                    if (! $org) {
                        return;
                    }
                    $object = RatingPoint::toOrganization($type, $org, $user);
                }
                $em->persist($object);
            }
        );

        return new JsonResponse([]);
    }
}
