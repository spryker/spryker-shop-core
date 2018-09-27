<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerPage\CustomerNavigationWidgetPluginInterface;
use SprykerShop\Yves\CustomerPage\Widget\CustomerNavigationWidget;

/**
 * @deprecated Use \SprykerShop\Yves\CustomerPage\Widget\CustomerNavigationWidget instead.
 *
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerNavigationWidgetPlugin extends AbstractWidgetPlugin implements CustomerNavigationWidgetPluginInterface
{
    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     *
     * @return void
     */
    public function initialize(string $activePage, ?int $activeEntityId = null): void
    {
        $widget = new CustomerNavigationWidget($activePage, $activeEntityId);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getCustomerMenuItemWidgetPlugins());
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
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
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return CustomerNavigationWidget::getTemplate();
    }
}
