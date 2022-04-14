<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

/**
 * Provides extensibility for checkout payment methods collection.
 *
 * Use this plugin to add new payment forms to the subform collection using the provided payment methods.
 *
 * Do not use this plugin to remove an already existing subform from a collection.
 */
interface PaymentCollectionExtenderPluginInterface
{
    /**
     * Specification:
     * - Uses a list of payment methods to extend the collection of subform with new ones.
     * - Returns an extended collection of subforms.
     *
     * @api
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $paymentSubFormPluginCollection
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function extendCollection(
        SubFormPluginCollection $paymentSubFormPluginCollection,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): SubFormPluginCollection;
}
