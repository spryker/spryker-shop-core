<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetWidget\Plugin\ProductSetDetailPage;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\ProductSetWidget\ProductSetWidgetPluginInterface;

class ProductSetWidgetPlugin extends AbstractWidgetPlugin implements ProductSetWidgetPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     * @param \Generated\Shared\Transfer\StorageProductTransfer[] $storageProductTransfers
     *
     * @return void
     */
    public function initialize(ProductSetStorageTransfer $productSetStorageTransfer, array $storageProductTransfers): void
    {
        $this
            ->addParameter('productSet', $productSetStorageTransfer)
            ->addParameter('storageProducts', $storageProductTransfers);
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
        return '@ProductSetWidget/_product-set-detail-page/product-set-widget.twig';
    }

}
