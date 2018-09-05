<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

interface CustomerPageToCustomerAccessPermissionClientInterface
{
    /**
     * @param string $customerSecuredPattern
     *
     * @return string
     */
    public function getCustomerSecuredPatternAccordingCustomerAccess(string $customerSecuredPattern): string;
}