<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Mapper;

use Generated\Shared\Transfer\ItemTransfer;

interface ProductAvailabilityMapperInterface
{
    /**
     * @param array<string, mixed> $attributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function mapProductAvailabilityAttributes(array $attributes, ItemTransfer $itemTransfer): array;
}
