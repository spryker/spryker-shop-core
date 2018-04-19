<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Communication\Application as SprykerApplication;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShopApplication\Exception\InvalidApplicationException;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig_Environment;
use Twig_Loader_Chain;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopTwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    use PermissionAwareTrait;

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
        $this->setTwigOptions($app);
        $this->registerTwig($app);
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
    protected function setTwigOptions(Application $app)
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
            $app->extend('twig', function (\Twig_Environment $twig) use ($app) {
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

                $this->registerWidgetTwigFilter($twig);
                $this->registerTwigFunctionCan($twig);

                return $twig;
            })
        );
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return void
     */
    protected function registerTwigFunctionCan(Twig_Environment $twig)
    {
        $canFunction = new Twig_SimpleFunction('can', [
            $this,
            'can',
        ], [
            'needs_context' => false,
            'needs_environment' => false,
        ]);

        $twig->addFunction($canFunction->getName(), $canFunction);
    }

    /**
     * @param string $permissionKey
     * @param string|int|mixed|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null)
    {
        return true;
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return void
     */
    protected function registerWidgetTwigFilter(Twig_Environment $twig)
    {
        foreach ($this->getTwigFilters() as $filter) {
            $twig->addFilter($filter->getName(), $filter);
        }
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
