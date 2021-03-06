<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDiscontinuedWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductDiscontinuedWidget\ProductDiscontinuedWidgetFactory getFactory()
 */
class ProductDiscontinuedWidget extends AbstractWidget
{
    /**
     * @param string $sku
     */
    public function __construct(string $sku)
    {
        $discontinuedProduct = $this->getFactory()
            ->getProductDiscontinuedStorageClient()
            ->findProductDiscontinuedStorage($sku, $this->getLocale());

        $this->addParameter('discontinuedProduct', $discontinuedProduct);
        $this->addParameter('isDiscontinued', (bool)$discontinuedProduct);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductDiscontinuedWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductDiscontinuedWidget/views/shopping-list-product-discontinued/shopping-list-product-discontinued.twig';
    }
}
