<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProfilePage\Dependency\Client;

use Generated\Shared\Transfer\MerchantProfileTransfer;

class MerchantProfilePageToMerchantProfileStorageClientBridge implements MerchantProfilePageToMerchantProfileStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface
     */
    protected $merchantProfileStorageClient;

    /**
     * @param \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface $merchantStorageClient
     */
    public function __construct($merchantStorageClient)
    {
        $this->merchantProfileStorageClient = $merchantStorageClient;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function mapMerchantProfileStorageData(array $data): MerchantProfileTransfer
    {
        return $this->merchantProfileStorageClient->mapMerchantProfileStorageData($data);
    }
}
