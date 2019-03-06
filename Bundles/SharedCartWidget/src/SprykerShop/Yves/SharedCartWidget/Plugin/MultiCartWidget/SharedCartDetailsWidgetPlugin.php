<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartDetailsWidgetPluginInterface;
use SprykerShop\Yves\SharedCartWidget\Widget\SharedCartDetailsWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\SharedCartDetailsWidget instead.
 *
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class SharedCartDetailsWidgetPlugin extends AbstractWidgetPlugin implements SharedCartDetailsWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $actions
     * @param string[]|null $widgetList
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, array $actions, ?array $widgetList = null): void
    {
        $widget = new SharedCartDetailsWidget($quoteTransfer, $actions, $widgetList);

        $this->parameters = $widget->getParameters();

        if ($widgetList) {
            $this->addWidgets($widgetList);
        }
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
        return SharedCartDetailsWidget::getTemplate();
    }
}
