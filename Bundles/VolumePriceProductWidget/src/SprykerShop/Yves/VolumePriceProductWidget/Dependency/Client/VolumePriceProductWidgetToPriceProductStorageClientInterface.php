<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

interface VolumePriceProductWidgetToPriceProductStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceAbstractStorageTransfer(int $idProductAbstract): ?PriceProductStorageTransfer;

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceConcreteStorageTransfer(int $idProductConcrete): ?PriceProductStorageTransfer;
}
