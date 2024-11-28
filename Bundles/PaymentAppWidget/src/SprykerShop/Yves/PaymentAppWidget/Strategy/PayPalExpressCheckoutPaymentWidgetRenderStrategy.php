<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Strategy;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;
use SprykerShop\Yves\PaymentAppWidget\Reader\PaymentMethodScriptReaderInterface;
use SprykerShop\Yves\PaymentAppWidgetExtension\Dependency\Plugin\ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface;

class PayPalExpressCheckoutPaymentWidgetRenderStrategy implements ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface
{
    /**
     * @var string
     */
    protected const MODULE_NAME_PAYPAL_PAYMENT_APP = 'PaymentAppWidget';

    /**
     * @var string
     */
    protected const TEMPLATE_TYPE_MOLECULE = 'molecule';

    /**
     * @var string
     */
    protected const TEMPLATE_NAME_PAYPAL_BUTTONS = 'paypal-buttons';

    /**
     * @var string
     */
    protected const APP_PAYMENT_METHOD_KEY_PAYPAL_EXPRESS = 'paypal-express';

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig
     */
    protected PaymentAppWidgetConfig $paymentAppWidgetConfig;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Reader\PaymentMethodScriptReaderInterface
     */
    protected PaymentMethodScriptReaderInterface $paymentMethodScriptReader;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig $paymentAppWidgetConfig
     * @param \SprykerShop\Yves\PaymentAppWidget\Reader\PaymentMethodScriptReaderInterface $paymentMethodScriptReader
     */
    public function __construct(
        PaymentAppWidgetConfig $paymentAppWidgetConfig,
        PaymentMethodScriptReaderInterface $paymentMethodScriptReader
    ) {
        $this->paymentAppWidgetConfig = $paymentAppWidgetConfig;
        $this->paymentMethodScriptReader = $paymentMethodScriptReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    public function isApplicable(
        PaymentMethodTransfer $paymentMethodTransfer
    ): bool {
        $paymentMethodAppConfiguration = $paymentMethodTransfer->getPaymentMethodAppConfiguration();
        if (!$paymentMethodAppConfiguration || !$paymentMethodAppConfiguration->getCheckoutConfiguration()) {
            return false;
        }

        $checkoutConfiguration = $paymentMethodAppConfiguration->getCheckoutConfigurationOrFail();

        return $checkoutConfiguration->getStrategy() === $this->paymentAppWidgetConfig::CHECKOUT_CONFIGURATION_STRATEGY_EXPRESS_CHECKOUT
            && $checkoutConfiguration->getAppPaymentMethodKey() === static::APP_PAYMENT_METHOD_KEY_PAYPAL_EXPRESS;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer
     */
    public function getExpressCheckoutPaymentMethodWidget(
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
    ): ExpressCheckoutPaymentMethodWidgetTransfer {
        $paymentMethodTransfer = $expressCheckoutPaymentMethodWidgetTransfer->getPaymentMethodOrFail();
        $scriptTransfers = $this->paymentMethodScriptReader->getScriptsForPaymentMethod(
            $paymentMethodTransfer,
            $expressCheckoutPaymentMethodWidgetTransfer,
        );

        $paymentMethodTransfer->getPaymentMethodAppConfigurationOrFail()->getCheckoutConfigurationOrFail()->setScripts($scriptTransfers);

        return $expressCheckoutPaymentMethodWidgetTransfer
            ->setModuleName(static::MODULE_NAME_PAYPAL_PAYMENT_APP)
            ->setTemplateType(static::TEMPLATE_TYPE_MOLECULE)
            ->setTemplateName(static::TEMPLATE_NAME_PAYPAL_BUTTONS);
    }
}
