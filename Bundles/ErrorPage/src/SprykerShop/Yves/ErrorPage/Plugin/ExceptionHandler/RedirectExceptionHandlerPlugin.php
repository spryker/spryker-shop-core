<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\ExceptionHandler;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ErrorPageExtension\Dependency\Plugin\ExceptionHandlerPluginInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageFactory getFactory()
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 */
class RedirectExceptionHandlerPlugin extends AbstractPlugin implements ExceptionHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if an exception can be handled by statusCode.
     *
     * @api
     *
     * @param int $statusCode
     *
     * @return bool
     */
    public function canHandle($statusCode)
    {
        return in_array($statusCode, $this->getConfig()->getValidRedirectExceptionStatusCodes());
    }

    /**
     * {@inheritDoc}
     * - Handles an RedirectExceptionHandler.
     *
     * @api
     *
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception)
    {
        return $this->getFactory()
            ->createRedirectExceptionHandler()
            ->handle($exception);
    }
}
