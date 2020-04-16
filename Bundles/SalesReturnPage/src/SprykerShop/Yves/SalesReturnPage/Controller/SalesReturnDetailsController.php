<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class SalesReturnDetailsController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request, string $returnReference)
    {
        $detailsData = $this->executeDetailsAction($request, $returnReference);

        return $this->view($detailsData, [], '@SalesReturnPage/views/sales-return-details/sales-return-details.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return array
     */
    public function executeDetailsAction(Request $request, string $returnReference)
    {
        $returnFilterTransfer = (new ReturnFilterTransfer())
            ->setReturnReference($returnReference);

        $salesReturnTransfer = $this->getFactory()
            ->getSalesReturnClient()
            ->getReturns($returnFilterTransfer)
            ->getReturns()
            ->offsetGet(0);

        return [
            'return' => $salesReturnTransfer,
        ];
    }
}
