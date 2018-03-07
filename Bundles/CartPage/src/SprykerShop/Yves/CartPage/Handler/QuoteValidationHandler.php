<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;

class QuoteValidationHandler extends BaseHandler implements QuoteValidationHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(
        CartPageToCartClientInterface $cartClient,
        FlashMessengerInterface $flashMessenger
    ) {
        parent::__construct($flashMessenger);
        $this->cartClient = $cartClient;
    }

    /**
     * @return bool
     */
    public function validateQuote(): bool
    {
        $quoteValidationResult = $this->cartClient->validateQuote();
        $this->setFlashMessagesFromLastZedRequest($this->cartClient);

        return $quoteValidationResult->getIsSuccessful();
    }
}
