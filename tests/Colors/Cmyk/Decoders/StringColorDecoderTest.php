<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Cmyk\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\TestCase;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder::class)]
final class StringColorDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode('cmyk(0,0,0,0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 0, 0, 0], $result->toArray());

        $result = $decoder->decode('cmyk(0, 100, 100, 0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100, 0], $result->toArray());

        $result = $decoder->decode('cmyk(0, 100, 100, 0)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100, 0], $result->toArray());

        $result = $decoder->decode('cmyk(0%, 100%, 100%, 0%)');
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals([0, 100, 100, 0], $result->toArray());
    }

    public function testDecodeInvalid(): void
    {
        $decoder = new StringColorDecoder();
        $this->expectException(DecoderException::class);
        $decoder->decode(null);
    }
}
