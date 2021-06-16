<?php

namespace App;

use App\Auth\ActionNeedAuthorization;
use App\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\encode;

class AddComment extends AbstractController implements ActionNeedAuthorization
{
    public function __invoke(User $user, Request $request, Connection $dbal) : JsonResponse
    {
        $type = $request->get('type');
        $text = $request->get('text');
        if (! in_array($type, ['person', 'organization', 'event', 'place', 'ad'])) {
            return JsonResponse::fromJsonString(encode(['error' => 'Неправильный тип']));
        }
        $dbal->insert('comment',
            [
                $type . '_' . 'id' => $request->get('id'),
                'text'             => $text,
                'created_at'       => date('Y-m-d H:i:s'),
                'user_id'          => $user->id(),
            ]
        );

        return new JsonResponse([]);
    }
}
