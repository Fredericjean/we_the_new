<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class ProductImageExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('main_image')
        ];
    }

    public function getMainImage()
    {
        //TODO: Implement getMainImage() method.
    }
}
