<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageFactory getFactory()
 */
class Error404Controller extends AbstractController
{
    protected const REQUEST_PARAM_EXCEPTION = 'exception';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $exception = $request->query->get(static::REQUEST_PARAM_EXCEPTION);

        return $this->viewResponse([
            'error' => $this->getFactory()->createErrorMessage()->getNotFoundMessage($exception),
            'hideUserMenu' => true,
        ]);
    }
}
