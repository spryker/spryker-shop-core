<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuoteApprovalControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_QUOTE_APPROVAL_APPROVE = 'quote-approval-approve';
    public const ROUTE_QUOTE_APPROVAL_DECLINE = 'quote-approval-decline';
    public const ROUTE_QUOTE_APPROVAL_CANCEL = 'quote-approval-cancel';

    protected const PATTERN_ID = '\d+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addQuoteApprovalRoute();
    }

    /**
     * @return $this
     */
    protected function addQuoteApprovalRoute()
    {
        $this->createPostController('/quote-approval/approve/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_APPROVE, 'QuoteApprovalWidget', 'QuoteApproval', 'approve')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        $this->createPostController('/quote-approval/decline/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_DECLINE, 'QuoteApprovalWidget', 'QuoteApproval', 'decline')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        $this->createPostController('/quote-approval/cancel/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_CANCEL, 'QuoteApprovalWidget', 'QuoteApproval', 'cancel')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        return $this;
    }
}
