<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(ImageManager::class)]
#[RequiresPhpExtension('imagick')]
final class ImageManagerTestImagick extends BaseTestCase
{
    public function testConstructor(): void
    {
        $manager = new ImageManager(new Driver());
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = new ImageManager(Driver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testWithDriver(): void
    {
        $manager = ImageManager::withDriver(new Driver());
        $this->assertInstanceOf(ImageManager::class, $manager);

        $manager = ImageManager::withDriver(Driver::class);
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testDriver(): void
    {
        $driver = new Driver();
        $manager = ImageManager::withDriver($driver);
        $this->assertEquals($driver, $manager->driver());
    }

    public function testDriverStatic(): void
    {
        $manager = ImageManager::imagick();
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testCreateImagick(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testAnimateImagick(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->animate(function ($animation) {
            $animation->add($this->getTestResourcePath('red.gif'), .25);
        });
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagick(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'));
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagickWithDecoderClassname(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), FilePathImageDecoder::class);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagickWithDecoderInstance(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), new FilePathImageDecoder());
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagickWithDecoderClassnameArray(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [FilePathImageDecoder::class]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagickWithDecoderInstanceArray(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [new FilePathImageDecoder()]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagickWithDecoderInstanceArrayMultiple(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('red.gif'), [
            new BinaryImageDecoder(),
            new FilePathImageDecoder(),
        ]);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testReadImagickWithRotationAdjustment(): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($this->getTestResourcePath('orientation.jpg'));
        $this->assertColor(1, 0, 254, 255, $image->pickColor(3, 3));
    }

    public function testReadImagickWithoutRotationAdjustment(): void
    {
        $manager = new ImageManager(Driver::class, autoOrientation: false);
        $image = $manager->read($this->getTestResourcePath('orientation.jpg'));
        $this->assertColor(250, 2, 3, 255, $image->pickColor(3, 3));
    }
}