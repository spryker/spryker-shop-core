<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductColorGroupWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class ProductColorGroupWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductColorGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    public function getProductViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductColorGroupWidgetDependencyProvider::PLUGIN_PRODUCT_VIEW_EXPANDERS);
    }
}
