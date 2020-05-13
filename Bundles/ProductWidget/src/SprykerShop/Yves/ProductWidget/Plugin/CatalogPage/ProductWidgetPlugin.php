<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Plugin\CatalogPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CatalogPage\Dependency\Plugin\ProductWidget\ProductWidgetPluginInterface;
use SprykerShop\Yves\ProductWidget\Widget\CatalogPageProductWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductWidget\Widget\CatalogPageProductWidget instead.
 *
 * @method \SprykerShop\Yves\ProductWidget\ProductWidgetFactory getFactory()
 */
class ProductWidgetPlugin extends AbstractWidgetPlugin implements ProductWidgetPluginInterface
{
    /**
     * @param mixed[] $product
     * @param string|null $viewMode
     *
     * @return void
     */
    public function initialize(array $product, $viewMode = null): void
    {
        $widget = new CatalogPageProductWidget($product, $viewMode);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getCatalogPageSubWidgets());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return CatalogPageProductWidget::getTemplate();
    }
}
