<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class SalesReturnDetailsController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailsAction(Request $request, string $returnReference): View
    {
        $detailsData = $this->executeDetailsAction($request, $returnReference);

        return $this->view($detailsData, [], '@SalesReturnPage/views/sales-return-details/sales-return-details.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    public function executeDetailsAction(Request $request, string $returnReference): array
    {
        $returnFilterTransfer = (new ReturnFilterTransfer())
            ->setReturnReference($returnReference);

        /**
         * @var \Generated\Shared\Transfer\ReturnTransfer|null $returnTransfer
         */
        $returnTransfer = $this->getFactory()
            ->getSalesReturnClient()
            ->getReturns($returnFilterTransfer)
            ->getReturns()
            ->getIterator()
            ->current();

        if (!$returnTransfer) {
            throw new NotFoundHttpException();
        }

        return [
            'returns' => $returnTransfer,
            'reurnItemsCount' => $returnTransfer->getReturnItems()->count(),
        ];
    }
}
