<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnViewController extends AbstractReturnController
{
    /**
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function viewAction(string $returnReference): View
    {
        $response = $this->executeViewAction($returnReference);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-view/return-view.twig',
        );
    }

    /**
     * @param string $returnReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeViewAction(string $returnReference): array
    {
        $returnTransfer = $this->getFactory()
            ->createReturnReader()
            ->findReturnByReference($returnReference, $this->getFactory()->getCustomerClient()->getCustomer()->getCustomerReference());

        if (!$returnTransfer) {
            throw new NotFoundHttpException(sprintf(
                "Return with provided reference %s doesn't exist",
                $returnReference,
            ));
        }

        return [
            'return' => $returnTransfer,
            'uniqueOrderReferences' => $this->extractUniqueOrderReferencesFromReturn($returnTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return array<string>
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
