<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Form\DataProvider;

use Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer;
use SprykerShop\Yves\PaymentAppWidget\Form\ExpressCheckoutForm;

class ExpressCheckoutFormDataProvider
{
    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function getOptions(array $options): array
    {
        return [
            'data_class' => InitializePreOrderPaymentRequestTransfer::class,
            ExpressCheckoutForm::OPTION_CSRF_TOKEN_NAME => $options[ExpressCheckoutForm::OPTION_CSRF_TOKEN_NAME] ?? null,
        ];
    }
}
