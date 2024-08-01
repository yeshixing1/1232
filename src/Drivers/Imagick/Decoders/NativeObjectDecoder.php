<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Intervention\Image\Modifiers\RemoveAnimationModifier;

class NativeObjectDecoder extends SpecializableDecoder implements SpecializedInterface
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_object($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!($input instanceof Imagick)) {
            throw new DecoderException('Unable to decode input');
        }

        // get original indexed palette status for origin before any operation
        $indexed = $this->hasIndexedColors($input);

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        if ($input->getImageFormat() != 'JPEG') {
            $input = $input->coalesceImages();
        }

        // create image object
        $image = new Image(
            $this->driver(),
            new Core($input)
        );

        // discard animation depending on config
        if (!$this->driver()->config()->decodeAnimation) {
            $image->modify(new RemoveAnimationModifier());
        }

        // adjust image rotatation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        // set values on origin
        $image->origin()->setMediaType($input->getImageMimeType());
        $image->origin()->setIndexed($indexed);

        return $image;
    }

    /**
     * Determine if given imagick instance is a indexed palette color image
     *
     * @param Imagick $imagick
     * @return bool
     */
    private function hasIndexedColors(Imagick $imagick): bool
    {
        // Palette PNG files with alpha channel result incorrectly in "truecolor with alpha"
        // in imagemagick 6 making it impossible to rely on Imagick::getImageType().
        // This workaround looks at the the PNG data directly to decode the color type byte.

        // detect imagick major version
        $imagickVersion = dechex(Imagick::getVersion()['versionNumber']);
        $imagickVersion = substr($imagickVersion, 0, 1);

        // detect palette status manually in imagemagick 6
        if (version_compare($imagickVersion, '6', '<=') && $imagick->getImageFormat() === 'PNG') {
            $data = $imagick->getImageBlob();
            $pos = strpos($data, 'IHDR');
            $type = substr($data, $pos + 13, 1);
            $type = unpack('C', $type)[1];

            return $type === 3; // color type 3 is a PNG with indexed palette
        }

        // non-workaround version
        return in_array(
            $imagick->getImageType(),
            [Imagick::IMGTYPE_PALETTE, Imagick::IMGTYPE_PALETTEMATTE],
        );
    }
}
