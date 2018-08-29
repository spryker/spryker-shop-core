<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\Communication\Application as SprykerApplication;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\View\ViewInterface;
use SprykerShop\Yves\ShopApplication\Exception\InvalidApplicationException;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class WidgetTagServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const WIDGET_TAG_SERVICE = 'widget_tag_service';

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function register(Application $application)
    {
        $this->addWidgetTagService($application);
        $this->addWidgetTagTokenParser($application);
    }

    /**
     * @param \Silex\Application $app
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\InvalidApplicationException
     *
     * @return void
     */
    public function boot(Application $app)
    {
        if (!$app instanceof SprykerApplication) {
            throw new InvalidApplicationException(sprintf(
                'The used application object need to be an instance of %s.',
                SprykerApplication::class
            ));
        }

        $app['dispatcher']->addListener(KernelEvents::VIEW, function (GetResponseForControllerResultEvent $event) use ($app) {
            $this->onKernelView($event, $app);
        }, 0);
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    protected function addWidgetTagService(Application $application): void
    {
        $application[self::WIDGET_TAG_SERVICE] = $application->share(function () {
            return $this->getFactory()->createWidgetTagService();
        });
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    protected function addWidgetTagTokenParser(Application $application): void
    {
        $application['twig'] = $application->share(
            $application->extend('twig', function (\Twig_Environment $twig) {
                $twig->addTokenParser($this->getFactory()->createWidgetTagTokenParser());

                return $twig;
            })
        );
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    protected function onKernelView(GetResponseForControllerResultEvent $event, SprykerApplication $application)
    {
        /** @var \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $result */
        $result = $event->getControllerResult();

        if (!$result instanceof ViewInterface) {
            return;
        }

        /** @var \Twig_Environment $twig */
        $twig = $application['twig'];
        $twig->addGlobal('_view', $result);

        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($result);

        if ($result->getTemplate()) {
            $response = $application->render($result->getTemplate(), $this->getViewParameters($result));
        } else {
            $response = $this->getFactory()
                ->createTwigRenderer()
                ->render($application, $this->getViewParameters($result));
        }

        $event->setResponse($response);
        $widgetContainerRegistry->removeLastAdded();
    }

    /**
     * @param \Spryker\Yves\Kernel\View\ViewInterface $view
     *
     * @return array|null
     */
    protected function getViewParameters(ViewInterface $view)
    {
        if ($this->getConfig()->useViewParametersToRenderTwig()) {
            return $view->getData();
        }

        return [];
    }
}
