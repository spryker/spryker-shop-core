<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Communication\Application as SprykerApplication;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig_Environment;
use Twig_Loader_Chain;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopTwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->getFactory()
            ->createSilexTwigServiceProvider()
            ->register($app);

        $this->registerTwigLoaderChain($app);
        $this->registerTwigCache($app);
        $this->registerTwig($app);

        // TODO: move these to somewhere else
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) use ($app) {
                $twig = $this->registerWidgetTwigFilter($twig);

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

        $this->getFactory()
            ->createSilexTwigServiceProvider()
            ->boot($app);

        $app['dispatcher']->addListener(KernelEvents::VIEW, function (GetResponseForControllerResultEvent $event) use ($app) {
            $this->onKernelView($event, $app);
        }, 0);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    public function onKernelView(GetResponseForControllerResultEvent $event, SprykerApplication $application)
    {
        $result = $event->getControllerResult();

        if (empty($result) || is_array($result)) {
            $response = $this->getFactory()
                ->createTwigRenderer()
                ->render($application, (array)$result);

            $event->setResponse($response);

            return;
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function registerTwigLoaderChain(Application $app)
    {
        $app['twig.loader'] = $app->share(function ($app) {
            return new Twig_Loader_Chain(
                [
                    $app['twig.loader.yves'],
                    $app['twig.loader.filesystem'],
                ]
            );
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function registerTwigCache(Application $app)
    {
        $app['twig.options'] = Config::get(TwigConstants::YVES_TWIG_OPTIONS);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function registerTwig(Application $app)
    {
        $app['twig.form.templates'] = $this->getConfig()->getFormThemes();
        $app['twig.global.variables'] = $app->share(function () {
            return [];
        });
        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function (\Twig_Environment $twig) use ($app) {
                    if (class_exists('Symfony\Bridge\Twig\Extension\RoutingExtension')) {
                        if (isset($app['form.factory'])) {
                            $app['twig.loader']->addLoader(
                                new Twig_Loader_Filesystem(__DIR__ . '/../../Resources/views/Form')
                            );
                        }
                    }

                    foreach ($app['twig.global.variables'] as $name => $value) {
                        $twig->addGlobal($name, $value);
                    }

                    return $twig;
                }
            )
        );
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return \Twig_Environment
     */
    protected function registerWidgetTwigFilter(Twig_Environment $twig)
    {
        $filters = $this->getTwigFilters();
        foreach ($filters as $filter) {
            $twig->addFilter($filter->getName(), $filter);
        }

        return $twig;
    }

    /**
     * @return \Twig_SimpleFilter[]
     */
    protected function getTwigFilters()
    {
        return [
            new Twig_SimpleFilter('floor', function ($value) {
                return floor($value);
            }),
            new Twig_SimpleFilter('ceil', function ($value) {
                return ceil($value);
            }),
            new Twig_SimpleFilter('int', function ($value) {
                return (int)$value;
            }),
        ];
    }
}
