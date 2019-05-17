<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryWidget\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use `SprykerShop\Yves\CategoryWidget\Plugin\Twig\CategoryTwigPlugin` instead.
 *
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
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $globals) use ($app) {
                return $this->getGlobalTemplateVariables($app) + $globals;
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
    }

    /**
     * @param \Silex\Application $app
     *
     * @return array
     */
    protected function getGlobalTemplateVariables(Application $app): array
    {
        return [
            'categories' => $this->getFactory()->getCategoryStorageClient()->getCategories($app['locale']),
        ];
    }
}
