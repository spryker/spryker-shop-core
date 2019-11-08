<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @deprecated Use `\SprykerShop\Yves\ErrorPage\Plugin\EventDispatcher\ErrorPageEventDispatcherPlugin` instead.
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
        $app['dispatcher']->addListener(KernelEvents::EXCEPTION, [$this, 'onKernelException'], 50);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        $exceptionHandlerPlugins = $this->getFactory()->getExceptionHandlerPlugins();
        foreach ($exceptionHandlerPlugins as $exceptionHandlerPlugin) {
            if (!$exceptionHandlerPlugin->canHandle($statusCode)) {
                continue;
            }

            $response = $exceptionHandlerPlugin->handleException(FlattenException::create($exception));

            $event->setResponse($response);
            $event->stopPropagation();

            break;
        }
    }
}
