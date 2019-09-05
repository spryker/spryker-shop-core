<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Model;


use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface;

class QuoteApprovalToQuoteSynchronizer implements QuoteApprovalToQuoteSynchronizerInterface
{
    /**
     * @uses \Spryker\Shared\QuoteApproval\QuoteApprovalConfig::STATUS_WAITING
     */
    public const STATUS_WAITING = 'waiting';

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface $quoteClient
     */
    public function __construct(CartPageToCartClientInterface $cartClient, CartPageToQuoteClientInterface $quoteClient)
    {
        $this->cartClient = $cartClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     *
     * @return void
     */
    public function synchronizeQuoteApprovals(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer): void
    {
        $quoteTransfer = $this->cartClient->getQuote();

        $quoteApprovalTransfer = $quoteApprovalResponseTransfer->getQuoteApproval();
        if ($quoteApprovalTransfer && $quoteApprovalTransfer->getStatus() === static::STATUS_WAITING) {
            $this->addQuoteApprovalToQuote($quoteApprovalTransfer, $quoteTransfer);
        }

        if ($quoteApprovalTransfer && $quoteApprovalTransfer->getStatus() !== static::STATUS_WAITING) {
            $this->updateQuoteApprovalInQuote($quoteApprovalTransfer, $quoteTransfer);
        }

        if (!$quoteApprovalTransfer) {
            // @ToDo Zed does not return $quoteApprovalTransferafter deletion
            // NEED TO REFACTOR
            $this->removeQuoteApprovalFromQuote($quoteApprovalTransfer, $quoteTransfer);
        }

        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @param int $idQuoteApproval
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getCorrespondedQuoteApprovalIndexInSessionQuote(
        int $idQuoteApproval,
        QuoteTransfer $quoteTransfer
    ): ?int {
        foreach ($quoteTransfer->getQuoteApprovals() as $index => $quoteApprovalTransfer) {
            if ($quoteApprovalTransfer->getIdQuoteApproval() === $idQuoteApproval) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteApprovalToQuote(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $quoteTransfer->addQuoteApproval($quoteApprovalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateQuoteApprovalInQuote(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $correspondedQuoteApprovalIndexInSessionQuote = $this->getCorrespondedQuoteApprovalIndexInSessionQuote(
            $quoteApprovalTransfer->getIdQuoteApproval(),
            $quoteTransfer
        );
        if ($correspondedQuoteApprovalIndexInSessionQuote === null) {
            return;
        }

        $quoteTransfer->getQuoteApprovals()->offsetUnset($correspondedQuoteApprovalIndexInSessionQuote);
        $quoteTransfer->getQuoteApprovals()->offsetSet(
            $correspondedQuoteApprovalIndexInSessionQuote,
            $quoteApprovalTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function removeQuoteApprovalFromQuote(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $correspondedQuoteApprovalIndexInSessionQuote = $this->getCorrespondedQuoteApprovalIndexInSessionQuote(
            $quoteApprovalTransfer->getIdQuoteApproval(),
            $quoteTransfer
        );
        if ($correspondedQuoteApprovalIndexInSessionQuote === null) {
            return;
        }

        $quoteTransfer->getQuoteApprovals()->offsetUnset($correspondedQuoteApprovalIndexInSessionQuote);
    }
}
