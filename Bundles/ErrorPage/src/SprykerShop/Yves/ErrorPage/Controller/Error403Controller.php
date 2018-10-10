<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;

class Error403Controller extends AbstractController
{
    protected const REQUEST_PARAM_EXCEPTION = 'exception';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->viewResponse([
            'error' => $this->getErrorMessage($request),
            'hideUserMenu' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getErrorMessage(Request $request)
    {
        $exception = $request->query->get(self::REQUEST_PARAM_EXCEPTION);

        if ($exception instanceof FlattenException) {
            return $exception->getMessage();
        }

        return '';
    }
}
