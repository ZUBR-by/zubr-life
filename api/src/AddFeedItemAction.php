<?php

namespace App;

use App\Auth\ActionRequiresAuthorization;
use App\Errors\InvalidField;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AddFeedItemAction extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(
        Request $request,
        FileUploader $fileUploader
    ) : Response {
        $attachments = [];
        $name        = (string) $request->get('name');
        $description = (string) $request->get('description');
        /** @var UploadedFile[] $files */
        $files = $request->files->all();
        foreach ($files as $file) {
            $mime = explode('/', $file->getClientMimeType());
            if (count($mime) !== 2) {
                continue;
            }
            if (! (in_array($mime[0], ['image', 'video', 'audio'])
                || $file->getClientMimeType() === 'application/pdf')) {
                continue;
            }
            $link          = $fileUploader->uploadFile($file);
            $attachments[] = [
                'type'  => $mime[0],
                'url' => $link,
                'mime'  => $file->getClientMimeType(),
            ];
        }

        return new JsonResponse([]);
    }
}
