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
     * @uses \SprykerShop\Yves\SalesReturnPage\Plugin\Router\SalesReturnPageRouteProviderPlugin::PARAM_RETURN_REFERENCE
     */
    protected const PARAM_RETURN_REFERENCE = 'returnReference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function printAction(Request $request): View
    {
        $response = $this->executePrintAction($request);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-print/return-print.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executePrintAction(Request $request): array
    {
        return [
            'return' => $this->getReturnByReference($request->get(static::PARAM_RETURN_REFERENCE)),
        ];
    }
}
