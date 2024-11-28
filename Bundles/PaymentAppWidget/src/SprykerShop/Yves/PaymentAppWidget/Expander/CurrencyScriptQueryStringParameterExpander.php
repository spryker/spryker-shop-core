<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;

class CurrencyScriptQueryStringParameterExpander implements ScriptQueryStringParameterExpanderInterface
{
    /**
     * @var string
     */
    protected const QUERY_PARAMETER_CURRENCY = 'currency';

    /**
     * @param array<string, mixed> $queryStringParameters
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     *
     * @return array<string, mixed>
     */
    public function expandQueryStringParameters(
        array $queryStringParameters,
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
    ): array {
        if (isset($queryStringParameters[static::QUERY_PARAMETER_CURRENCY])) {
            $queryStringParameters[static::QUERY_PARAMETER_CURRENCY] = $expressCheckoutPaymentMethodWidgetTransfer
                ->getQuoteOrFail()
                ->getCurrencyOrFail()
                ->getCodeOrFail();
        }

        return $queryStringParameters;
    }
}
