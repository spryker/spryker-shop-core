<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Reader;

use ArrayObject;

interface QuoteReaderInterface
{
    /**
     * @param string $groupKey
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsByConfiguredBundleGroupKey(string $groupKey): ArrayObject;
}
