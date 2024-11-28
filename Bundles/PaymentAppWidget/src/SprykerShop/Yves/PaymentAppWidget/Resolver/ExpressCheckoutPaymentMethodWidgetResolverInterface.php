<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Resolver;

interface ExpressCheckoutPaymentMethodWidgetResolverInterface
{
    /**
     * @return list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>
     */
    public function getExpressCheckoutPaymentMethodWidgets(): array;
}
