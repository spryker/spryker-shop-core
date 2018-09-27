<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_PRODUCT_RELATION_WIDGET_SUB_WIDGETS = 'PLUGIN_PRODUCT_DETAIL_PAGE_SIMILAR_PRODUCTS_WIDGETS';
    public const PLUGIN_CATALOG_PAGE_SUB_WIDGETS = 'PLUGIN_CATALOG_PAGE_SUB_WIDGETS';
    public const PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS = 'PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS';
    public const PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS = 'PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS';
    public const PLUGIN_HOME_PAGE_SUB_WIDGETS = 'PLUGIN_HOME_PAGE_SUB_WIDGETS';
    public const PLUGINS_PRODUCT_REPLACEMENT_FOR_WIDGET_SUB_WIDGET = 'PLUGINS_PRODUCT_REPLACEMENT_FOR_WIDGET_SUB_WIDGET';
    public const PLUGINS_PRODUCT_ALTERNATIVE_WIDGET_SUB_WIDGET = 'PLUGINS_PRODUCT_ALTERNATIVE_WIDGET_SUB_WIDGET';

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
        $container = $this->addProductReplacementForWidgetPlugins($container);
        $container = $this->addProductAlternativeWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductRelationWidgetSubWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_RELATION_WIDGET_SUB_WIDGETS] = function () {
            return $this->getProductRelationWidgetSubWidgetPlugins();
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
        $container[self::PLUGIN_CATALOG_PAGE_SUB_WIDGETS] = function () {
            return $this->getCatalogPageSubWidgetPlugins();
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
        $container[self::PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS] = function () {
            return $this->getCmsContentWidgetProductSubWidgetPlugins();
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
        $container[self::PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS] = function () {
            return $this->getCmsContentWidgetProductGroupSubWidgetPlugins();
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
        $container[self::PLUGIN_HOME_PAGE_SUB_WIDGETS] = function () {
            return $this->getHomePageSubWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductReplacementForWidgetPlugins(Container $container): Container
    {
        $container[self::PLUGINS_PRODUCT_REPLACEMENT_FOR_WIDGET_SUB_WIDGET] = function () {
            return $this->getProductReplacementForWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductAlternativeWidgetPlugins(Container $container): Container
    {
        $container[self::PLUGINS_PRODUCT_ALTERNATIVE_WIDGET_SUB_WIDGET] = function () {
            return $this->getProductAlternativeWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getProductRelationWidgetSubWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getCatalogPageSubWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getCmsContentWidgetProductSubWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getCmsContentWidgetProductGroupSubWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getHomePageSubWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getProductReplacementForWidgetPlugins(): array
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getProductAlternativeWidgetPlugins(): array
    {
        return [];
    }
}
