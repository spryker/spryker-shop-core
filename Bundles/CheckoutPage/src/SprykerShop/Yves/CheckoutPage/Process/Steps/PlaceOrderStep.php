<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;

class PlaceOrderStep extends AbstractPlaceOrderStep
{
    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param array $errorCodeToRouteMatching
     */
    public function __construct(
        CheckoutPageToCheckoutClientInterface $checkoutClient,
        FlashMessengerInterface $flashMessenger,
        $stepRoute,
        $escapeRoute,
        $errorCodeToRouteMatching = []
    ) {
        parent::__construct($checkoutClient, $stepRoute, $escapeRoute, $errorCodeToRouteMatching);

        $this->flashMessenger = $flashMessenger;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getCheckoutConfirmed()) {
            return false;
        }

        return parent::postCondition($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $dataTransfer)
    {
        if ($this->isCartEmpty($dataTransfer)) {
            return false;
        }

        if (!$dataTransfer->getCheckoutConfirmed()) {
            $this->escapeRoute = CheckoutPageControllerProvider::CHECKOUT_SUMMARY;
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCartEmpty(QuoteTransfer $quoteTransfer)
    {
        return count($quoteTransfer->getItems()) === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $this->flashMessenger->addErrorMessage($checkoutErrorTransfer->getMessage());
        }
    }
}
