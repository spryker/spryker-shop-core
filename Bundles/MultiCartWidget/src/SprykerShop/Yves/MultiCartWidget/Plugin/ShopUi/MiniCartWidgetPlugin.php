<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Widget\MiniCartWidget;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\MultiCart\MiniCartWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\MultiCartWidget\Widget\MiniCartWidget instead.
 *
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MiniCartWidgetPlugin extends AbstractWidgetPlugin implements MiniCartWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param int $cartQuantity
     *
     * @return void
     */
    public function initialize($cartQuantity): void
    {
        $widget = new MiniCartWidget($cartQuantity);

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
        return MiniCartWidget::getTemplate();
    }
}
