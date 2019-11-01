<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;

class MerchantProductOfferWidgetToMerchantProfileStorageClientBridge implements MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface
     */
    protected $merchantProfileStorageClient;

    /**
     * @param \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface $merchantProfileStorageClient
     */
    public function __construct($merchantProfileStorageClient)
    {
        $this->merchantProfileStorageClient = $merchantProfileStorageClient;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer|null
     */
    public function findMerchantProfileStorageData(int $idMerchant): ?MerchantProfileStorageTransfer
    {
        return $this->merchantProfileStorageClient->findMerchantProfileStorageData($idMerchant);
    }
}
