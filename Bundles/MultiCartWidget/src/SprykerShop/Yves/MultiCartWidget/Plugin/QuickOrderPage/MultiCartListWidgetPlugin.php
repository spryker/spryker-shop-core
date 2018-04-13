<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\MultiCartWidget\MultiCartListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MultiCartListWidgetPlugin extends AbstractWidgetPlugin implements MultiCartListWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this
            ->addParameter('carts', $this->getQuoteList())
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed());
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
        return '@MultiCartWidget/views/quick-order-page/quick-order-page.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getQuoteList(): array
    {
        $quoteCollectionTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection();

        $quoteTransferCollection = [];
        $defaultQuoteTransfer = $this->getFactory()->getMultiCartClient()->getDefaultCart();
        if ($this->can('WriteSharedCartPermissionPlugin', $defaultQuoteTransfer->getIdQuote())) {
            $quoteTransferCollection[] = $defaultQuoteTransfer;
        }
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault() && $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote())) {
                $quoteTransferCollection[] = $quoteTransfer;
            }
        }

        return $quoteTransferCollection;
    }

    /**
     * @return bool
     */
    protected function isMultiCartAllowed(): bool
    {
        return $this->getFactory()
            ->getMultiCartClient()
            ->isMultiCartAllowed();
    }
}
