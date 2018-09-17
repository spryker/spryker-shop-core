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
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException;
use SprykerShop\Yves\ShopApplication\Exception\InvalidApplicationException;
use SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class WidgetServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function register(Application $application)
    {
        $application['twig'] = $application->share(
            $application->extend('twig', function (Twig_Environment $twig) {
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
     * @param \Twig_Environment $twig
     *
     * @return \Twig_Environment
     */
    protected function registerWidgetTwigFunction(Twig_Environment $twig)
    {
        foreach ($this->getFunctions() as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    protected function getFunctions()
    {
        return [
            new Twig_SimpleFunction('widget', [$this, 'widget'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('widgetBlock', [$this, 'widgetBlock'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('widgetGlobal', [$this, 'widgetGlobal'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('widgetExists', [$this, 'widgetExists'], [
                'needs_context' => false,
            ]),
            new Twig_SimpleFunction('widgetGlobalExists', [$this, 'widgetGlobalExists'], [
                'needs_context' => false,
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return string
     */
    public function widget(Twig_Environment $twig, $widgetName, ...$arguments)
    {
        try {
            $widgetContainer = $this->getWidgetContainer();

            if (!$widgetContainer->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $twig->addGlobal('_widget', $widget);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->render();

            $widgetContainerRegistry->removeLastAdded();

            return $result;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param string $block
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return string
     */
    public function widgetBlock(Twig_Environment $twig, $widgetName, $block, ...$arguments)
    {
        try {
            $widgetContainer = $this->getWidgetContainer();

            if (!$widgetContainer->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $twig->addGlobal('_widget', $widget);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->renderBlock($block);

            $widgetContainerRegistry->removeLastAdded();

            return $result;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return string
     */
    public function widgetGlobal(Twig_Environment $twig, $widgetName, ...$arguments)
    {
        try {
            $widgetCollection = $this->getFactory()->createWidgetCollection();

            if (!$widgetCollection->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetCollection->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $twig->addGlobal('_widget', $widget);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->render();

            $widgetContainerRegistry->removeLastAdded();

            return $result;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function widgetExists($name)
    {
        return $this->getWidgetContainer()->hasWidget($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function widgetGlobalExists($name)
    {
        return $this->getFactory()->createWidgetCollection()->hasWidget($name);
    }

    /**
     * @throws \SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException
     *
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function getWidgetContainer(): WidgetContainerInterface
    {
        $widgetRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainer = $widgetRegistry->getLastAdded();

        if (!$widgetContainer) {
            throw new EmptyWidgetRegistryException(sprintf(
                'You have tried to access a widget but %s is empty. To fix this you need to register your widget or view in the registry.',
                get_class($widgetRegistry)
            ));
        }

        return $widgetContainer;
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

    /**
     * @param string $widgetName
     * @param \Throwable $e
     *
     * @return \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     */
    protected function createWidgetRenderException(string $widgetName, Throwable $e): WidgetRenderException
    {
        return new WidgetRenderException(sprintf(
            '%s - Something went wrong in widget "%s": %s in %s:%d',
            get_class($e),
            $widgetName,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ), $e->getCode(), $e);
    }
}
