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

class QuoteSetter implements QuoteSetterInterface
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
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuote(
        string $orderCustomReference,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $isOrderCustomReferenceLengthValid = $this->orderCustomReferenceValidator
            ->isOrderCustomReferenceLengthValid($orderCustomReference);

        if (!$isOrderCustomReferenceLengthValid) {
            $quoteResponseTransfer->addError(
                (new QuoteErrorTransfer())->setMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH)
            );

            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteTransfer->setOrderCustomReference($orderCustomReference);

        $quoteResponseTransfer = $this->orderCustomReferenceClient->setOrderCustomReference($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
    }
}
