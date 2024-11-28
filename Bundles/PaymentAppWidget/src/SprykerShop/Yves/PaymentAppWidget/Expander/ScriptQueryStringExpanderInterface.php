<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\ScriptTransfer;

interface ScriptQueryStringExpanderInterface
{
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
    ): ScriptTransfer;
}
