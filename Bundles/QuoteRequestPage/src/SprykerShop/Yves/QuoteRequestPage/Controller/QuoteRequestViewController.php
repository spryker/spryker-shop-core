<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Yves\Kernel\View\View;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestViewController extends QuoteRequestAbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $viewData = $this->executeIndexAction();

        return $this->view(
            $viewData,
            [],
            '@QuoteRequestPage/views/view-quote-request/view-quote-request.twig'
        );
    }

    /**
     * @return array
     */
    protected function executeIndexAction(): array
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestCollectionTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->findQuoteRequestCollectionByFilter((new QuoteRequestFilterTransfer())->setCompanyUser($companyUserTransfer));

        return [
            'quoteRequests' => $quoteRequestCollectionTransfer->getQuoteRequests(),
        ];
    }
}
