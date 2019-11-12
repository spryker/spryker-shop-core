<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class QuoteConfiguredBundleWidget extends AbstractWidget
{
    protected const PARAMETER_ITEMS = 'items';
    protected const PARAMETER_CONFIGURED_BUNDLES = 'configuredBundles';
    protected const PARAMETER_IS_QUANTITY_CHANGEABLE = 'isQuantityChangeable';
    protected const PARAMETER_CURRENCY_ISO_CODE = 'currencyIsoCode';
    protected const PARAMETER_PRICE_MODE = 'priceMode';
    protected const PARAMETER_ID_QUOTE = 'idQuote';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[]|null $itemTransfers
     */
    public function __construct(QuoteTransfer $quoteTransfer, ?iterable $itemTransfers = [])
    {
        if (!count($itemTransfers)) {
            $itemTransfers = $quoteTransfer->getItems();
        }

        $itemTransfers = $this->mapItems($itemTransfers);

        $configuredBundleTransfers = $this->getFactory()
            ->createConfiguredBundleGrouper()
            ->getConfiguredBundles($quoteTransfer, $itemTransfers);

        $this->addItemsParameter($itemTransfers);
        $this->addConfiguredBundlesParameter($configuredBundleTransfers);
        $this->addIsQuantityChangeableParameter();
        $this->addCurrencyIsoCodeParameter($quoteTransfer);
        $this->addPriceModeParameter($quoteTransfer);
        $this->addIdQuoteParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteConfiguredBundleWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleWidget/views/quote-configured-bundle-widget/quote-configured-bundle-widget.twig';
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addItemsParameter(iterable $itemTransfers): void
    {
        $this->addParameter(static::PARAMETER_ITEMS, $itemTransfers);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ConfiguredBundleTransfer[] $configuredBundleTransfers
     *
     * @return void
     */
    protected function addConfiguredBundlesParameter(iterable $configuredBundleTransfers): void
    {
        $this->addParameter(static::PARAMETER_CONFIGURED_BUNDLES, $configuredBundleTransfers);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapItems(iterable $itemTransfers): array
    {
        $items = [];

        foreach ($itemTransfers as $itemTransfer) {
            $items[$itemTransfer->getGroupKey()] = $itemTransfer;
        }

        return $items;
    }

    /**
     * @return void
     */
    protected function addIsQuantityChangeableParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_QUANTITY_CHANGEABLE, $this->getConfig()->isQuantityChangeable());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCurrencyIsoCodeParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_CURRENCY_ISO_CODE,
            $quoteTransfer->getCurrency() ? $quoteTransfer->getCurrency()->getCode() : null
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addPriceModeParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRICE_MODE, $quoteTransfer->getPriceMode());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIdQuoteParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_ID_QUOTE, $quoteTransfer->getIdQuote());
    }
}
