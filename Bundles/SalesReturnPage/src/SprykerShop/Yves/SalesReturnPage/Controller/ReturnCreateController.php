<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\OrderListRequestTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnCreateController extends AbstractReturnController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $orderReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, string $orderReference)
    {
        $response = $this->executecreateAction($request, $orderReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-create/return-create.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $orderReference
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request, string $orderReference)
    {
        /**
         * @var \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfersCollection
         */
        $orderTransfersCollection = $this->getFactory()
            ->getSalesClient()
            ->getOffsetPaginatedCustomerOrderList($this->createOrderListRequestTransfer($orderReference))
            ->getOrders();
        dd($orderTransfersCollection);

        return [

        ];
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\OrderListRequestTransfer
     */
    protected function createOrderListRequestTransfer($orderReference): OrderListRequestTransfer
    {
        return (new OrderListRequestTransfer())
            ->setCustomerReference($this->getFactory()->getCustomerClient()->getCustomer()->getCustomerReference())
            ->setOrderReference($orderReference);
    }
}
