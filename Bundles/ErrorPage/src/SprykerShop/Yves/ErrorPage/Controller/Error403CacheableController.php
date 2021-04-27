<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageFactory getFactory()
 */
class Error403CacheableController extends AbstractController
{
    protected const QUERY_PARAMETER_ERROR_MESSAGE = 'errorMessage';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        return $this->view([
            'error' => $this->getErrorMessage($request),
            'hideUserMenu' => true,
        ], [], '@ErrorPage/views/error403/error403.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getErrorMessage(Request $request): string
    {
        if (!$this->getFactory()->getConfig()->isErrorStackTraceEnabled()) {
            return '';
        }

        return $request->query->get(static::QUERY_PARAMETER_ERROR_MESSAGE) ?? '';
    }
}
