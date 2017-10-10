<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductCategoryWidget\ProductCategoryWidgetPluginInterface;

class ProductCategoryWidgetPlugin extends AbstractWidgetPlugin implements ProductCategoryWidgetPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return void
     */
    public function initialize(StorageProductTransfer $storageProductTransfer): void
    {
        $this->addParameter('product', $storageProductTransfer);
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
        return '@ProductCategoryWidget/_product-detail-page/breadcrumb.twig';
    }

}
