<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class QuoteRequestAgentWidgetConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_CLOSED
     */
    public const STATUS_CLOSED = 'closed';

    protected const PAGINATION_DEFAULT_QUOTE_REQUESTS_PER_PAGE = 5;

    /**
     * @return int
     */
    public function getPaginationDefaultQuoteRequestsPerPage(): int
    {
        return static::PAGINATION_DEFAULT_QUOTE_REQUESTS_PER_PAGE;
    }

    /**
     * @return string[]
     */
    public function getExcludedStatuses(): array
    {
        return [
            static::STATUS_CLOSED,
        ];
    }
}
