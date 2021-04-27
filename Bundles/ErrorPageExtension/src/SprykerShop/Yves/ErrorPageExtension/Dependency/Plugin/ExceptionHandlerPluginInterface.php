<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPageExtension\Dependency\Plugin;

use Symfony\Component\ErrorHandler\Exception\FlattenException;

/**
 * Provides extension capabilities for the exception handling.
 */
interface ExceptionHandlerPluginInterface
{
    /**
     * Specification:
     * - Checks if an exception can be handled by statusCode.
     *
     * @api
     *
     * @param int $statusCode
     *
     * @return bool
     */
    public function canHandle($statusCode);

    /**
     * Specification:
     * - Handles an exception.
     *
     * @api
     *
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception);
}
