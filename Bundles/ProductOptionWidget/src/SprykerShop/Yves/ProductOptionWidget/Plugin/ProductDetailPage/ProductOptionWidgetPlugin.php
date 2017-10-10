<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductOptionWidget\ProductOptionWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ProductOptionWidgetPlugin extends AbstractWidgetPlugin implements ProductOptionWidgetPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return void
     */
    public function initialize(StorageProductTransfer $storageProductTransfer): void
    {
        $this
            ->addParameter(
                'productOptionGroups',
                $this->getProductOptionGroups($storageProductTransfer->getIdProductAbstract())
            );
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
        return '@ProductOptionWidget/_product-detail-page/product-options.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StorageProductOptionGroupTransfer[]
     */
    protected function getProductOptionGroups(int $idProductAbstract)
    {
        return $this->getStorageProductOptionGroupCollectionTransfer($idProductAbstract)->getProductOptionGroups();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    protected function getStorageProductOptionGroupCollectionTransfer(int $idProductAbstract)
    {
        return $this
            ->getFactory()
            ->getProductOptionClient()
            ->getProductOptions($idProductAbstract, $this->getLocale());
    }

}
