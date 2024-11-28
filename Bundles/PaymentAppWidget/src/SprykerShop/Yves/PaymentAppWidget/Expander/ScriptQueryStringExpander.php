<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\ScriptTransfer;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;

class ScriptQueryStringExpander implements ScriptQueryStringExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig
     */
    protected PaymentAppWidgetConfig $paymentAppWidgetConfig;

    /**
     * @var array<\SprykerShop\Yves\PaymentAppWidget\Expander\ScriptQueryStringParameterExpanderInterface>
     */
    protected array $scriptQueryStringParameterExpanders;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig $paymentAppWidgetConfig
     * @param array<\SprykerShop\Yves\PaymentAppWidget\Expander\ScriptQueryStringParameterExpanderInterface> $scriptQueryStringParameterExpanders
     */
    public function __construct(
        PaymentAppWidgetConfig $paymentAppWidgetConfig,
        array $scriptQueryStringParameterExpanders
    ) {
        $this->paymentAppWidgetConfig = $paymentAppWidgetConfig;
        $this->scriptQueryStringParameterExpanders = $scriptQueryStringParameterExpanders;
    }

    /**
     * @param \Generated\Shared\Transfer\ScriptTransfer $scriptTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     * @param string $appPaymentMethodKey
     *
     * @return \Generated\Shared\Transfer\ScriptTransfer
     */
    public function expandScriptQueryStringParameters(
        ScriptTransfer $scriptTransfer,
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer,
        string $appPaymentMethodKey
    ): ScriptTransfer {
        $queryStringParameters = $this->executeScriptQueryStringParameterExpanders(
            $scriptTransfer->getQueryParams(),
            $expressCheckoutPaymentMethodWidgetTransfer,
        );

        $queryStringParameters = array_merge(
            $queryStringParameters,
            $this->paymentAppWidgetConfig->getScriptQueryStringParametersByAppPaymentMethodKey($appPaymentMethodKey),
        );

        return $scriptTransfer->setQueryParams($queryStringParameters);
    }

    /**
     * @param array<string, mixed> $queryStringParameters
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     *
     * @return array<string, mixed>
     */
    protected function executeScriptQueryStringParameterExpanders(
        array $queryStringParameters,
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
    ): array {
        foreach ($this->scriptQueryStringParameterExpanders as $scriptQueryStringParameterExpander) {
            $queryStringParameters = $scriptQueryStringParameterExpander->expandQueryStringParameters(
                $queryStringParameters,
                $expressCheckoutPaymentMethodWidgetTransfer,
            );
        }

        return $queryStringParameters;
    }
}
