<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\BusinessOnBehalfWidget\MenuItemBusinessOnBehalfWidgetPluginInterface;

class MenuItemBusinessOnBehalfWidgetPlugin extends AbstractWidgetPlugin implements MenuItemBusinessOnBehalfWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(): void
    {
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
        return '@BusinessOnBehalfWidget/views/customer-page/change-company-user.twig';
    }
}
