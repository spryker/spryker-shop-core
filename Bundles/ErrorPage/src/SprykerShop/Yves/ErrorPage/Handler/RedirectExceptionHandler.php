<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Handler;

use Spryker\Yves\Router\Router\ChainRouter;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectExceptionHandler implements RedirectExceptionHandlerInterface
{
    /**
     * @var string
     */
    protected const URL_NAME_PREFIX = 'error-page/';
    /**
     * @var string
     */
    protected const QUERY_PARAMETER_ERROR_MESSAGE = 'errorMessage';

    /**
     * @var \Spryker\Yves\Router\Router\ChainRouter
     */
    protected $router;

    /**
     * @param \Spryker\Yves\Router\Router\ChainRouter $router
     */
    public function __construct(ChainRouter $router)
    {
        $this->router = $router;
    }

    /**
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(FlattenException $exception): Response
    {
        $errorPageUrl = $this->router->generate(
            static::URL_NAME_PREFIX . $exception->getStatusCode(),
            [
                static::QUERY_PARAMETER_ERROR_MESSAGE => $exception->getMessage(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return RedirectResponse::create($errorPageUrl, Response::HTTP_MOVED_PERMANENTLY);
    }
}
