<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;

interface MerchantRegistrationRequestPageToMerchantRegistrationRequestClientInterface
{
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer;
}
