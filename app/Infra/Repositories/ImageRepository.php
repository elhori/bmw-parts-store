<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\IImageRepository;
use App\Domain\Contract\Imageable;
use Illuminate\Http\UploadedFile;

class ImageRepository implements IImageRepository
{
    public function uploadImage(UploadedFile $file, Imageable $imageable): string
    {
        $directory = resource_path('img');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filePath = 'img/' . time() . '_' . $file->getClientOriginalName();
        $file->move($directory, basename($filePath));

        $eloquentModel = $this->resolveEloquentModel($imageable);

        if ($eloquentModel->image) {
            unlink(resource_path($eloquentModel->image->file_path));
            $eloquentModel->image()->delete();
        }

        $eloquentModel->image()->create([
            'file_path' => $filePath,
            'imageable_id' => $imageable->getId(),
            'imageable_type' => $imageable->getImageableType(),
        ]);

        return $filePath;
    }

    private function resolveEloquentModel(Imageable $imageable)
    {
        $modelClass = $imageable->getImageableType();
        return $modelClass::find($imageable->getId());
    }
}
