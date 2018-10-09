<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockTwigFunctionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($app) {
                return $this->registerCmsBlockTwigFunction($twig, $app);
            })
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Silex\Application $app
     *
     * @return \Twig_Environment
     */
    protected function registerCmsBlockTwigFunction(Twig_Environment $twig, Application $app)
    {
        foreach ($this->getCmsBlockTwigFunctions($app) as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return array
     */
    protected function getCmsBlockTwigFunctions(Application $app)
    {
        $functions = [];
        foreach ($this->getFactory()->getTwigFunctionPlugins() as $twigFunction) {
            $functions = array_merge($functions, $twigFunction->getFunctions($app));
        }

        return $functions;
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
