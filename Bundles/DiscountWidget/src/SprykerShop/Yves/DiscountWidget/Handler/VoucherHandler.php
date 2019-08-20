<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Handler;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientInterface;

/**
 * @deprecated Use CartCode + CartCodeWidget modules instead.
 */
class VoucherHandler extends BaseHandler implements VoucherHandlerInterface
{
    protected const GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED = 'cart.locked.change_denied';

    /**
     * @var \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientInterface $quoteClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(
        DiscountWidgetToCalculationClientInterface $calculationClient,
        DiscountWidgetToQuoteClientInterface $quoteClient,
        FlashMessengerInterface $flashMessenger
    ) {
        parent::__construct($flashMessenger);
        $this->calculationClient = $calculationClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param string $voucherCode
     *
     * @return void
     */
    public function add($voucherCode)
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($this->quoteClient->isQuoteLocked($quoteTransfer)) {
             $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED);

             return;
        }

        $voucherDiscount = new DiscountTransfer();
        $voucherDiscount->setVoucherCode($voucherCode);

        $quoteTransfer->addVoucherDiscount($voucherDiscount);

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->addFlashMessages($quoteTransfer, $voucherCode);

        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return void
     */
    protected function addFlashMessages($quoteTransfer, $voucherCode)
    {
        if ($this->isVoucherFromPromotionDiscount($quoteTransfer, $voucherCode)) {
            return;
        }

        if ($this->isVoucherCodeApplied($quoteTransfer, $voucherCode)) {
            $this->setFlashMessagesFromLastZedRequest($this->calculationClient);

            return;
        }

        $this->flashMessenger->addErrorMessage('cart.voucher.apply.failed');
    }

    /**
     * @param string $voucherCode
     *
     * @return void
     */
    public function remove($voucherCode)
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($this->quoteClient->isQuoteLocked($quoteTransfer)) {
            $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED);

            return;
        }

        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();
        $this->unsetVoucherCode($voucherCode, $voucherDiscounts);
        $this->unsetNotAppliedVoucherCode($voucherCode, $quoteTransfer);

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        $this->setFlashMessagesFromLastZedRequest($this->calculationClient);
        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function clear()
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($this->quoteClient->isQuoteLocked($quoteTransfer)) {
            $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED);

            return;
        }

        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->setUsedNotAppliedVoucherCodes([]);

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        $this->setFlashMessagesFromLastZedRequest($this->calculationClient);
        $this->quoteClient->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return bool
     */
    protected function isVoucherCodeApplied(QuoteTransfer $quoteTransfer, $voucherCode)
    {
        foreach ($quoteTransfer->getVoucherDiscounts() as $discountTransfer) {
            if ($discountTransfer->getVoucherCode() === $voucherCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * @deprecated
     *
     * @param string $voucherCode
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function unsetNotAppliedVoucherCode(string $voucherCode, QuoteTransfer $quoteTransfer): void
    {
        $usedNotAppliedVoucherCodeResultList = array_filter(
            $quoteTransfer->getUsedNotAppliedVoucherCodes(),
            function ($usedNotAppliedVoucherCode) use ($voucherCode) {
                return $usedNotAppliedVoucherCode != $voucherCode;
            }
        );

        $quoteTransfer->setUsedNotAppliedVoucherCodes($usedNotAppliedVoucherCodeResultList);
    }

    /**
     * @param string $voucherCode
     * @param \ArrayObject|\Generated\Shared\Transfer\DiscountTransfer[] $voucherDiscounts
     *
     * @return void
     */
    protected function unsetVoucherCode($voucherCode, ArrayObject $voucherDiscounts)
    {
        $discountIterator = $voucherDiscounts->getIterator();
        foreach ($discountIterator as $key => $voucherDiscountTransfer) {
            if ($voucherDiscountTransfer->getVoucherCode() === $voucherCode) {
                $discountIterator->offsetUnset($key);
            }

            if (!$discountIterator->valid()) {
                break;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return bool
     */
    protected function isVoucherFromPromotionDiscount(QuoteTransfer $quoteTransfer, $voucherCode)
    {
        foreach ($quoteTransfer->getUsedNotAppliedVoucherCodes() as $voucherCodeUsed) {
            if ($voucherCodeUsed === $voucherCode) {
                return true;
            }
        }

        return false;
    }
}
