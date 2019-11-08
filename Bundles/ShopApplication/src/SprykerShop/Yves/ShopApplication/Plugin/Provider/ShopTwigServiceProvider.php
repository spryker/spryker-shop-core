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
use SprykerShop\Yves\ShopApplication\Exception\InvalidApplicationException;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

/**
 * @deprecated Use `\SprykerShop\Yves\ShopApplication\Plugin\EventDispatcher\ShopApplicationEventDispatcherPlugin` instead.
 *
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
            return new ChainLoader(
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
            $app->extend('twig', function (Environment $twig) use ($app) {
                if (class_exists('Symfony\Bridge\Twig\Extension\RoutingExtension')) {
                    if (isset($app['form.factory'])) {
                        $app['twig.loader']->addLoader(
                            new FilesystemLoader(__DIR__ . '/../../Resources/views/Form')
                        );
                    }
                }

                foreach ($app['twig.global.variables'] as $name => $value) {
                    $twig->addGlobal($name, $value);
                }

                $twig->addExtension($this->getFactory()->createShopApplicationTwigExtensionPlugin());

                return $twig;
            })
        );
    }
}
