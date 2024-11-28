<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;

interface ScriptQueryStringParameterExpanderInterface
{
    /**
     * @param array<string, mixed> $queryStringParameters
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
     *
     * @return array<string, mixed>
     */
    public function expandQueryStringParameters(
        array $queryStringParameters,
        ExpressCheckoutPaymentMethodWidgetTransfer $expressCheckoutPaymentMethodWidgetTransfer
    ): array;
}
