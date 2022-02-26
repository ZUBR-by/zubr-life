<?php

namespace App;

use App\FileUploader;
use App\GraphQLClient;
use App\User;
use Aws\S3\S3Client;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Filesystem\write_file;
use function Psl\Json\encode;
use function Psl\Vec\map;

class AddMessage extends AbstractController
{
    public function __invoke(
        User          $user,
        Request       $request,
        FileUploader  $fileUploader,
        GraphQLClient $graphQLClient,
        string        $uploadPath
    ): JsonResponse
    {
        $message = $request->request->all();

        $message['categories'] = map($message['categories'], fn(string|int $i) => (int)$i);
        sort($message['categories']);
        $variables = [
            'extra'         => [
                'commission_code'        => $message['commission_number'],
                'publish_media'          => $message['publish_media'],
                'role'                   => $message['role'],
                'original_commission_id' => $message['commission']['id'] ?? null
            ],
            'date'          => (new DateTime())->setDate(2022, 2, $message['day'])->format('Y-m-d'),
            'categories'    => $message['categories'],
            'description'   => $message['description'],
            'commission_id' => $message['commission']['id'] ?? null,
            'attachments'   => [],
            'observer_id'   => '',
            'district'      => $message['district']
        ];
        $response  = $graphQLClient->requestObserverBot(/** @lang GraphQL */ <<<'GraphQL'
mutation(
    $categories: jsonb, 
    $description: String, 
    $commission_id: Int,
    $date: date,
    $attachments: jsonb,
    $extra: jsonb
    $observer_id: String,
    $district: String
) {
    message: insert_elections_observation_messages_one(
        object: {
            extra: $extra
            reported_for: $date,
            categories: $categories,
            description: $description,
            commission_id: $commission_id,
            attachments: $attachments,
            observer_id: $observer_id,
            district: $district
        }
    ) {
        id
    }
}
GraphQL
            ,
            $variables
        );
        /** @var UploadedFile[] $files */
        $files      = $request->files->all();
        $s3         = new S3Client([
            'region'      => 'eu-north-1',
            'version'     => 'latest',
            'credentials' => [
                'key'    => $_ENV['AWS_KEY'],
                'secret' => $_ENV['AWS_SECRET'],
            ],
        ]);
        $attachment = [];
        foreach ($files as $file) {
            $s3Upload     = $s3->putObject([
                'Bucket'      => $_ENV['S3_BUCKET'],
                'ContentType' => $file->getMimeType(),
                'Tagging'     => 'category=message_2022',
                'Key'         => sha1($file->getContent()) . '.' . $file->getClientOriginalExtension(),
                'Body'        => $file->getContent(),
                'ACL'         => 'public-read',
            ]);
            $attachment[] = [
                'url'  => $s3Upload['ObjectURL'],
                'type' => explode((string)$file->getMimeType(), '/')[0]
            ];
        }
        $update = $graphQLClient->requestObserverBot(/** @lang GraphQL */ <<<'GraphQL'
mutation($id: Int!, $attachments: jsonb) {
    update_elections_observation_messages_by_pk(pk_columns: {id: $id}, _append: {attachments: $attachments}) {
        id
    }
}
GraphQL
            ,
            [
                'id'          => $response['data']['message']['id'],
                'attachments' => $attachment
            ]
        );
        return new JsonResponse(['uploaded' => $response]);
    }
}
