<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetWidget\Plugin\ProductSetDetailPage;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\ProductSetWidget\ProductSetWidgetPluginInterface;
use SprykerShop\Yves\ProductSetWidget\Widget\ProductSetDetailPageWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductSetWidget\Widget\ProductSetDetailPageWidget instead.
 */
class ProductSetWidgetPlugin extends AbstractWidgetPlugin implements ProductSetWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return void
     */
    public function initialize(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers): void
    {
        $widget = new ProductSetDetailPageWidget($productSetDataStorageTransfer, $productViewTransfers);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductSetDetailPageWidget::getTemplate();
    }
}
