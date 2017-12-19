<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Dependency\Client;

class PriceWidgetToPriceStorageClientBridge implements PriceWidgetToPriceStorageClientInterface
{
    /**
     * @var \Spryker\Client\PriceStorage\PriceStorageClientInterface
     */
    protected $priceStorageClient;

    /**
     * @param \Spryker\Client\PriceStorage\PriceStorageClientInterface $priceStorageClient
     */
    public function __construct($priceStorageClient)
    {
        $this->priceStorageClient = $priceStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceAbstractStorageTransfer($idProductAbstract)
    {
        return $this->priceStorageClient->findPriceAbstractStorageTransfer($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceConcreteStorageTransfer($idProductConcrete)
    {
        return $this->priceStorageClient->findPriceConcreteStorageTransfer($idProductConcrete);
    }
}
