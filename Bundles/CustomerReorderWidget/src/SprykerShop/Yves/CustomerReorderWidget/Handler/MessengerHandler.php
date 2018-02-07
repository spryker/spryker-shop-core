<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;

class MessengerHandler implements MessengerHandlerInterface
{
    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    private $client;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface $client
     */
    public function __construct(
        FlashMessengerInterface $flashMessenger,
        CustomerReorderWidgetToCartClientInterface $client
    ) {
        $this->flashMessenger = $flashMessenger;
        $this->client = $client;
    }

    /**
     * In all meanings dirty jack copied from CartPage
     *
     * @TODO discuss with @Nyulas
     *
     * @return void
     */
    public function setFlashMessagesFromLastZedRequest(): void
    {
        foreach ($this->client->getZedStub()->getErrorMessages() as $errorMessage) {
            $this->flashMessenger->addErrorMessage($errorMessage->getValue());
        }

        foreach ($this->client->getZedStub()->getSuccessMessages() as $successMessage) {
            $this->flashMessenger->addSuccessMessage($successMessage->getValue());
        }

        foreach ($this->client->getZedStub()->getInfoMessages() as $infoMessage) {
            $this->flashMessenger->addInfoMessage($infoMessage->getValue());
        }
    }
}
