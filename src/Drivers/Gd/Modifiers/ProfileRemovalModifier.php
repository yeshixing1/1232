<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ProfileRemovalModifier as GenericProfileRemovalModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

class ProfileRemovalModifier extends GenericProfileRemovalModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        // Color profiles are not supported by GD, so the decoded
        // image is already free of profiles anyway.
        return $image;
    }
}
