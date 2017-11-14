<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory;

/**
 * @method CmsBlockWidgetFactory getFactory()
 */
class CmsBlockTwigFunctionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) use ($app) {
                return $this->registerCmsBlockTwigFunction($twig, $app);
            })
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param Application $app
     *
     * @return \Twig_Environment
     */
    protected function registerCmsBlockTwigFunction(\Twig_Environment $twig, Application $app)
    {
        foreach ($this->getCmsBlockTwigFunctions($app) as $function)
        {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    function getCmsBlockTwigFunctions(Application $app)
    {
        $functions = [];
        foreach ($this->getFactory()->getTwigFunctionPlugins() as $twigFunction) {
            $functions = array_merge($functions, $twigFunction->getFunctions($app));
        }

        return $functions;
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
