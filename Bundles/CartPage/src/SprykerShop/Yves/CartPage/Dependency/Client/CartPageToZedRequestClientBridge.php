<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Client;

class CartPageToZedRequestClientBridge implements CartPageToZedRequestClientInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct($zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest()
    {
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseErrorMessages()
    {
        return $this->zedRequestClient->getLastResponseErrorMessages();
    }

    /**
     * @return void
     */
    public function addResponseMessagesToMessenger(): void
    {
        $this->zedRequestClient->addResponseMessagesToMessenger();
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesErrorMessages(): array
    {
        return $this->zedRequestClient->getResponsesErrorMessages();
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseSuccessMessages(): array
    {
        return $this->zedRequestClient->getLastResponseSuccessMessages();
    }

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseInfoMessages(): array
    {
        return $this->zedRequestClient->getLastResponseInfoMessages();
    }
}
