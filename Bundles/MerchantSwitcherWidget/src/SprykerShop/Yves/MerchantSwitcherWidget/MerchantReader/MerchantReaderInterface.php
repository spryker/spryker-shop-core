<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use ArrayObject;

interface MerchantReaderInterface
{
    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantTransfer[]
     */
    public function getActiveMerchants(): ArrayObject;

    /**
     * @return string
     */
    public function getSelectedMerchantReference(): string;
}
