<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CategoryWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CategoryWidget\Dependency\Client\CategoryWidgetToCategoryStorageClientBridge;

class CategoryWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCategoryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryStorageClient(Container $container)
    {
        $container[self::CLIENT_CATEGORY_STORAGE] = function (Container $container) {
            return new CategoryWidgetToCategoryStorageClientBridge($container->getLocator()->categoryStorage()->client());
        };

        return $container;
    }
}
