<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Form\Handler;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCartReorderClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCustomerClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToZedRequestClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig;
use Symfony\Component\HttpFoundation\Request;

class OrderAmendmentHandler implements OrderAmendmentHandlerInterface
{
    /**
     * @param \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCartReorderClientInterface $cartReorderClient
     * @param \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToZedRequestClientInterface $zedRequestClient
     * @param \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig $salesOrderAmendmentWidgetConfig
     */
    public function __construct(
        protected SalesOrderAmendmentWidgetToCustomerClientInterface $customerClient,
        protected SalesOrderAmendmentWidgetToCartReorderClientInterface $cartReorderClient,
        protected SalesOrderAmendmentWidgetToZedRequestClientInterface $zedRequestClient,
        protected SalesOrderAmendmentWidgetConfig $salesOrderAmendmentWidgetConfig
    ) {
    }

    /**
     * @param string $orderReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function amendOrder(string $orderReference, Request $request): CartReorderResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->customerClient->getCustomer();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->fromArray($customerTransfer->toArray(), true)
            ->setOrderReference($orderReference)
            ->setIsAmendment(true)
            ->setReorderStrategy($this->salesOrderAmendmentWidgetConfig->getCartReorderStrategyForOrderAmendment());

        $cartReorderResponseTransfer = $this->cartReorderClient->reorder($cartReorderRequestTransfer);

        if (!$cartReorderResponseTransfer->getErrors()->count()) {
            $this->zedRequestClient->addResponseMessagesToMessenger();
        }

        return $cartReorderResponseTransfer;
    }
}
