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
    public const ROUTE_QUOTE_APPROVAL_CREATE = 'quote-approval-create';
    public const ROUTE_QUOTE_APPROVAL_REMOVE = 'quote-approval-remove';

    protected const PATTERN_ID = '\d+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addQuoteApprovalApproveRoute()
            ->addQuoteApprovalDeclineRoute()
            ->addCreateQuoteApprovalRoute()
            ->addRemoveQuoteApprovalRoute();
    }

    /**
     * @return $this
     */
    protected function addQuoteApprovalApproveRoute()
    {
        $this->createPostController('/quote-approval/approve/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_APPROVE, 'QuoteApprovalWidget', 'QuoteApproval', 'approve')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuoteApprovalDeclineRoute()
    {
        $this->createPostController('/quote-approval/decline/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_DECLINE, 'QuoteApprovalWidget', 'QuoteApproval', 'decline')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCreateQuoteApprovalRoute()
    {
        $this->createController('/quote-approval/create', static::ROUTE_QUOTE_APPROVAL_CREATE, 'QuoteApprovalWidget', 'QuoteApproval', 'createQuoteApproval')
            ->method('POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRemoveQuoteApprovalRoute()
    {
        $this->createController('/quote-approval/{idQuoteApproval}/remove', static::ROUTE_QUOTE_APPROVAL_REMOVE, 'QuoteApprovalWidget', 'QuoteApproval', 'removeQuoteApproval')
            ->assert('idQuoteApproval', static::PATTERN_ID)
            ->method('POST');

        return $this;
    }
}
