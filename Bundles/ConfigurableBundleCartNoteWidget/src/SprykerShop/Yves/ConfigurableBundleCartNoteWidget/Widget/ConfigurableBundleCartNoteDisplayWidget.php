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
class ConfigurableBundleCartNoteDisplayWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     */
    public function __construct(ConfiguredBundleTransfer $configuredBundleTransfer)
    {
        $this->addConfiguredBundleParameter($configuredBundleTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ConfigurableBundleCartNoteDisplayWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleCartNoteWidget/views/configurable-bundle-cart-note-display/configurable-bundle-cart-note-display.twig';
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
}
