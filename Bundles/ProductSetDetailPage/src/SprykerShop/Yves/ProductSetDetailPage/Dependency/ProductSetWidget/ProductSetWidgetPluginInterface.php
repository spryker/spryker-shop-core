<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Dependency\ProductSetWidget;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductSetWidgetPluginInterface extends WidgetPluginInterface
{

    public const NAME = 'ProductSetWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     * @param \Generated\Shared\Transfer\StorageProductTransfer[] $storageProductTransfers
     *
     * @return void
     */
    public function initialize(ProductSetStorageTransfer $productSetStorageTransfer, array $storageProductTransfers): void;

}
