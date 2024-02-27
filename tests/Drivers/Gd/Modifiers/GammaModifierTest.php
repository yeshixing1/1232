<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\GammaModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Modifiers\GammaModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\GammaModifier::class)]
final class GammaModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModifier(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $image->modify(new GammaModifier(2.1));
        $this->assertEquals('00d5f8', $image->pickColor(0, 0)->toHex());
    }
}
