<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Dependency\Plugin\ProductWidget;

use Generated\Shared\Transfer\StorageProductAbstractRelationTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductWidgetPluginInterface extends WidgetPluginInterface
{

    const NAME = 'ProductWidgetPlugin';

    /**
     * @param StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer
     *
     * @return void
     */
    public function initialize(StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer): void;

}
