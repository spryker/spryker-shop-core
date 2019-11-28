<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithPostConditionErrorRouteInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class PlaceOrderStep extends AbstractBaseStep implements StepWithExternalRedirectInterface, StepWithPostConditionErrorRouteInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var string
     */
    protected $externalRedirectUrl;

    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer|null
     */
    protected $checkoutResponseTransfer;

    /**
     * @var array
     */
    protected $errorCodeToRouteMatching = [];

    /**
     * @var string|null
     */
    protected $postConditionErrorRoute;

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @var string
     */
    protected $currentLocale;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param string $currentLocale
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param array $errorCodeToRouteMatching
     */
    public function __construct(
        CheckoutPageToCheckoutClientInterface $checkoutClient,
        FlashMessengerInterface $flashMessenger,
        string $currentLocale,
        CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient,
        $stepRoute,
        $escapeRoute,
        $errorCodeToRouteMatching = []
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->checkoutClient = $checkoutClient;
        $this->errorCodeToRouteMatching = $errorCodeToRouteMatching;
        $this->flashMessenger = $flashMessenger;
        $this->currentLocale = $currentLocale;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        if ($this->isCartEmpty($quoteTransfer)) {
            return false;
        }

        if (!$quoteTransfer->getCheckoutConfirmed()) {
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
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getCheckoutConfirmed()) {
            return false;
        }

        if ($this->checkoutResponseTransfer && !$this->checkoutResponseTransfer->getIsSuccess()) {
            $this->setPostConditionErrorRoute($this->checkoutResponseTransfer);

            return false;
        }

        return ($quoteTransfer->getOrderReference() !== null);
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);

        if ($checkoutResponseTransfer->getIsExternalRedirect()) {
            $this->externalRedirectUrl = $checkoutResponseTransfer->getRedirectUrl();
        }

        if ($checkoutResponseTransfer->getSaveOrder() !== null) {
            $quoteTransfer->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
        }

        $this->setCheckoutErrorMessages($checkoutResponseTransfer);
        $this->checkoutResponseTransfer = $checkoutResponseTransfer;

        return $quoteTransfer;
    }

    /**
     * @return string
     */
    public function getExternalRedirectUrl()
    {
        return $this->externalRedirectUrl;
    }

    /**
     * @return string|null
     */
    public function getPostConditionErrorRoute()
    {
        return $this->postConditionErrorRoute;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setPostConditionErrorRoute(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        foreach ($checkoutResponseTransfer->getErrors() as $error) {
            if (isset($this->errorCodeToRouteMatching[$error->getErrorCode()])) {
                $this->postConditionErrorRoute = $this->errorCodeToRouteMatching[$error->getErrorCode()];
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $this->flashMessenger->addErrorMessage(
                $this->translateCheckoutErrorMessage($checkoutErrorTransfer)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function translateCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $checkoutErrorMessage = $checkoutErrorTransfer->getMessage();

        return $this->glossaryStorageClient->translate(
            $checkoutErrorMessage,
            $this->currentLocale,
            $checkoutErrorTransfer->getParameters()
        ) ?: $checkoutErrorMessage;
    }
}
