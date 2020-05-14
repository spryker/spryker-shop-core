<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnViewController extends AbstractReturnController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function viewAction(Request $request, string $returnReference): View
    {
        $response = $this->executeViewAction($request, $returnReference);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-view/return-view.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return array
     */
    protected function executeViewAction(Request $request, string $returnReference): array
    {
        $returnTransfer = $this->getReturnByReference($returnReference);

        return [
            'return' => $returnTransfer,
            'uniqueOrderReferences' => $this->extractUniqueOrderReferencesFromReturn($returnTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string[]
     */
    protected function extractUniqueOrderReferencesFromReturn(ReturnTransfer $returnTransfer): array
    {
        $uniqueOrderReferences = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $idSalesOrder = $returnItemTransfer->getOrderItem()->getFkSalesOrder();
            $orderReference = $returnItemTransfer->getOrderItem()->getOrderReference();

            $uniqueOrderReferences[$idSalesOrder] = $orderReference;
        }

        return $uniqueOrderReferences;
    }
}
