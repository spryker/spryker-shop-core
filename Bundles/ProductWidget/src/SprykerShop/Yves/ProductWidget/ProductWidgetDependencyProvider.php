<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductGroupWidget\Plugin\ProductWidget\ProductGroupWidgetPlugin;
use SprykerShop\Yves\ProductLabelWidget\Plugin\ProductWidget\ProductAbstractLabelWidgetPlugin;
use SprykerShop\Yves\ProductReviewWidget\Plugin\ProductWidget\ProductReviewWidgetPlugin;

class ProductWidgetDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_PRODUCT_RELATION_WIDGET_SUB_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductRelationWidgetSubWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductRelationWidgetSubWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_RELATION_WIDGET_SUB_WIDGETS] = function (Container $container) {
            return $this->getProductRelationWidgetSubWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getProductRelationWidgetSubWidgetPlugins(Container $container): array
    {
        // TODO: move this to project level
        return [
            ProductAbstractLabelWidgetPlugin::class,
            ProductGroupWidgetPlugin::class,
            ProductReviewWidgetPlugin::class,
        ];
    }

}
