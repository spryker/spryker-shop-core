<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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

}
