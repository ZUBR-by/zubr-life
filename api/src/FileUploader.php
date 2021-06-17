<?php

namespace App;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploader
{
    public function uploadFile(UploadedFile $file) : string;
}
