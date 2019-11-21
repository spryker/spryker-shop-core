<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Widget;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\ConfigurableBundleCartNoteWidgetFactory getFactory()
 */
class ConfiguredBundleCartNoteWidget extends AbstractWidget
{
    protected const PARAMETER_CONFIGURABLE_BUNDLE_CART_NOTE_FORM = 'configurableBundleCartNoteForm';
    protected const PARAMETER_QUOTE = 'quote';

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     */
    public function __construct(
        ConfiguredBundleTransfer $configuredBundleTransfer,
        ?QuoteTransfer $quoteTransfer = null
    ) {
        $this->addConfigurableBundleCartNoteFormParameter($configuredBundleTransfer);
        $this->addQuoteParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ConfiguredBundleCartNoteWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleCartNoteWidget/views/configured-bundle-cart-note/configured-bundle-cart-note.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return void
     */
    protected function addConfigurableBundleCartNoteFormParameter(ConfiguredBundleTransfer $configuredBundleTransfer): void
    {
        $cartNoteForm = $this->getFactory()->getConfigurableBundleCartNoteForm($configuredBundleTransfer);
        $this->addParameter(static::PARAMETER_CONFIGURABLE_BUNDLE_CART_NOTE_FORM, $cartNoteForm->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteParameter(?QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer = $quoteTransfer ?: $this->getFactory()->getQuoteClient()->getQuote();
        $this->addParameter(static::PARAMETER_QUOTE, $quoteTransfer);
    }
}
