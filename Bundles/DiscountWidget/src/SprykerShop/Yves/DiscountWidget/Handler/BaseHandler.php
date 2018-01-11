<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Handler;

use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface;

class BaseHandler
{
    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(FlashMessengerInterface $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractClient $client
     *
     * @return void
     */
    public function setFlashMessagesFromLastZedRequest(DiscountWidgetToCalculationClientInterface $client)
    {
        foreach ($client->getZedStub()->getErrorMessages() as $errorMessage) {
            $this->flashMessenger->addErrorMessage($errorMessage->getValue());
        }

        foreach ($client->getZedStub()->getSuccessMessages() as $successMessage) {
            $this->flashMessenger->addSuccessMessage($successMessage->getValue());
        }

        foreach ($client->getZedStub()->getInfoMessages() as $infoMessage) {
            $this->flashMessenger->addInfoMessage($infoMessage->getValue());
        }
    }
}
