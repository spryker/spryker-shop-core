<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class QuoteRequestWidgetConfig extends AbstractBundleConfig
{
    /**
     * @see \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_DETAILS
     */
    public const QUOTE_REQUEST_REDIRECT_URL = 'quote-request/details';
}
