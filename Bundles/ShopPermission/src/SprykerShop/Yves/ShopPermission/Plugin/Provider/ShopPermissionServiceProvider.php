<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopPermission\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @deprecated Use `SprykerShop\Yves\ShopPermission\Plugin\Twig\ShopPermissionTwigPlugin` instead.
 *
 * @method \SprykerShop\Yves\ShopPermission\ShopPermissionFactory getFactory()
 */
class ShopPermissionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) use ($app) {
                $twig = $this->registerPermissionTwigFunction($twig, $app);
                $twig = $this->registerPermissionTwigExtensions($twig);

                return $twig;
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Silex\Application $app
     *
     * @return \Twig\Environment
     */
    protected function registerPermissionTwigFunction(Environment $twig, Application $app)
    {
        foreach ($this->getPermissionTwigFunctions($app) as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return array
     */
    protected function getPermissionTwigFunctions(Application $app)
    {
        $functions = [];
        foreach ($this->getFactory()->getPermissionTwigFunctionPlugins() as $twigFunction) {
            $functions = array_merge($functions, $twigFunction->getFunctions($app));
        }

        return $functions;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerPermissionTwigExtensions(Environment $twig): Environment
    {
        foreach ($this->getFactory()->getPermissionTwigExtensionPlugins() as $extensionPlugin) {
            $twig->addExtension($extensionPlugin);
        }

        return $twig;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
