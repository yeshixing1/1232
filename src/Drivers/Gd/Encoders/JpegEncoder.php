<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Encoders\JpegEncoder as GenericJpegEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\CanBufferOutput;
use Intervention\Image\Traits\IsDriverSpecialized;

class JpegEncoder extends GenericJpegEncoder implements SpecializedInterface
{
    use CanBufferOutput;
    use IsDriverSpecialized;

    public function encode(ImageInterface $image): EncodedImage
    {
        $output = Cloner::cloneBlended($image->core()->native(), background: $image->blendingColor());

        $data = $this->getBuffered(function () use ($output) {
            imagejpeg($output, null, $this->quality);
        });

        return new EncodedImage($data, 'image/jpeg');
    }
}
