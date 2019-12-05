<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Widget;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleNoteWidget\ConfigurableBundleNoteWidgetFactory getFactory()
 */
class ConfiguredBundleNoteWidget extends AbstractWidget
{
    protected const PARAMETER_CONFIGURED_BUNDLE = 'configuredBundle';
    protected const PARAMETER_CONFIGURABLE_BUNDLE_NOTE_FORM = 'configurableBundleNoteForm';
    protected const PARAMETER_QUOTE = 'quote';

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     */
    public function __construct(
        ConfiguredBundleTransfer $configuredBundleTransfer,
        ?QuoteTransfer $quoteTransfer = null
    ) {
        $this->addConfiguredBundleParameter($configuredBundleTransfer);
        $this->addConfigurableBundleNoteFormParameter($configuredBundleTransfer);
        $this->addQuoteParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ConfiguredBundleNoteWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleNoteWidget/views/configured-bundle-note/configured-bundle-note.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return void
     */
    protected function addConfiguredBundleParameter(ConfiguredBundleTransfer $configuredBundleTransfer): void
    {
        $this->addParameter(static::PARAMETER_CONFIGURED_BUNDLE, $configuredBundleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return void
     */
    protected function addConfigurableBundleNoteFormParameter(ConfiguredBundleTransfer $configuredBundleTransfer): void
    {
        $noteForm = $this->getFactory()->getConfigurableBundleNoteForm($configuredBundleTransfer);
        $this->addParameter(static::PARAMETER_CONFIGURABLE_BUNDLE_NOTE_FORM, $noteForm->createView());
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
