<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetWidget\Widget;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class CmsContentProductSetWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     */
    public function __construct(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers)
    {
        $this
            ->addParameter('productSet', $productSetDataStorageTransfer)
            ->addParameter('productViews', $productViewTransfers);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductSetWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductSetWidget/views/product-set-widget/product-set-widget.twig';
    }
}
