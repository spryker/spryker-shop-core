<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\MultiCartWidget\MultiCartWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MultiCartWidgetPlugin extends AbstractWidgetPlugin implements MultiCartWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isButtonDisabled
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, $isButtonDisabled): void
    {
        $this
            ->addWidgets($this->getFactory()->getViewExtendWidgetPlugins())
            ->addParameter(
                'cart',
                $this->getFactory()->getMultiCartClient()->getDefaultCart()
            )
            ->addParameter('cartCollection', $this->getInactiveQuoteList())
            ->addParameter('isButtonDisabled', $isButtonDisabled)
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed());
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@MultiCartWidget/views/add-to-multi-cart/add-to-multi-cart.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getInactiveQuoteList(): array
    {
        $quoteCollectionTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection();

        $inActiveQuoteTransferList = [];
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault() && $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote())) {
                $inActiveQuoteTransferList[] = $quoteTransfer;
            }
        }

        return $inActiveQuoteTransferList;
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
