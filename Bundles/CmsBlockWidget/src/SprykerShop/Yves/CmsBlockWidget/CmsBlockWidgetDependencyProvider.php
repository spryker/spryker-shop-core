<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientBridge;

class CmsBlockWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const TWIG_FUNCTION_PLUGINS = 'TWIG_FUNCTION_PLUGINS';
    const CLIENT_CMS_BLOCK_STORAGE = 'CLIENT_CMS_BLOCK_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addTwigFunctionPlugins($container);
        $container = $this->addCmsBlockStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigFunctionPlugins(Container $container)
    {
        $container[static::TWIG_FUNCTION_PLUGINS] = function () {
            return $this->getTwigFunctionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface[]
     */
    protected function getTwigFunctionPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsBlockStorageClient(Container $container)
    {
        $container[static::CLIENT_CMS_BLOCK_STORAGE] = function (Container $container) {
            return new CmsBlockWidgetToCmsBlockStorageClientBridge($container->getLocator()->cmsBlockStorage()->client());
        };

        return $container;
    }
}
