<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDiscontinuedWidget\Plugin\ShoppingListPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDiscontinuedWidget\Widget\ProductDiscontinuedWidget;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductDiscontinuedWidget\ProductDiscontinuedWidgetPluginInterface;

/**
 * @depricated Use \SprykerShop\Yves\ProductDiscontinuedWidget\Widget\ProductDiscontinuedWidget instead.
 *
 * @method \SprykerShop\Yves\ProductDiscontinuedWidget\ProductDiscontinuedWidgetFactory getFactory()
 */
class ProductDiscontinuedWidgetPlugin extends AbstractWidgetPlugin implements ProductDiscontinuedWidgetPluginInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function initialize(string $sku): void
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
        return static::NAME;
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
        return ProductDiscontinuedWidget::getTemplate();
    }
}
