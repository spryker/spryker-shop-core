<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Widget;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form\ConfigurableBundleCartNoteForm;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\ConfigurableBundleCartNoteWidgetFactory getFactory()
 */
class ConfigurableBundleCartNoteFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     */
    public function __construct(
        ConfiguredBundleTransfer $configuredBundleTransfer,
        ?QuoteTransfer $quoteTransfer = null
    ) {
        $this->addConfigurableBundleCartNoteFormParameter($configuredBundleTransfer);
        $this->addConfiguredBundleParameter($configuredBundleTransfer);
        $this->addQuoteParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ConfigurableBundleCartNoteFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleCartNoteWidget/views/configurable-bundle-cart-note-form/configurable-bundle-cart-note-form.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return void
     */
    protected function addConfigurableBundleCartNoteFormParameter(ConfiguredBundleTransfer $configuredBundleTransfer): void
    {
        $cartNoteForm = $this->getFactory()
            ->getConfigurableBundleCartNoteForm()
            ->setData($configuredBundleTransfer);

        $cartNoteForm
            ->get(ConfigurableBundleCartNoteForm::FIELD_CONFIGURABLE_BUNDLE_TEMPLATE_NAME)
            ->setData($configuredBundleTransfer->getTemplate()->getName());

        $this->addParameter('configurableBundleCartNoteForm', $cartNoteForm->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return void
     */
    protected function addConfiguredBundleParameter(ConfiguredBundleTransfer $configuredBundleTransfer): void
    {
        $this->addParameter('configuredBundle', $configuredBundleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteParameter(?QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer = $quoteTransfer ?: $this->getFactory()->getQuoteClient()->getQuote();

        $this->addParameter('quote', $quoteTransfer);
    }
}
