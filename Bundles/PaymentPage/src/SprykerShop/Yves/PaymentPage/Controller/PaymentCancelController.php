<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Controller;

use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;

/**
 * @method \SprykerShop\Yves\PaymentPage\PaymentPageFactory getFactory()
 */
class PaymentCancelController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ORDER_REFERENCE = 'orderReference';

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $this->cancelOrder();
        $this->getFactory()->getCustomerClient()->markCustomerAsDirty();
        $this->getFactory()->getCartClient()->clearQuote();

        return $this->view([], [], '@PaymentPage/views/payment-cancel/index.twig');
    }

    /**
     * The order cancellation only happens if the order reference is provided and the customer is logged in.
     * Also, `SalesClient` has to have the `SalesClient::cancelOrder()` method implemented.
     *
     * @return void
     */
    protected function cancelOrder(): void
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->getRequestStack()->getCurrentRequest();
        $orderReference = $request->query->get(static::PARAM_ORDER_REFERENCE);
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($orderReference !== null && $customerTransfer !== null) {
            $this->getFactory()->getSalesClient()->cancelOrder((new OrderCancelRequestTransfer())
                ->setCustomer($customerTransfer)
                ->setOrderReference((string)$orderReference));
        }
    }
}
