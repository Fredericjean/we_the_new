<?php

namespace App\Twig;

use App\Entity\Product\Product;
use App\Entity\Product\ProductImage;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class ProductImageExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('main_image', [$this, 'getMainImage'])
        ];
    }

    public function getMainImage(Product $product): ?ProductImage
    {
        $mainImage = array_filter($product->getImages()->toArray(), function (ProductImage $productImage) {
            if ($productImage->getImageType() === ProductImage::IMAGE_TYPE_MAIN) {
                return $productImage;
            }
        });

        return array_shift($mainImage);
    }
}
