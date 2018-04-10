<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

use Generated\Shared\Transfer\StorageAvailabilityTransfer;

interface CustomerReorderWidgetToAvailabilityStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer|null
     */
    public function getProductAvailabilityByIdProductAbstract(int $idProductAbstract): ?StorageAvailabilityTransfer;
}
