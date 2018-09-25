<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartDetailsWidgetPluginInterface;

/**
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
    protected function checkActionsPermission(QuoteTransfer $quoteTransfer, array $actions): array
    {
        $writeAllowed = $this->can(WriteSharedCartPermissionPlugin::KEY, $quoteTransfer->getIdQuote());
        $viewAllowed = $this->can(ReadSharedCartPermissionPlugin::KEY, $quoteTransfer->getIdQuote()) || $writeAllowed;
        $deleteAllowed = $this->isDeleteCartAllowed($quoteTransfer) && $writeAllowed;
        $actions['view'] = isset($actions['view']) ? $actions['view'] && $viewAllowed : $viewAllowed;
        $actions['update'] = isset($actions['update']) ? $actions['update'] && $writeAllowed : $writeAllowed;
        $actions['set_default'] = isset($actions['set_default']) ? $actions['set_default'] && $viewAllowed : $viewAllowed;
        $actions['delete'] = isset($actions['delete']) ? $actions['delete'] && $deleteAllowed : $deleteAllowed;

        return $actions;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     *
     * @return bool
     */
    protected function isDeleteCartAllowed(QuoteTransfer $currentQuoteTransfer): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $ownedQuoteNumber = 0;
        foreach ($this->getFactory()->getMultiCartClient()->getQuoteCollection()->getQuotes() as $quoteTransfer) {
            if ($this->isQuoteOwner($quoteTransfer, $customerTransfer)) {
                $ownedQuoteNumber++;
            }
        }

        return $ownedQuoteNumber > 1 || (!$this->isQuoteOwner($currentQuoteTransfer, $customerTransfer) && $ownedQuoteNumber > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        return strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0;
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
        return '@SharedCartWidget/views/shared-cart-details/shared-cart-details.twig';
    }
}
