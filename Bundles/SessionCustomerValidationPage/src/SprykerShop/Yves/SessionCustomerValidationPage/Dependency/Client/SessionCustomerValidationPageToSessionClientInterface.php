<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client;

interface SessionCustomerValidationPageToSessionClientInterface
{
    /**
     * @return string
     */
    public function getId(): string;
}
