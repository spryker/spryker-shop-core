<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnSlipPrintController extends AbstractReturnController
{
    /**
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function printAction(string $returnReference): View
    {
        $response = $this->executePrintAction($returnReference);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-print/return-print.twig',
        );
    }

    /**
     * @param string $returnReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executePrintAction(string $returnReference): array
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
            'return' => $this->sortReturnItemByOrderReference($returnTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    protected function sortReturnItemByOrderReference(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnTransfer->getReturnItems()->uasort(
            function (ReturnItemTransfer $firstReturnItemTransfer, ReturnItemTransfer $secondReturnItemTransfer) {
                return strcmp($firstReturnItemTransfer->getOrderItem()->getOrderReference(), $secondReturnItemTransfer->getOrderItem()->getOrderReference());
            },
        );

        return $returnTransfer;
    }
}
