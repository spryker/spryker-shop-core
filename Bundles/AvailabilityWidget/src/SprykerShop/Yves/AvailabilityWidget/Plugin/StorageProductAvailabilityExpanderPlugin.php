<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AvailabilityWidget\AvailabilityWidgetFactory getFactory()
 */
class StorageProductAvailabilityExpanderPlugin extends AbstractPlugin implements StorageProductExpanderPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function expandStorageProduct(StorageProductTransfer $storageProductTransfer, array $productData, Request $request)
    {
        $storageAvailabilityTransfer = $this->getFactory()
            ->getAvailabilityClient()
            ->getProductAvailabilityByIdProductAbstract($storageProductTransfer->getIdProductAbstract());

        if (!$storageProductTransfer->getIsVariant()) {
            $storageProductTransfer->setAvailable($storageAvailabilityTransfer->getIsAbstractProductAvailable());

            return $storageProductTransfer;
        }

        $concreteProductAvailableItems = $storageAvailabilityTransfer->getConcreteProductAvailableItems();
        if (isset($concreteProductAvailableItems[$storageProductTransfer->getSku()])) {
            $storageProductTransfer->setAvailable($concreteProductAvailableItems[$storageProductTransfer->getSku()]);
        }

        return $storageProductTransfer;
    }

}
