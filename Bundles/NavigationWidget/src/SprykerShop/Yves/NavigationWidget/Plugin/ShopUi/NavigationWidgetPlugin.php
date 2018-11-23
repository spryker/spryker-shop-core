<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\NavigationWidget\Widget\NavigationWidget;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\NavigationWidget\NavigationWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\NavigationWidget\Widget\NavigationWidget instead.
 *
 * @method \SprykerShop\Yves\NavigationWidget\NavigationWidgetFactory getFactory()
 */
class NavigationWidgetPlugin extends AbstractWidgetPlugin implements NavigationWidgetPluginInterface
{
    /**
     * @param string $navigationKey
     * @param string $template
     *
     * @return void
     */
    public function initialize($navigationKey, $template): void
    {
        $navigationWidget = new NavigationWidget($navigationKey, $template);

        $this->parameters = $navigationWidget->getParameters();
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
        return NavigationWidget::getTemplate();
    }
}
