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
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `SprykerShop\Yves\ShopApplication\Plugin\Twig\WidgetTagTwigPlugin` instead to provide twig functionality.
 * @deprecated Use `\SprykerShop\Yves\ShopApplication\Plugin\EventDispatcher\ShopApplicationEventDispatcherPlugin` instead to handle View response.
 *
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class WidgetTagServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const WIDGET_TAG_SERVICE = 'widget_tag_service';

    protected const TWIG_FUNCTION_FIND_WIDGET = 'findWidget';
    protected const TWIG_GLOBAL_VARIABLE_NAME_VIEW = '_view';

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function register(Application $application)
    {
        $this->addWidgetTagService($application);
        $this->addWidgetTagTokenParser($application);

        $application['twig'] = $application->share(
            $application->extend('twig', function (Environment $twig) {
                return $this->registerWidgetTwigFunction($twig);
            })
        );
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
        $application[static::WIDGET_TAG_SERVICE] = $application->share(function () {
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
            $application->extend('twig', function (Environment $twig) {
                $twig->addTokenParser($this->getFactory()->createWidgetTagTokenParser());

                return $twig;
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerWidgetTwigFunction(Environment $twig)
    {
        foreach ($this->getFunctions() as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    protected function getFunctions(): array
    {
        return [
            new TwigFunction(static::TWIG_FUNCTION_FIND_WIDGET, [$this, 'findWidget'], [
                'needs_context' => false,
            ]),
        ];
    }

    /**
     * @param string $widgetName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|null
     */
    public function findWidget(string $widgetName, array $arguments = [])
    {
        $widgetTagService = $this->getFactory()->createWidgetTagService();
        $widget = $widgetTagService->openWidgetContext($widgetName, $arguments);

        if (!$widget) {
            return null;
        }

        $widgetTagService->closeWidgetContext();

        return $widget;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    protected function onKernelView(GetResponseForControllerResultEvent $event, SprykerApplication $application): void
    {
        /** @var \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $result */
        $result = $event->getControllerResult();

        if (!$result instanceof ViewInterface) {
            return;
        }

        $masterGlobalView = null;

        /** @var \Twig\Environment $twig */
        $twig = $application['twig'];

        if (!$event->isMasterRequest()) {
            $masterGlobalView = $this->getGlobalView($twig);
        }

        $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_VIEW, $result);

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

        if ($masterGlobalView) {
            $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_VIEW, $masterGlobalView);
        }
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Spryker\Yves\Kernel\View\ViewInterface|null
     */
    protected function getGlobalView(Environment $twig): ?ViewInterface
    {
        $twigGlobals = $twig->getGlobals();

        if (!isset($twigGlobals[static::TWIG_GLOBAL_VARIABLE_NAME_VIEW])) {
            return null;
        }

        return $twigGlobals[static::TWIG_GLOBAL_VARIABLE_NAME_VIEW];
    }

    /**
     * @param \Spryker\Yves\Kernel\View\ViewInterface $view
     *
     * @return array
     */
    protected function getViewParameters(ViewInterface $view): array
    {
        if ($this->getConfig()->useViewParametersToRenderTwig()) {
            return $view->getData();
        }

        return [];
    }
}
