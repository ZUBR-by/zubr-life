<?php

namespace App;

use App\FileUploader;
use App\GraphQLClient;
use App\User;
use Illuminate\Support\Js;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Filesystem\write_file;
use function Psl\Json\encode;

class AddProtocol extends AbstractController
{
    public function __invoke(
        User          $user,
        Request       $request,
        FileUploader  $fileUploader,
        GraphQLClient $graphQLClient,
        string        $uploadPath
    ): JsonResponse
    {
        /** @var UploadedFile[] $files */
        $files   = $request->files->all();
        $success = 0;
        foreach ($files as $file) {
            $success++;
            write_file($uploadPath . '/' . $file->getClientOriginalName(), $file->getContent());
        }

        return new JsonResponse(['uploaded' => $success]);
    }
}
