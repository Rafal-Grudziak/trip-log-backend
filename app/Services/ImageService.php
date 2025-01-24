<?php

namespace App\Services;

use App\Dtos\ImageStoreDto;
use App\Models\Image;

class ImageService extends ModelService
{

    protected function getModelClass(): string
    {
        return Image::class;
    }


    public function store(ImageStoreDto $dto): Image
    {
        $image = $this->getModel()->newInstance();
        $image->setAttribute('imageable_type', $dto->imageable_type);
        $image->setAttribute('imageable_id', $dto->imageable_id);
        if ($dto->image) {
            $path = $dto->image->store('images');
            $image->setAttribute('path', $path);
        }
        $image->save();

        return $image;
    }


}
