<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Expander;

use ArrayObject;

interface ItemExpanderInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $localeName
     *
     * @return \ArrayObject
     */
    public function expandItemTransfers(ArrayObject $itemTransfers, string $localeName): ArrayObject;
}
