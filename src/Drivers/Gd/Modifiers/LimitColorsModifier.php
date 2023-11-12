<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class LimitColorsModifier implements ModifierInterface
{
    public function __construct(protected int $limit = 0, protected int $threshold = 256)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        // no color limit: no reduction
        if ($this->limit === 0) {
            return $image;
        }

        // limit is over threshold: no reduction
        if ($this->limit > $this->threshold) {
            return $image;
        }

        $width = $image->width();
        $height = $image->height();

        foreach ($image as $frame) {
            // create empty gd
            $reduced = imagecreatetruecolor($width, $height);

            // create matte
            $matte = imagecolorallocatealpha($reduced, 255, 255, 255, 127);

            // fill with matte
            imagefill($reduced, 0, 0, $matte);

            imagealphablending($reduced, false);

            // set transparency and get transparency index
            imagecolortransparent($reduced, $matte);

            // copy original image
            imagecopy($reduced, $frame->core(), 0, 0, 0, 0, $width, $height);

            // reduce limit by one to include possible transparency in palette
            $limit = imagecolortransparent($frame->core()) === -1 ? $this->limit : $this->limit - 1;

            // decrease colors
            imagetruecolortopalette($reduced, true, $limit);

            $frame->setCore($reduced);
        }


        return $image;
    }
}