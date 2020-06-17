<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Dependency\Client;

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
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function findOne(string $sku): array
    {
        return 'MER000001';
        return $this->merchantProductStorageClient->findOne($sku);
    }
}
