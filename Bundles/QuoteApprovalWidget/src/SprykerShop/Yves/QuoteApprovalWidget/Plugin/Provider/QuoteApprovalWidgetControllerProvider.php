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
        $this->createController('/{quote-approval}/request/send', static::ROUTE_QUOTE_APPROVAL_REUQEST_SEND, 'QuoteApprovalWidget', 'QuoteApproveRequest', 'sendQuoteApproveRequest')
            ->assert('quote-approval', $this->getAllowedLocalesPattern() . 'quote-approval|quote-approval')
            ->value('quote-approval', 'quote-approval');

        return $this;
    }
}
