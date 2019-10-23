<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface;

class MerchantProductOfferWidgetToMerchantProductOfferStorageClientBridge implements MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
{
    /**
     * @var Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface
     */
    protected $merchantProfileStorageClient;

    /**
     * @param \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageClientInterface $merchantProfileStorageClient
     */
    public function __construct(MerchantProfileStorageClientInterface $merchantProfileStorageClient)
    {
        $this->merchantProfileStorageClient = $merchantProfileStorageClient;
    }
 }
