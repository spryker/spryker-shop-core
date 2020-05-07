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
class ReturnViewController extends AbstractReturnController
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
    public function viewAction(Request $request): View
    {
        $response = $this->executeViewAction($request);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-view/return-view.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeViewAction(Request $request): array
    {
        $returnTransfer = $this->getReturnByReference($request->get(static::PARAM_RETURN_REFERENCE));

        return [
            'return' => $returnTransfer,
            'returnItemsCount' => $returnTransfer->getReturnItems()->count(),
        ];
    }
}
