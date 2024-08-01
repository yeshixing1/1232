<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Traits\CanInspectPng;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\PngEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\PngEncoder::class)]
final class PngEncoderTest extends ImagickTestCase
{
    use CanInspectPng;

    public function testEncode(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', (string) $result);
        $this->assertFalse($this->isInterlacedPng((string) $result));
    }

    public function testEncodeInterlaced(): void
    {
        $image = $this->createTestImage(3, 2);
        $encoder = new PngEncoder(interlaced: true);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/png', (string) $result);
        $this->assertTrue($this->isInterlacedPng((string) $result));
    }

    #[DataProvider('indexedDataProvider')]
    public function testEncoderIndexed(ImageInterface $image, PngEncoder $encoder, string $result): void
    {
        $this->assertEquals(
            $result,
            $this->pngColorType((string) $encoder->encode($image)),
        );
    }

    public static function indexedDataProvider(): array
    {
        return [
            [
                static::createTestImage(3, 2), // truecolor-alpha
                new PngEncoder(),
                'truecolor-alpha',
            ],
            [
                static::createTestImage(3, 2), // truecolor-alpha
                new PngEncoder(indexed: true),
                'indexed',
            ],
            [
                static::createTestImage(3, 2), // truecolor-alpha
                new PngEncoder(indexed: false),
                'truecolor-alpha',
            ],
            [
                static::createTestImageTransparent(3, 2), // truecolor-alpha
                new PngEncoder(),
                'truecolor-alpha',
            ],
            [
                static::createTestImageTransparent(3, 2), // truecolor-alpha
                new PngEncoder(indexed: true),
                'indexed',
            ],
            [
                static::createTestImageTransparent(3, 2), // truecolor-alpha
                new PngEncoder(indexed: false),
                'truecolor-alpha',
            ],
            [
                static::createTestImageTransparent(3, 2)->fill('fff'), // truecolor-alpha
                new PngEncoder(),
                'truecolor-alpha',
            ],
            [
                static::createTestImageTransparent(3, 2)->fill('fff'), // truecolor-alpha
                new PngEncoder(indexed: true),
                'indexed',
            ],
            [
                static::createTestImageTransparent(3, 2)->fill('fff'), // truecolor-alpha
                new PngEncoder(indexed: false),
                'truecolor-alpha',
            ],
            [
                static::readTestImage('tile.png'), // indexed
                new PngEncoder(),
                'indexed',
            ],
            [
                static::readTestImage('tile.png'), // indexed
                new PngEncoder(indexed: true),
                'indexed',
            ],
            [
                static::readTestImage('tile.png'), // indexed
                new PngEncoder(indexed: false),
                'truecolor-alpha',
            ],
            [
                static::readTestImage('test.jpg'), // foreign format
                new PngEncoder(),
                'truecolor-alpha',
            ],
            [
                static::readTestImage('test.jpg'), // foreign format
                new PngEncoder(indexed: true),
                'indexed',
            ],
            [
                static::readTestImage('test.jpg'), // foreign format
                new PngEncoder(indexed: false),
                'truecolor-alpha',
            ]
        ];
    }
}
