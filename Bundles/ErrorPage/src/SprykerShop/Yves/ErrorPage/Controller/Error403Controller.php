<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;

class Error403Controller extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_EXCEPTION = 'exception';

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
    protected function getErrorMessage(Request $request)
    {
        /** @var \Symfony\Component\ErrorHandler\Exception\FlattenException|null $exception */
        $exception = $request->query->all()[static::REQUEST_PARAM_EXCEPTION] ?? null;

        if ($exception instanceof FlattenException) {
            return $exception->getMessage();
        }

        return '';
    }
}
