<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CategoryImageStorageWidget\Dependency\CategoryImageStorageWidgetToCategoryImageStorageClientBridge;

class CategoryImageStorageWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CATEGORY_IMAGE_STORAGE = 'CLIENT_CATEGORY_IMAGE_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCategoryImageStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryImageStorageClient(Container $container): Container
    {
        $container[static::CLIENT_CATEGORY_IMAGE_STORAGE] = function (Container $container) {
            return new CategoryImageStorageWidgetToCategoryImageStorageClientBridge(
                $container->getLocator()->categoryImageStorage()->client()
            );
        };

        return $container;
    }
}
