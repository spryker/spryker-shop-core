<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestAbstractController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        foreach ($quoteRequestResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer);
        }
    }

    /**
     * @param string $quoteRequestReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequestTransferByReference(string $quoteRequestReference): QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setWithHidden(true);

        $quoteRequestTransfers = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer|null $quoteRequestTransfer */
        $quoteRequestTransfer = array_shift($quoteRequestTransfers);

        if (!$quoteRequestTransfer) {
            throw new NotFoundHttpException();
        }

        return $quoteRequestTransfer;
    }
}
