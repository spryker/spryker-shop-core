<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Reader;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;

interface ExpressCheckoutPaymentMethodWidgetReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
     *
     * @return list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>
     */
    public function getExpressCheckoutPaymentMethodWidgets(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
    ): array;
}
