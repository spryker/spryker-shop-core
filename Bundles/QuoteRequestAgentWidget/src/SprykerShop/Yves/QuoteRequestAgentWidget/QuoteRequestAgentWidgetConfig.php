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
     * @uses \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_CLOSED
     */
    public const STATUS_CLOSED = 'closed';

    /**
     * @api
     *
     * @return string[]
     */
    public function getExcludedOverviewStatuses(): array
    {
        return [
            static::STATUS_CLOSED,
        ];
    }
}
