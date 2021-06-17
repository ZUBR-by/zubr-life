<?php

namespace App\Comments;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArchiveComment extends AbstractController
{
    public function __invoke(int $id, Connection $dbal) : JsonResponse
    {
        $dbal->transactional(function (Connection $dbal) use ($id) {
            $dbal->executeStatement(
                'INSERT INTO archived_comment 
                            (id, organization_id, person_id, event_id, ad_id, issue_id, 
                             place_id, user_id, text, attachments, params, created_at, hidden_at) 
                             SELECT id, organization_id, person_id, event_id, ad_id, issue_id, 
                                     place_id, user_id, text, attachments, params, created_at, hidden_at 
                               FROM comment WHERE id = ?',
                [$id]
            );
            $dbal->delete('comment', ['id' => $id]);
        });

        return new JsonResponse([]);
    }
}
