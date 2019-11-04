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
class ConfigurableBundleCartNoteFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     */
    public function __construct(ConfiguredBundleTransfer $configuredBundleTransfer)
    {
        $this->addConfigurableBundleCartNoteFormParameter($configuredBundleTransfer);
        $this->addQuoteParameter($configuredBundleTransfer);
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

        $this->addParameter('configurableBundleCartNoteForm', $cartNoteForm->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return void
     */
    protected function addQuoteParameter(ConfiguredBundleTransfer $configuredBundleTransfer): void
    {
        $this->addParameter('configuredBundle', $configuredBundleTransfer);
    }
}
