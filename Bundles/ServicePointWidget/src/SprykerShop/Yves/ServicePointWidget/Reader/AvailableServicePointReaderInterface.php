<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Reader;

interface AvailableServicePointReaderInterface
{
    /**
     * @param list<string> $servicePointUuids
     * @param string $storeName
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    public function getServicePoints(array $servicePointUuids, string $storeName): array;
}
