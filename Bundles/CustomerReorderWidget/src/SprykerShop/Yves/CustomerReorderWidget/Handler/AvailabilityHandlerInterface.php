<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;

/**
 * @todo discuss
 * this is hard dependency on availability
 */
interface AvailabilityHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $locale
     *
     * @return bool
     */
    public function getAvailabilityForItemTransfer(ItemTransfer $itemTransfer, string $locale): bool;
}
