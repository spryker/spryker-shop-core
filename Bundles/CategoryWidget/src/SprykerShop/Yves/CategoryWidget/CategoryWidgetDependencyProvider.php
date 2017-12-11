<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CategoryWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CategoryWidget\Dependency\Client\CategoryWidgetToCategoryExporterClientBridge;

class CategoryWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CATEGORY_EXPORTER = 'CLIENT_CATEGORY_EXPORTER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCategoryExporterClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryExporterClient(Container $container)
    {
        $container[self::CLIENT_CATEGORY_EXPORTER] = function (Container $container) {
            return new CategoryWidgetToCategoryExporterClientBridge($container->getLocator()->categoryExporter()->client());
        };

        return $container;
    }
}
