<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductWidgetDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_PRODUCT_RELATION_WIDGET_SUB_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS';
    const PLUGIN_CATALOG_PAGE_SUB_WIDGETS = 'PLUGIN_CATALOG_PAGE_SUB_WIDGETS';
    const PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS = 'PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS';
    const PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS = 'PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS';
    const PLUGIN_HOME_PAGE_SUB_WIDGETS = 'PLUGIN_HOME_PAGE_SUB_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductRelationWidgetSubWidgetPlugins($container);
        $container = $this->addCatalogPageSubWidgetPlugins($container);
        $container = $this->addCmsContentWidgetProductSubWidgetPlugins($container);
        $container = $this->addCmsContentWidgetProductGroupSubWidgetPlugins($container);
        $container = $this->addHomePageSubWidgetPlugins($container);

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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCatalogPageSubWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_CATALOG_PAGE_SUB_WIDGETS] = function (Container $container) {
            return $this->getCatalogPageSubWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsContentWidgetProductSubWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS] = function (Container $container) {
            return $this->getCmsContentWidgetProductSubWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsContentWidgetProductGroupSubWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS] = function (Container $container) {
            return $this->getCmsContentWidgetProductGroupSubWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addHomePageSubWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_HOME_PAGE_SUB_WIDGETS] = function (Container $container) {
            return $this->getHomePageSubWidgetPlugins($container);
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
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getCatalogPageSubWidgetPlugins(Container $container): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getCmsContentWidgetProductSubWidgetPlugins(Container $container): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getCmsContentWidgetProductGroupSubWidgetPlugins(Container $container): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getHomePageSubWidgetPlugins(Container $container): array
    {
        return [];
    }

}
