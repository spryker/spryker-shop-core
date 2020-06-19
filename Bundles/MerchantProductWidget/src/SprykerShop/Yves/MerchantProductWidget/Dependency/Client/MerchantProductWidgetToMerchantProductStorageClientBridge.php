<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;

class MerchantProductWidgetToMerchantProductStorageClientBridge implements MerchantProductWidgetToMerchantProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProductStorage\MerchantProductStorageClientInterface
     */
    protected $merchantProductStorageClient;

    /**
     * @param \Spryker\Client\MerchantProductStorage\MerchantProductStorageClientInterface $merchantProductStorageClient
     */
    public function __construct($merchantProductStorageClient)
    {
        $this->merchantProductStorageClient = $merchantProductStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer
     */
    public function findOne(int $idProductAbstract): MerchantProductStorageTransfer
    {
        return $this->merchantProductStorageClient->findOne($idProductAbstract);
    }
}
