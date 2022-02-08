<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @deprecated Use {@link \SprykerShop\Yves\ErrorPage\Plugin\EventDispatcher\ErrorPageEventDispatcherPlugin} instead.
 *
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageFactory getFactory()
 */
class ErrorPageServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $appDispatcher */
        $appDispatcher = $app['dispatcher'];
        $appDispatcher->addListener(KernelEvents::EXCEPTION, [$this, 'onKernelException'], 50);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        $exceptionHandlerPlugins = $this->getFactory()->getExceptionHandlerPlugins();
        foreach ($exceptionHandlerPlugins as $exceptionHandlerPlugin) {
            if (!$exceptionHandlerPlugin->canHandle($statusCode)) {
                continue;
            }

            $response = $exceptionHandlerPlugin->handleException(FlattenException::createFromThrowable($exception));

            $event->setResponse($response);
            $event->stopPropagation();

            break;
        }
    }
}
