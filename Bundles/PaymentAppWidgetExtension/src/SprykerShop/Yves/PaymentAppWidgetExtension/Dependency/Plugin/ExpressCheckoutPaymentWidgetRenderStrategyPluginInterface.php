<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;

/**
 * Provides the template and data for rendering express checkout payment methods.
 */
interface ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for a payment method.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    public function isApplicable(
        PaymentMethodTransfer $paymentMethodTransfer
    ): bool;

    /**
     * Specification:
     * - Returns express checkout payment method widget with template path and data for the rendering.
     * - The given template `ExpressCheckoutPaymentMethodTemplateTransfer` contains the default values.
     * - The `ExpressCheckoutPaymentMethodWidgetTransfer.paymentMethod` contains the payment method.
     * - The `ExpressCheckoutPaymentMethodWidgetTransfer.redirectUrls` is a list of URLs that are used to point to the handling controllers.
     * - The `ExpressCheckoutPaymentMethodWidgetTransfer.csrfToken` and `ExpressCheckoutPaymentMethodTemplateTransfer.csrfTokenName` are used to protect the form from CSRF attacks.
     * - The `ExpressCheckoutPaymentMethodWidgetTransfer.quote` contains the current quote data.
     * - The `ExpressCheckoutPaymentMethodWidgetTransfer.moduleName`, `ExpressCheckoutPaymentMethodWidgetTransfer.templateType`, and `ExpressCheckoutPaymentMethodWidgetTransfer.templateName` has to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer
     */
    public function getExpressCheckoutPaymentMethodWidget(
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
    ): ExpressCheckoutPaymentMethodWidgetTransfer;
}
