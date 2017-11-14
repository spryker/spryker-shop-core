<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;

class CmsBlockWidgetDependencyProvider extends AbstractBundleDependencyProvider
{

    const TWIG_FUNCTION_PLUGINS = 'TWIG_FUNCTION_PLUGINS';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addTwigFunctionPlugins($container);

        return $container;
    }

    /**
     * @param $container
     *
     * @return mixed
     */
    protected function addTwigFunctionPlugins($container)
    {
        $container[static::TWIG_FUNCTION_PLUGINS] = function () {
            return $this->getTwigFunctionPlugins();
        };

        return $container;
    }

    /**
     * @return TwigFunctionPluginInterface[]
     */
    protected function getTwigFunctionPlugins()
    {
        return [];
    }

}
