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
        $this->addParameter(
            'discontinuedProduct',
            $this->getFactory()
                ->getProductDiscontinuedStorageClient()
                ->findProductDiscontinuedStorage($sku, $this->getLocale())
        );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductDiscontinuedWidget';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductDiscontinuedWidget/views/shopping-list-product-discontinued/shopping-list-product-discontinued.twig';
    }
}
