<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class OrderCustomReferenceWidgetToOrderCustomReferenceClientBridge implements OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface
{
    /**
     * @var \Spryker\Client\OrderCustomReference\OrderCustomReferenceClientInterface
     */
    protected $orderCustomReferenceClient;

    /**
     * @param \Spryker\Client\OrderCustomReference\OrderCustomReferenceClientInterface $orderCustomReferenceClient
     */
    public function __construct($orderCustomReferenceClient)
    {
        $this->orderCustomReferenceClient = $orderCustomReferenceClient;
    }

    /**
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(
        string $orderCustomReference,
        QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer {
        return $this->orderCustomReferenceClient->setOrderCustomReference($orderCustomReference, $quoteTransfer);
    }
}
