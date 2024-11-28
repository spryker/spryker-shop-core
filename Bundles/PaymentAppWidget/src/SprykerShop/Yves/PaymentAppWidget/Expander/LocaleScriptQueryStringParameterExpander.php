<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToLocaleClientInterface;

class LocaleScriptQueryStringParameterExpander implements ScriptQueryStringParameterExpanderInterface
{
    /**
     * @var string
     */
    protected const QUERY_PARAMETER_LOCALE = 'locale';

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToLocaleClientInterface
     */
    protected PaymentAppWidgetToLocaleClientInterface $localeClient;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToLocaleClientInterface $localeClient
     */
    public function __construct(PaymentAppWidgetToLocaleClientInterface $localeClient)
    {
        $this->localeClient = $localeClient;
    }

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
        if (isset($queryStringParameters[static::QUERY_PARAMETER_LOCALE])) {
            $queryStringParameters[static::QUERY_PARAMETER_LOCALE] = $this->localeClient->getCurrentLocale();
        }

        return $queryStringParameters;
    }
}
