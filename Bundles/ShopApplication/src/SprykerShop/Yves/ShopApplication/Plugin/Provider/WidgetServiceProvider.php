<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Exception;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\Communication\Application as SprykerApplication;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\View\View;
use Spryker\Yves\Kernel\View\ViewInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 */
class WidgetServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->registerBaseWidgetContainer($app);

        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) use ($app) {
                $twig = $this->registerWidgetTwigFunction($twig);

                return $twig;
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        if (!$app instanceof SprykerApplication) {
            return; // TODO: perhaps throw exception
        }

        $app['dispatcher']->addListener(KernelEvents::VIEW, function (GetResponseForControllerResultEvent $event) use ($app) {
            $this->onKernelView($event, $app);
        }, 0);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function registerBaseWidgetContainer(Application $app): void
    {
        $widgetContainerRegistry = new WidgetContainerRegistry($app);
        $widgetContainerRegistry->add(new View([], $this->getFactory()->getBaseWidgetPlugins()));
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return \Twig_Environment
     */
    protected function registerWidgetTwigFunction(Twig_Environment $twig)
    {
        $functions = $this->getFunctions();
        foreach ($functions as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
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
            new Twig_SimpleFunction('widgetExists', [$this, 'widgetExists'], [
                'needs_context' => false,
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $name
     * @param array $arguments
     *
     * @throws \Exception
     *
     * @return string
     */
    public function widget(Twig_Environment $twig, $name, ...$arguments)
    {
        // TODO: refactor
        try {
            $widgetContainer = $this->getWidgetContainer();

            if (!$widgetContainer->hasWidget($name)) {
                return '';
            }

            $widgetClass = $widgetContainer->getWidgetClassName($name);
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
            // TODO: use custom exception
            throw new Exception(sprintf(
                'Something went wrong in widget "%s": %s',
                $name,
                $e->getMessage()
            ), $e->getCode(), $e);
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $name
     * @param string $block
     * @param array $arguments
     *
     * @throws \Exception
     *
     * @return string
     */
    public function widgetBlock(Twig_Environment $twig, $name, $block, ...$arguments)
    {
        // TODO: refactor
        try {
            $view = $this->getWidgetContainer();

            if (!$view->hasWidget($name)) {
                return '';
            }

            $widgetClass = $view->getWidgetClassName($name);
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
            // TODO: use custom exception
            throw new Exception(sprintf(
                'Something went wrong in widget "%s": %s',
                $name,
                $e->getMessage()
            ), $e->getCode(), $e);
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
     * @throws \Exception
     *
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function getWidgetContainer(): WidgetContainerInterface
    {
        $widgetRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainer = $widgetRegistry->getLastAdded();

        if (!$widgetContainer) {
            // TODO: use custom exception
            throw new Exception(sprintf(
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
        $result = $event->getControllerResult();

        if (!$result instanceof ViewInterface) {
            return;
        }

        /** @var \Twig_Environment $twig */
        $twig = $application['twig'];
        $twig->addGlobal('_view', $result);

        // TODO: clean up
        $widgetContainerRegistry = new WidgetContainerRegistry($application);
        $widgetContainerRegistry->add($result);

        if ($result->getTemplate()) {
            $response = $application->render($result->getTemplate());
        } else {
            $response = $this->getFactory()
                ->createTwigRenderer()
                ->render($application);
        }

        $event->setResponse($response);
        $widgetContainerRegistry->removeLastAdded();
    }
}
