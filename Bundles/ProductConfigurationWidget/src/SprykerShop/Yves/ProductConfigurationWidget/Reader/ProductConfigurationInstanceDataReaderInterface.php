<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationInstanceDataReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return array
     */
    public function getProductConfigurationInstanceTemplateData(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): array;
}
