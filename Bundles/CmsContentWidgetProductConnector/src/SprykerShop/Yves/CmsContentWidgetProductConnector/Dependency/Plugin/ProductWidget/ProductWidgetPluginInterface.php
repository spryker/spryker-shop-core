<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector\Dependency\Plugin\ProductWidget;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductWidgetPluginInterface extends WidgetPluginInterface
{

    public const NAME = 'ProductWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return void
     */
    public function initialize(StorageProductTransfer $storageProductTransfer): void;

}
