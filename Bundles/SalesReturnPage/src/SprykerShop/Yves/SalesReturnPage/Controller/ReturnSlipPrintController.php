<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnSlipPrintController extends AbstractReturnController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function printAction(Request $request, string $returnReference)
    {
        $response = $this->executeprintAction($request, $returnReference);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-print/return-print.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $returnReference
     *
     * @return array
     */
    protected function executePrintAction(Request $request, string $returnReference)
    {
        $returnTransfer = $this->getReturnByReference($returnReference);

        return [
            'return' => $returnTransfer,
            'returnItemsCount' => $returnTransfer->getReturnItems()->count(),
        ];
    }
}
