<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Widget;

use Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class QuoteConfiguredBundleWidget extends AbstractWidget
{
    protected const PARAMETER_QUOTE = 'quote';
    protected const PARAMETER_CONFIGURED_BUNDLES = 'configuredBundles';
    protected const PARAMETER_IS_QUANTITY_CHANGEABLE = 'isQuantityChangeable';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $configuredBundleCollectionTransfer = $this->getFactory()
            ->createConfiguredBundleMapper()
            ->mapQuoteToConfiguredBundles($quoteTransfer);

        $this->addQuoteParameter($quoteTransfer);
        $this->addConfiguredBundlesParameter($configuredBundleCollectionTransfer);
        $this->addIsQuantityChangeableParameter();
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
     * @param \Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer $configuredBundleCollectionTransfer
     *
     * @return void
     */
    protected function addConfiguredBundlesParameter(ConfiguredBundleCollectionTransfer $configuredBundleCollectionTransfer): void
    {
        $this->addParameter(static::PARAMETER_CONFIGURED_BUNDLES, $configuredBundleCollectionTransfer->getConfiguredBundles());
    }

    /**
     * @return void
     */
    protected function addIsQuantityChangeableParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_QUANTITY_CHANGEABLE, $this->getConfig()->isQuantityChangeable());
    }
}
