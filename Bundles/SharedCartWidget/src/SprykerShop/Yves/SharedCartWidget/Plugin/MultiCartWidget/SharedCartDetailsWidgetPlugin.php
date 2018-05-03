<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartDetailsWidgetPluginInterface;

class SharedCartDetailsWidgetPlugin extends AbstractWidgetPlugin implements SharedCartDetailsWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $actions
     * @param null|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[] $widgetList
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, array $actions, ?array $widgetList = null): void
    {
        $this
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('actions', $this->checkActionsPermission($quoteTransfer, $actions));
        if ($widgetList) {
            $this->addWidgets($widgetList);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $actions
     *
     * @return array
     */
    protected function checkActionsPermission(QuoteTransfer $quoteTransfer, array $actions)
    {
        $writeAllowed = $this->can(WriteSharedCartPermissionPlugin::KEY, $quoteTransfer->getIdQuote());
        $viewAllowed = $this->can(ReadSharedCartPermissionPlugin::KEY, $quoteTransfer->getIdQuote()) || $writeAllowed;
        $actions['view'] = isset($actions['view']) ? $actions['view'] && $viewAllowed : $viewAllowed;
        $actions['update'] = isset($actions['update']) ? $actions['update'] && $writeAllowed : $writeAllowed;
        $actions['set_default'] = isset($actions['set_default']) ? $actions['set_default'] && $viewAllowed : $viewAllowed;
        $actions['delete'] = isset($actions['delete']) ? $actions['delete'] && $writeAllowed : $writeAllowed;

        return $actions;
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
        return '@SharedCartWidget/views/multi-cart-widget/shared-cart-details.twig';
    }
}
