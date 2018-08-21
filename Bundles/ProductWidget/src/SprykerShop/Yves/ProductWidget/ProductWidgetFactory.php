<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class ProductWidgetFactory extends AbstractFactory
{
    /**
     * @return string[]
     */
    public function getProductRelationWidgetSubWidgets(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGIN_PRODUCT_RELATION_WIDGET_SUB_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getCatalogPageSubWidgets(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGIN_CATALOG_PAGE_SUB_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getCmsContentWidgetProductWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_SUB_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getCmsContentWidgetProductGroupWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGIN_CMS_CONTENT_WIDGET_PRODUCT_GROUP_SUB_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getFeaturedProductSubWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGIN_HOME_PAGE_SUB_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getProductReplacementForWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGINS_PRODUCT_REPLACEMENT_FOR_WIDGET_SUB_WIDGET);
    }

    /**
     * @return string[]
     */
    public function getProductAlternativeWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductWidgetDependencyProvider::PLUGINS_PRODUCT_ALTERNATIVE_WIDGET_SUB_WIDGET);
    }
}
