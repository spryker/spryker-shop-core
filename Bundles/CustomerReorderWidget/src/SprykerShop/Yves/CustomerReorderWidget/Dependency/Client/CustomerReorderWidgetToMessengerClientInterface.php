<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

interface CustomerReorderWidgetToMessengerClientInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage(string $message): void;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage(string $message): void;
}
