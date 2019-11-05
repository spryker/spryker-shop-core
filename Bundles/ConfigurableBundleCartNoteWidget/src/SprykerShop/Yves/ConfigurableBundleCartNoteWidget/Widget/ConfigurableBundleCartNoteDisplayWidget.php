<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Widget;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\ConfigurableBundleCartNoteWidgetFactory getFactory()
 */
class ConfigurableBundleCartNoteDisplayWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     */
    public function __construct(SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer)
    {
        $this->addConfiguredBundleParameter($salesOrderConfiguredBundleTransfer);
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
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return void
     */
    protected function addConfiguredBundleParameter(SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer): void
    {
        $this->addParameter('salesOrderConfiguredBundle', $salesOrderConfiguredBundleTransfer);
    }
}
