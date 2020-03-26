<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CompanyWidget\Widget\CompanyMenuItemWidget;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\CompanyWidget\MenuItemCompanyWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CompanyWidget\Widget\CompanyMenuItemWidget instead.
 */
class MenuItemCompanyWidgetPlugin extends AbstractWidgetPlugin implements MenuItemCompanyWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $widget = new CompanyMenuItemWidget();

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
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
    public static function getTemplate(): string
    {
        return CompanyMenuItemWidget::getTemplate();
    }
}
