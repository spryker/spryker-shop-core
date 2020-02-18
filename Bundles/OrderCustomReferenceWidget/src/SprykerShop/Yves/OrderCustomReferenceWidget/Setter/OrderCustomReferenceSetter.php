<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Setter;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Validator\OrderCustomReferenceValidatorInterface;

class OrderCustomReferenceSetter implements OrderCustomReferenceSetterInterface
{
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

    /**
     * @var \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface
     */
    protected $orderCustomReferenceClient;

    /**
     * @var \SprykerShop\Yves\OrderCustomReferenceWidget\Validator\OrderCustomReferenceValidatorInterface
     */
    protected $orderCustomReferenceValidator;

    /**
     * @param \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface $orderCustomReferenceClient
     * @param \SprykerShop\Yves\OrderCustomReferenceWidget\Validator\OrderCustomReferenceValidatorInterface $orderCustomReferenceValidator
     */
    public function __construct(
        OrderCustomReferenceWidgetToQuoteClientInterface $quoteClient,
        OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface $orderCustomReferenceClient,
        OrderCustomReferenceValidatorInterface $orderCustomReferenceValidator
    ) {
        $this->quoteClient = $quoteClient;
        $this->orderCustomReferenceClient = $orderCustomReferenceClient;
        $this->orderCustomReferenceValidator = $orderCustomReferenceValidator;
    }

    /**
     * @param string $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(string $orderCustomReference): QuoteResponseTransfer
    {
        $isOrderCustomReferenceLengthValid = $this->orderCustomReferenceValidator
            ->isOrderCustomReferenceLengthValid($orderCustomReference);

        if (!$isOrderCustomReferenceLengthValid) {
            return $this->createQuoteResponseTransferWithError(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH);
        }

        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteTransfer->setOrderCustomReference($orderCustomReference);

        $quoteResponseTransfer = $this->orderCustomReferenceClient->setOrderCustomReference($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransferWithError(string $message): QuoteResponseTransfer
    {
        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($message);

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->addError($quoteErrorTransfer);
    }
}
