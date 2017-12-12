<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CategoryWidget\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CategoryWidget\CategoryWidgetFactory getFactory()
 */
class CategoryServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->addGlobalTemplateVariable($app, [
            'categories' => $this->getFactory()->getCategoryStorageClient()->getCategories($app['locale']),
        ]);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     * @param array $globalTemplateVariables
     *
     * @return void
     */
    protected function addGlobalTemplateVariable(Application $app, array $globalTemplateVariables)
    {
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $variables) use ($globalTemplateVariables) {
                return array_merge($variables, $globalTemplateVariables);
            })
        );
    }

}
