<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DiscountWidget\Handler;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCartClientInterface;

class VoucherHandler extends BaseHandler implements VoucherHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCartClientInterface $cartClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(
        DiscountWidgetToCalculationClientInterface $calculationClient,
        DiscountWidgetToCartClientInterface $cartClient,
        FlashMessengerInterface $flashMessenger
    ) {
        parent::__construct($flashMessenger);
        $this->calculationClient = $calculationClient;
        $this->cartClient = $cartClient;
    }

    /**
     * @param string $voucherCode
     *
     * @return void
     */
    public function add($voucherCode)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        $voucherDiscount = new DiscountTransfer();
        $voucherDiscount->setVoucherCode($voucherCode);
        $quoteTransfer->addVoucherDiscount($voucherDiscount);

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->addFlashMessages($quoteTransfer, $voucherCode);

        $this->cartClient->storeQuote($quoteTransfer);
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
        $quoteTransfer = $this->cartClient->getQuote();

        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();
        $this->unsetVoucherCode($voucherCode, $voucherDiscounts);

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        $this->setFlashMessagesFromLastZedRequest($this->calculationClient);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function clear()
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $quoteTransfer->setVoucherDiscounts(new ArrayObject());

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        $this->setFlashMessagesFromLastZedRequest($this->calculationClient);
        $this->cartClient->storeQuote($quoteTransfer);
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
