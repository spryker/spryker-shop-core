<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

class CompanyPageToMessengerClientBridge implements CompanyPageToMessengerClientInterface
{
    /**
     * @var \Spryker\Client\Messenger\MessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \Spryker\Client\Messenger\MessengerClientInterface $messengerClient
     */
    public function __construct($messengerClient)
    {
        $this->messengerClient = $messengerClient;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message): void
    {
        $this->messengerClient->addErrorMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message): void
    {
        $this->messengerClient->addSuccessMessage($message);
    }
}
