<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router\QuoteApprovalWidgetRouteProviderPlugin` instead.
 */
class QuoteApprovalControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_QUOTE_APPROVAL_APPROVE = 'quote-approval-approve';
    protected const ROUTE_QUOTE_APPROVAL_DECLINE = 'quote-approval-decline';
    protected const ROUTE_QUOTE_APPROVAL_CREATE = 'quote-approval-create';
    protected const ROUTE_QUOTE_APPROVAL_REMOVE = 'quote-approval-remove';

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
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::approveAction()
     *
     * @return $this
     */
    protected function addQuoteApprovalApproveRoute()
    {
        $this->createPostController('/{quoteApproval}/approve/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_APPROVE, 'QuoteApprovalWidget', 'QuoteApproval', 'approve')
            ->assert('quoteApproval', $this->getAllowedLocalesPattern() . 'quote-approval|quote-approval')
            ->value('quoteApproval', 'quote-approval')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::declineAction()
     *
     * @return $this
     */
    protected function addQuoteApprovalDeclineRoute()
    {
        $this->createPostController('/{quoteApproval}/decline/{idQuoteApproval}', static::ROUTE_QUOTE_APPROVAL_DECLINE, 'QuoteApprovalWidget', 'QuoteApproval', 'decline')
            ->assert('quoteApproval', $this->getAllowedLocalesPattern() . 'quote-approval|quote-approval')
            ->value('quoteApproval', 'quote-approval')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::createQuoteApprovalAction()
     *
     * @return $this
     */
    protected function addCreateQuoteApprovalRoute()
    {
        $this->createPostController('/{quoteApproval}/create', static::ROUTE_QUOTE_APPROVAL_CREATE, 'QuoteApprovalWidget', 'QuoteApproval', 'createQuoteApproval')
            ->assert('quoteApproval', $this->getAllowedLocalesPattern() . 'quote-approval|quote-approval')
            ->value('quoteApproval', 'quote-approval');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::removeQuoteApprovalAction()
     *
     * @return $this
     */
    protected function addRemoveQuoteApprovalRoute()
    {
        $this->createPostController('/{quoteApproval}/{idQuoteApproval}/remove', static::ROUTE_QUOTE_APPROVAL_REMOVE, 'QuoteApprovalWidget', 'QuoteApproval', 'removeQuoteApproval')
            ->assert('quoteApproval', $this->getAllowedLocalesPattern() . 'quote-approval|quote-approval')
            ->value('quoteApproval', 'quote-approval')
            ->assert('idQuoteApproval', static::PATTERN_ID);

        return $this;
    }
}
