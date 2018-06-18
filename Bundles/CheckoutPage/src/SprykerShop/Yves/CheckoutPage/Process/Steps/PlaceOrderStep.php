<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithPostConditionErrorRouteInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToZedRequestClientInterface;
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
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected $checkoutResponseTransfer;

    /**
     * @var array
     */
    protected $errorCodeToRouteMatching = [];

    /**
     * @var string
     */
    protected $postConditionErrorRoute;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToZedRequestClientInterface $zedRequestClient
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param array $errorCodeToRouteMatching
     */
    public function __construct(
        CheckoutPageToCheckoutClientInterface $checkoutClient,
        CheckoutPageToZedRequestClientInterface $zedRequestClient,
        $stepRoute,
        $escapeRoute,
        $errorCodeToRouteMatching = []
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->checkoutClient = $checkoutClient;
        $this->zedRequestClient = $zedRequestClient;

        $this->errorCodeToRouteMatching = $errorCodeToRouteMatching;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @return string
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
            if ($checkoutErrorTransfer->getDetailedMessage()) {
                $this->checkoutClient->addCheckoutErrorMessage(
                    $checkoutErrorTransfer->getDetailedMessage()
                );

                $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
            }

            if ($checkoutErrorTransfer->getMessage()) {
                $this->checkoutClient->addCheckoutErrorMessage(
                    $this->createMessageTransfer()
                        ->setValue($checkoutErrorTransfer->getMessage())
                );

                $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(): MessageTransfer
    {
        return new MessageTransfer();
    }
}
