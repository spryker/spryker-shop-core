<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Widget\AddToMultiCartWidget;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\MultiCartWidget\MultiCartWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\MultiCartWidget\Widget\AddToMultiCartWidget instead.
 *
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MultiCartWidgetPlugin extends AbstractWidgetPlugin implements MultiCartWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $disabled
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, $disabled): void
    {
        $widget = new AddToMultiCartWidget($disabled);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getViewExtendWidgetPlugins());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return AddToMultiCartWidget::getTemplate();
    }
}
