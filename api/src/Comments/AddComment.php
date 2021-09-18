<?php

namespace App\Comments;

use App\Auth\ActionRequiresAuthorization;
use App\FileUploader;
use App\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\encode;

class AddComment extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(User $user, Request $request, FileUploader $fileUploader) : JsonResponse
    {
        $type = $request->get('type');
        $text = $request->get('text');
        if (empty($text)) {
            return new JsonResponse(['error' => 'Текст комментария обязателен']);
        }
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
                && $file->getClientMimeType() !== 'application/pdf') {
                continue;
            }
            $link          = $fileUploader->uploadFile($file);
            $attachments[] = [
                'type' => $mime[0],
                'url'  => $link,
                'mime' => $file->getClientMimeType(),
            ];
        }

        return new JsonResponse([]);
    }
}
