<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductOptionWidget;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductOptionWidgetPluginInterface extends WidgetPluginInterface
{

    const NAME = 'ProductOptionWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return void
     */
    public function initialize(StorageProductTransfer $storageProductTransfer): void;

}
