<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\ExceptionHandler;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ErrorPage\Dependency\Plugin\ExceptionHandlerPluginInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageFactory getFactory()
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 */
class SubRequestExceptionHandlerPlugin extends AbstractPlugin implements ExceptionHandlerPluginInterface
{
    public const URL_NAME_PREFIX = 'error/';

    /**
     * @param int $statusCode
     *
     * @return bool
     */
    public function canHandle($statusCode)
    {
        return in_array($statusCode, $this->getConfig()->getValidSubRequestExceptionStatusCodes());
    }

    /**
     * @param \Symfony\Component\Debug\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception)
    {
        $application = $this->getFactory()->getApplication();

        $errorPageUrl = $application->url(static::URL_NAME_PREFIX . $exception->getStatusCode());
        $request = Request::create($errorPageUrl, Request::METHOD_GET, [
            'exception' => $exception,
        ]);

        $response = $application->handle($request, HttpKernelInterface::SUB_REQUEST, false);

        return $response;
    }
}
