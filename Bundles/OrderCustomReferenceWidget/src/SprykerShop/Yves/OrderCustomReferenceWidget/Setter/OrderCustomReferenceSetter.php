<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Setter;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface;

class OrderCustomReferenceSetter implements OrderCustomReferenceSetterInterface
{
    /**
     * @var \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface
     */
    protected $orderCustomReferenceClient;

    /**
     * @param \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface $orderCustomReferenceClient
     */
    public function __construct(
        OrderCustomReferenceWidgetToQuoteClientInterface $quoteClient,
        OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface $orderCustomReferenceClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->orderCustomReferenceClient = $orderCustomReferenceClient;
    }

    /**
     * @param string|null $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(?string $orderCustomReference): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->orderCustomReferenceClient->setOrderCustomReference($orderCustomReference);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
    }
}
