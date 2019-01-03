<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuoteApprovalWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_QUOTE_APPROVAL_REUQEST_SEND = 'quote-approval/request/send';
    public const ROUTE_QUOTE_APPROVAL_REUQEST_CANCEL = 'quote-approval/request/cancel';

    protected const PATTERN_ID = '\d+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addSendApproveRequestRoute();
    }

    /**
     * @return $this
     */
    protected function addSendApproveRequestRoute(): self
    {
        $this->createController('/quote-approval/request/send', static::ROUTE_QUOTE_APPROVAL_REUQEST_SEND, 'QuoteApprovalWidget', 'QuoteApproveRequest', 'sendQuoteApproveRequest')
            ->method('POST');

        $this->createController('/quote-approval/request/{idQuoteApproval}/cancel', static::ROUTE_QUOTE_APPROVAL_REUQEST_CANCEL, 'QuoteApprovalWidget', 'QuoteApproveRequest', 'cancelQuoteApprovalRequest')
            ->assert('idQuoteApproval', static::PATTERN_ID)
            ->method('GET');

        return $this;
    }
}
