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
    /**
     * @var string
     */
    protected const PARAMETER_QUOTE = 'quote';

    /**
     * @var string
     */
    protected const PARAMETER_ITEMS = 'items';

    /**
     * @var string
     */
    protected const PARAMETER_CONFIGURED_BUNDLES = 'configuredBundles';

    /**
     * @var string
     */
    protected const PARAMETER_IS_QUANTITY_CHANGEABLE = 'isQuantityChangeable';

    /**
     * @var string
     */
    protected const PARAMETER_NUMBER_FORMAT_CONFIG = 'numberFormatConfig';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer>|null $itemTransfers
     */
    public function __construct(QuoteTransfer $quoteTransfer, ?iterable $itemTransfers = [])
    {
        if ($itemTransfers === null || !count($itemTransfers)) {
            $itemTransfers = $quoteTransfer->getItems();
        }

        $itemTransfers = $this->mapItems($itemTransfers);

        $configuredBundleTransfers = $this->getFactory()
            ->createConfiguredBundleGrouper()
            ->getConfiguredBundles($quoteTransfer, $itemTransfers);

        $this->addItemsParameter($itemTransfers);
        $this->addQuoteParameter($quoteTransfer);
        $this->addConfiguredBundlesParameter($configuredBundleTransfers);
        $this->addIsQuantityChangeableParameter();
        $this->addNumberFormatConfigParameter();
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUOTE, $quoteTransfer);
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function addItemsParameter(iterable $itemTransfers): void
    {
        $this->addParameter(static::PARAMETER_ITEMS, $itemTransfers);
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ConfiguredBundleTransfer> $configuredBundleTransfers
     *
     * @return void
     */
    protected function addConfiguredBundlesParameter(iterable $configuredBundleTransfers): void
    {
        $this->addParameter(static::PARAMETER_CONFIGURED_BUNDLES, $configuredBundleTransfers);
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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
     * @return void
     */
    protected function addNumberFormatConfigParameter(): void
    {
        $numberFormatConfigTransfer = $this->getFactory()
            ->getUtilNumberService()
            ->getNumberFormatConfig(
                $this->getFactory()->getLocaleClient()->getCurrentLocale(),
            );

        $this->addParameter(static::PARAMETER_NUMBER_FORMAT_CONFIG, $numberFormatConfigTransfer->toArray());
    }
}
