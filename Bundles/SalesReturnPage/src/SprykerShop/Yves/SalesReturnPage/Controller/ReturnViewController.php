<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnViewController extends AbstractReturnController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function viewAction(Request $request, string $returnReference)
    {
        $response = $this->executeviewAction($request, $returnReference);

        if (!is_array($response)) {
            return $response;
        }

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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeViewAction(Request $request, string $returnReference)
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
            'return' => $returnTransfer,
            'returnItemsCount' => $returnTransfer->getReturnItems()->count(),
        ];
    }
}
