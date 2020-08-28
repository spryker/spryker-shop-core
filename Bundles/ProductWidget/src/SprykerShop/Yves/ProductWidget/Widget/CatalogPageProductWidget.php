<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductWidget\ProductWidgetFactory getFactory()
 */
class CatalogPageProductWidget extends AbstractWidget
{
    /**
     * @param mixed[] $product
     * @param string|null $viewMode
     */
    public function __construct(array $product, $viewMode = null)
    {
        $this->addParameter('product', $product)
            ->addParameter('viewMode', $viewMode);

        /** @deprecated Use global widgets instead. */
        $this->addWidgets($this->getFactory()->getCatalogPageSubWidgets());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CatalogPageProductWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductWidget/views/catalog-product/catalog-product.twig';
    }
}
