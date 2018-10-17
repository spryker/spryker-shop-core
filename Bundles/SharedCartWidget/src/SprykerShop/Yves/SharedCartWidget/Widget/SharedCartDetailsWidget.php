<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class SharedCartDetailsWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $actions
     * @param string[]|null $widgetList
     */
    public function __construct(QuoteTransfer $quoteTransfer, array $actions, ?array $widgetList = null)
    {
        $this->addParameter('cart', $quoteTransfer)
            ->addParameter('actions', $this->checkActionsPermission($quoteTransfer, $actions));

        if ($widgetList) {
            /** @deprecated Use global widgets instead. */
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
        return $this->getFactory()->getSharedCartClient()->isQuoteDeletable($currentQuoteTransfer);
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
     * @return string
     */
    public static function getName(): string
    {
        return 'SharedCartDetailsWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-details/shared-cart-details.twig';
    }
}
