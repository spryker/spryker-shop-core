<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\CurrencyWidget\CurrencyWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CurrencyWidget\Plugin\ShopUi\CurrencyWidget instead.
 */
class CurrencyWidgetPlugin extends AbstractWidgetPlugin implements CurrencyWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $widget = new CurrencyWidget();

        $this->parameters = $widget->getParameters();
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
        return CurrencyWidget::getTemplate();
    }
}
