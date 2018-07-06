<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

class VolumePriceProductWidgetToPriceProductStorageClientBridge implements VolumePriceProductWidgetToPriceProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @param \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface $priceProductStorageClient
     */
    public function __construct($priceProductStorageClient)
    {
        $this->priceProductStorageClient = $priceProductStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceAbstractStorageTransfer(int $idProductAbstract): ?PriceProductStorageTransfer
    {
        return $this->priceProductStorageClient->findPriceAbstractStorageTransfer($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceConcreteStorageTransfer(int $idProductConcrete): ?PriceProductStorageTransfer
    {
        return $this->priceProductStorageClient->findPriceConcreteStorageTransfer($idProductConcrete);
    }
}
