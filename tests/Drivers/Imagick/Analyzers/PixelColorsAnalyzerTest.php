<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Analyzers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Analyzers\PixelColorsAnalyzer;
use Intervention\Image\Collection;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Analyzers\PixelColorsAnalyzer::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Analyzers\PixelColorsAnalyzer::class)]
final class PixelColorsAnalyzerTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testAnalyze(): void
    {
        $image = $this->readTestImage('tile.png');
        $analyzer = new PixelColorsAnalyzer(0, 0);
        $result = $analyzer->analyze($image);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(ColorInterface::class, $result->first());
        $this->assertEquals('b4e000', $result->first()->toHex());
    }
}
