<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductGroupWidget\Widget\ProductGroupWidget;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\ProductGroupWidget\ProductGroupWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductGroupWidget\Widget\ProductGroupWidget instead.
 *
 * @method \SprykerShop\Yves\ProductGroupWidget\ProductGroupWidgetFactory getFactory()
 */
class ProductGroupWidgetPlugin extends AbstractWidgetPlugin implements ProductGroupWidgetPluginInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $template
     *
     * @return void
     */
    public function initialize($idProductAbstract, $template): void
    {
        $widget = new ProductGroupWidget($idProductAbstract);

        $this->parameters = $widget->getParameters();
        $this->addParameter('template', $template);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductGroupWidget/views/product-group-deprecated/product-group-deprecated.twig';
    }
}
