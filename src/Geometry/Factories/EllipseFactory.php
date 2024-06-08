<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class EllipseFactory implements DrawableFactoryInterface
{
    /**
     * Finished product of factory
     */
    protected Ellipse $ellipse;

    /**
     * Create new factory instance
     *
     * @param Point $pivot
     * @param null|callable|Ellipse $init
     * @return void
     */
    final public function __construct(
        protected Point $pivot = new Point(0, 0),
        null|callable|Ellipse $init = null,
    ) {
        $this->ellipse = is_a($init, Ellipse::class) ? $init : new Ellipse(0, 0, $pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public function create(): DrawableInterface
    {
        return $this();
    }

    /**
     * Set position of ellipse
     *
     * @param int $x
     * @param int $y
     * @return EllipseFactory
     */
    public function position(int $x, int $y): self
    {
        $this->ellipse->setPivot(new Point($x, $y));

        return $this;
    }

    /**
     * Set the size of the ellipse to be produced
     *
     * @param int $width
     * @param int $height
     * @return EllipseFactory
     */
    public function size(int $width, int $height): self
    {
        $this->ellipse->setSize($width, $height);

        return $this;
    }

    /**
     * Set the width of the ellipse to be produced
     *
     * @param int $width
     * @return EllipseFactory
     */
    public function width(int $width): self
    {
        $this->ellipse->setWidth($width);

        return $this;
    }

    /**
     * Set the height of the ellipse to be produced
     *
     * @param int $height
     * @return EllipseFactory
     */
    public function height(int $height): self
    {
        $this->ellipse->setHeight($height);

        return $this;
    }

    /**
     * Set the background color of the ellipse to be produced
     *
     * @param mixed $color
     * @return EllipseFactory
     */
    public function background(mixed $color): self
    {
        $this->ellipse->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the ellipse to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return EllipseFactory
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->ellipse->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the ellipse
     *
     * @return Ellipse
     */
    public function __invoke(): Ellipse
    {
        return $this->ellipse;
    }
}
