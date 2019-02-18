<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class AgentQuoteRequestWidgetConfig extends AbstractBundleConfig
{
    public const PAGINATION_DEFAULT_QUOTE_REQUESTS_PER_PAGE = 5;

    /**
     * @return int
     */
    public function getPaginationDefaultQuoteRequestsPerPage(): int
    {
        return static::PAGINATION_DEFAULT_QUOTE_REQUESTS_PER_PAGE;
    }
}
