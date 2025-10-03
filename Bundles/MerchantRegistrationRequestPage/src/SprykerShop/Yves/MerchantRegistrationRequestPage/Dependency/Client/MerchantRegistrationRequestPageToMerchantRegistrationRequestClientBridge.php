<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;

class MerchantRegistrationRequestPageToMerchantRegistrationRequestClientBridge implements MerchantRegistrationRequestPageToMerchantRegistrationRequestClientInterface
{
    /**
     * @var \Spryker\Client\MerchantRegistrationRequest\MerchantRegistrationRequestClientInterface
     */
    protected $merchantRegistrationRequestClient;

    /**
     * @param \Spryker\Client\MerchantRegistrationRequest\MerchantRegistrationRequestClientInterface $merchantRegistrationRequestClient
     */
    public function __construct($merchantRegistrationRequestClient)
    {
        $this->merchantRegistrationRequestClient = $merchantRegistrationRequestClient;
    }

    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        return $this->merchantRegistrationRequestClient->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }
}
