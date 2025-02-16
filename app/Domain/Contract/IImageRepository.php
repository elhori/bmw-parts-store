<?php

namespace App\Domain\Contract;

use Illuminate\Http\UploadedFile;

interface IImageRepository
{
    public function uploadImage(UploadedFile $file, Imageable $imageable): string;
}
