<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\ExceptionHandler;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Yves\ErrorPage\Dependency\Plugin\ExceptionHandlerPluginInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageFactory getFactory()
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 */
class SubRequestExceptionHandlerPlugin extends AbstractPlugin implements ExceptionHandlerPluginInterface
{
    /**
     * @deprecated Use `\SprykerShop\Yves\ErrorPage\Plugin\ExceptionHandler\SubRequestExceptionHandlerPlugin::SERVICE_REQUEST_STACK` instead.
     *
     * @var string
     */
    public const SERVICE_REQUEST = 'request';

    /**
     * @var string
     */
    public const URL_NAME_PREFIX = 'error/';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @see \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTERS = 'routers';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @see \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @see \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     *
     * @var string
     */
    protected const SERVICE_KERNEL = 'kernel';

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
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception)
    {
        $request = $this->getFactory()->getRequestStack()->getCurrentRequest();
        $router = $this->getFactory()->getRouter();

        $errorPageUrl = $router->generate(static::URL_NAME_PREFIX . $exception->getStatusCode(), [], UrlGeneratorInterface::ABSOLUTE_URL);

        $cookies = $request->cookies->all();

        $subRequest = Request::create(
            $errorPageUrl,
            Request::METHOD_GET,
            [
                'exception' => $exception,
            ],
            $cookies,
        );

        if ($request->hasSession()) {
            $subRequest->setSession($request->getSession());
        }

        return $this->getFactory()->getKernel()->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Yves\Router\Router\ChainRouter
     */
    protected function getRouter(ContainerInterface $container): ChainRouter
    {
        return $container->get(static::SERVICE_ROUTERS);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(ContainerInterface $container): Request
    {
        /** @var \Symfony\Component\HttpFoundation\RequestStack $requestStack */
        $requestStack = $container->get(static::SERVICE_REQUEST_STACK);

        return $requestStack->getCurrentRequest();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getKernel(ContainerInterface $container): HttpKernelInterface
    {
        return $container->get(static::SERVICE_KERNEL);
    }
}
