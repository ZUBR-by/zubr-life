<?php

namespace App\Comments;

use App\Auth\ActionNeedAuthorization;
use App\Entity\User;
use App\FileUploader;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\encode;

class AddComment extends AbstractController implements ActionNeedAuthorization
{
    public function __invoke(User $user, Request $request, Connection $dbal, FileUploader $fileUploader) : JsonResponse
    {
        $type = $request->get('type');
        $text = $request->get('text');
        if (! in_array($type, ['person', 'organization', 'event', 'place', 'ad'])) {
            return JsonResponse::fromJsonString(encode(['error' => 'Неправильный тип']));
        }
        $attachments = [];
        /** @var UploadedFile[] $files */
        $files = $request->files->all();
        foreach ($files as $file) {
            $mime = explode('/', $file->getClientMimeType());
            if (count($mime) !== 2) {
                continue;
            }
            if (! in_array($mime[0], ['image', 'video', 'audio'])
                || $file->getClientMimeType() !== 'application/pdf') {
                continue;
            }
            $link          = $fileUploader->uploadFile($file);
            $attachments[] = [
                'type'  => $mime[0],
                'value' => $link,
                'mime'  => $file->getClientMimeType(),
            ];
        }
        $dbal->insert(
            'comment',
            [
                $type . '_' . 'id' => $request->get('id'),
                'text'             => $text,
                'created_at'       => date('Y-m-d H:i:s'),
                'user_id'          => $user->id(),
                'attachments'      => encode($attachments),
            ]
        );

        return new JsonResponse([]);
    }
}
