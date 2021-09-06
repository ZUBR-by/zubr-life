<?php

namespace App;

use Aws\S3\S3Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UploadFiles extends Command
{
    private string $s3Key;
    private string $s3Bucket;
    private string $s3Secret;

    public function __construct(string $s3Key, string $s3Secret, string $s3Bucket)
    {
        $this->s3Key    = $s3Key;
        $this->s3Bucket = $s3Bucket;
        $this->s3Secret = $s3Secret;

        parent::__construct('upload:media');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $s3  = new S3Client([
            'region'      => 'eu-north-1',
            'version'     => 'latest',
            'credentials' => [
                'key'    => $this->s3Key,
                'secret' => $this->s3Secret,
            ],
        ]);
        $map = [
            'a' => 'ad',
            'p' => 'person',
            'l' => 'place',
            'e' => 'event',
        ];
        foreach (scandir(__DIR__ . '/../var/media') as $filename) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }
            $path = __DIR__ . '/../var/media/' . $filename;
            $type = substr($filename, 0, 1);
            $file = substr($filename, 1, mb_strlen($filename) - 1);
            [$idLong] = explode('.', $file);
            $parts    = explode('_', $idLong);
            $content  = file_get_contents($path);
            $key      = $map[$type]
                . $parts[0]
                . (isset($parts[1]) ? '_' . $parts[1] : '')
                . '_'
                . sha1($content);
            $mime     = mime_content_type($path);
            $response = $s3->putObject([
                'Bucket'      => $this->s3Bucket,
                'ContentType' => $mime,
                'Key'         => $key,
                'Body'        => $content,
                'ACL'         => 'public-read',
            ]);
            rename($path, __DIR__ . '/../var/media_finished/' . $filename);
        }


        return 0;
    }
}
