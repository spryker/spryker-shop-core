<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

interface QuickOrderPageToMessengerClientInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message): void;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message): void;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message): void;
}
