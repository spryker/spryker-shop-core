<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\BusinessOnBehalfWidget\Widget\BusinessOnBehalfStatusWidget;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\BusinessOnBehalfWidget\MenuItemBusinessOnBehalfWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\BusinessOnBehalfWidget\Widget\BusinessOnBehalfStatusWidget instead.
 *
 * @method \SprykerShop\Yves\BusinessOnBehalfWidget\BusinessOnBehalfWidgetFactory getFactory()
 */
class MenuItemBusinessOnBehalfWidgetPlugin extends AbstractWidgetPlugin implements MenuItemBusinessOnBehalfWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $widget = new BusinessOnBehalfStatusWidget();

        $this->parameters = $widget->getParameters();
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
     * - Returns the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@BusinessOnBehalfWidget/views/customer-page/change-company-user.twig';
    }
}
