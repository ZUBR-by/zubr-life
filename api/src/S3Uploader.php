<?php

namespace App;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3Uploader implements FileUploader
{
    private S3Client $client;
    private string $s3Bucket;

    public function __construct(string $s3Key, string $s3Secret, string $s3Bucket)
    {
        $this->client   = new S3Client([
            'region'      => 'eu-north-1',
            'version'     => 'latest',
            'credentials' => [
                'key'    => $s3Key,
                'secret' => $s3Secret,
            ],
        ]);
        $this->s3Bucket = $s3Bucket;
    }

    public function uploadFile(UploadedFile $file) : string
    {
        $response = $this->client->putObject([
            'Bucket'      => $this->s3Bucket,
            'ContentType' => $file->getClientMimeType(),
            'Key'         => sha1($file->getContent()),
            'Body'        => $file->getContent(),
            'ACL'         => 'public-read',
        ]);

        return (string) $response['ObjectURL'];
    }
}
