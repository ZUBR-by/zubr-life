<?php

namespace App;

use App\Auth\ActionNeedAuthorization;
use App\Entity\Ad;
use App\Entity\Event;
use App\Entity\User;
use App\Errors\InvalidField;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function Psl\Json\encode;

class AddFeedItemAction extends AbstractController implements ActionNeedAuthorization
{
    public function __invoke(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader
    ) : Response {
        $em->transactional(function (EntityManagerInterface $em) use ($request, $user, $fileUploader) : void {
            $attachments = [];
            $name        = (string) $request->get('name');
            $description = (string) $request->get('description');

            if ($request->get('type') === 'event') {
                try {
                    $createdAt = new DateTime((string) $request->get('created_at'));
                } catch (Throwable $e) {
                    throw new InvalidField('Дата');
                }
                $object = new Event($user, $name, $description, $createdAt);
            } else {
                $object = new Ad($user, $name, $description);
            }
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
                    'value' => $link,
                    'mime'  => $file->getClientMimeType(),
                ];
            }
            $object->addAttachments($attachments);

            $em->persist($object);
        });


        return new JsonResponse([]);
    }
}
