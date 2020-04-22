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
         * @var \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfersCollections
         */
        $orderTransfersCollections = $this->getFactory()
            ->getSalesClient()
            ->getOffsetPaginatedCustomerOrderList($this->createOrderListRequestTransfer($orderReference))
            ->getOrders();

        if (!$orderTransfersCollections->count()) {
            dd(1);
        }

        /**
         * @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer
         */
        $orderTransfer = $orderTransfersCollections->offsetGet(0);
        $returnCreateForm = $this->getFactory()->getCreateReturnForm($orderTransfer);
        dd($request);

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
        ];
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\OrderListRequestTransfer
     */
    protected function createOrderListRequestTransfer(string $orderReference): OrderListRequestTransfer
    {
        return (new OrderListRequestTransfer())
            ->setCustomerReference($this->getFactory()->getCustomerClient()->getCustomer()->getCustomerReference())
            ->setOrderReference($orderReference);
    }
}
