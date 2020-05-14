<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Spryker\Yves\Kernel\View\View;
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
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function printAction(Request $request, string $returnReference): View
    {
        $response = $this->executePrintAction($request, $returnReference);

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
    protected function executePrintAction(Request $request, string $returnReference): array
    {
        return [
            'return' => $this->getReturnByReference($returnReference),
        ];
    }
}
