<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Widget;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\ConfigurableBundleCartNoteWidgetFactory getFactory()
 */
class ConfiguredBundleCartNoteFormWidget extends AbstractWidget
{
    protected const PARAMETER_CONFIGURABLE_BUNDLE_CART_NOTE_FORM = 'configurableBundleCartNoteForm';
    protected const PARAMETER_ID_QUOTE = 'idQuote';

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param int|null $idQuote
     */
    public function __construct(
        ConfiguredBundleTransfer $configuredBundleTransfer,
        ?int $idQuote = null
    ) {
        $this->addConfigurableBundleCartNoteFormParameter($configuredBundleTransfer);
        $this->addIdQuoteParameter($idQuote);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ConfiguredBundleCartNoteFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleCartNoteWidget/views/configured-bundle-cart-note-form/configured-bundle-cart-note-form.twig';
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
     * @param int|null $idQuote
     *
     * @return void
     */
    protected function addIdQuoteParameter(?int $idQuote): void
    {
        $this->addParameter(static::PARAMETER_ID_QUOTE, $idQuote);
    }
}
