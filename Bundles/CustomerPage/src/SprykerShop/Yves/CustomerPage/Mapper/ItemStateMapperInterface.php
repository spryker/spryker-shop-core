<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Mapper;

use ArrayObject;

interface ItemStateMapperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<array<array<string>>>
     */
    public function aggregateItemStatesDisplayNamesByOrderReference(ArrayObject $orderTransfers): array;
}
