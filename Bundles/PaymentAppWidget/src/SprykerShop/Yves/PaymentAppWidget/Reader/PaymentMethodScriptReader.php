<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use SprykerShop\Yves\PaymentAppWidget\Expander\ScriptQueryStringExpanderInterface;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;

class PaymentMethodScriptReader implements PaymentMethodScriptReaderInterface
{
    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig
     */
    protected PaymentAppWidgetConfig $paymentAppWidgetConfig;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Expander\ScriptQueryStringExpanderInterface
     */
    protected ScriptQueryStringExpanderInterface $scriptQueryStringExpander;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig $paymentAppWidgetConfig
     * @param \SprykerShop\Yves\PaymentAppWidget\Expander\ScriptQueryStringExpanderInterface $scriptQueryStringExpander
     */
    public function __construct(
        PaymentAppWidgetConfig $paymentAppWidgetConfig,
        ScriptQueryStringExpanderInterface $scriptQueryStringExpander
    ) {
        $this->paymentAppWidgetConfig = $paymentAppWidgetConfig;
        $this->scriptQueryStringExpander = $scriptQueryStringExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\ScriptTransfer>
     */
    public function getScriptsForPaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer,
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
    ): ArrayObject {
        $checkoutConfigurationTransfer = $paymentMethodTransfer->getPaymentMethodAppConfigurationOrFail()->getCheckoutConfigurationOrFail();
        $appPaymentMethodKey = $checkoutConfigurationTransfer->getAppPaymentMethodKeyOrFail();
        $scriptParameters = $this->paymentAppWidgetConfig->getScriptParametersByAppPaymentMethodKey($appPaymentMethodKey);

        foreach ($checkoutConfigurationTransfer->getScripts() as $scriptTransfer) {
            $scriptTransfer = $this->scriptQueryStringExpander->expandScriptQueryStringParameters(
                $scriptTransfer,
                $expressCheckoutPaymentMethodWidgetTransfer,
                $appPaymentMethodKey,
            );

            $scriptTransfer->setScriptParameters($scriptParameters);
        }

        return $checkoutConfigurationTransfer->getScripts();
    }
}
