<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ProductOptionWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('productOptionGroups', $this->getProductOptionGroups($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductOptionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductOptionWidget/views/option-configurator/option-configurator.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]
     */
    protected function getProductOptionGroups(ProductViewTransfer $productViewTransfer)
    {
        $productAbstractOptionStorageTransfer = $this->getStorageProductOptionGroupCollectionTransfer($productViewTransfer);
        if (!$productAbstractOptionStorageTransfer) {
            return new ArrayObject();
        }

        return $this->getStorageProductOptionGroupCollectionTransfer($productViewTransfer)->getProductOptionGroups();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    protected function getStorageProductOptionGroupCollectionTransfer(ProductViewTransfer $productViewTransfer)
    {
        return $this
            ->getFactory()
            ->getProductOptionStorageClient()
            ->getProductOptionsForCurrentStore($productViewTransfer->getIdProductAbstract());
    }
}
