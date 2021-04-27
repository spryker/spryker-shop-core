<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Handler;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

interface RedirectExceptionHandlerInterface
{
    /**
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(FlattenException $exception): Response;
}
