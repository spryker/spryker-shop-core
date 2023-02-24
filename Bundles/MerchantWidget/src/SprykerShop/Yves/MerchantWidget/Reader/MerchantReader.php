<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantWidget\Reader;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use SprykerShop\Yves\MerchantWidget\Dependency\Client\MerchantWidgetToMerchantStorageClientInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantWidget\Dependency\Client\MerchantWidgetToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @param \SprykerShop\Yves\MerchantWidget\Dependency\Client\MerchantWidgetToMerchantStorageClientInterface $merchantStorageClient
     */
    public function __construct(MerchantWidgetToMerchantStorageClientInterface $merchantStorageClient)
    {
        $this->merchantStorageClient = $merchantStorageClient;
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOneByMerchantReference(string $merchantReference): ?MerchantStorageTransfer
    {
        return $this->merchantStorageClient
            ->findOne(
                (new MerchantStorageCriteriaTransfer())
                    ->addMerchantReference($merchantReference),
            );
    }
}
