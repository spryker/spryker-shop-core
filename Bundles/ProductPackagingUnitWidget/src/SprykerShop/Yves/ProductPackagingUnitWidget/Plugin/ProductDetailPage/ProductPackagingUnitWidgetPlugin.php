<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductPackagingUnitWidget\ProductPackagingUnitWidgetPluginInterface;
use SprykerShop\Yves\ProductPackagingUnitWidget\Widget\ProductPackagingUnitWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductPackagingUnitWidget\Widget\ProductPackagingUnitWidget instead.
 *
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class ProductPackagingUnitWidgetPlugin extends AbstractWidgetPlugin implements ProductPackagingUnitWidgetPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return ProductPackagingUnitWidget::getTemplate();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array $quantityOptions Contains the selectable quantity options; each option is structured as ['label' => 1, 'value' => 1]
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled, array $quantityOptions = []): void
    {
        $widget = new ProductPackagingUnitWidget($productViewTransfer, $isAddToCartDisabled, $quantityOptions);

        $this->parameters = $widget->getParameters();
    }
}
