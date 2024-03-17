<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\Traits\CanBeDriverSpecialized;

class SpecializableEncoder extends AbstractEncoder implements SpecializableInterface
{
    use CanBeDriverSpecialized;
}
