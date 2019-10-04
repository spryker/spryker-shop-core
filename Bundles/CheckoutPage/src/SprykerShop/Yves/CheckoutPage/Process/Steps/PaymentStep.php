<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginWithMessengerInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface
     */
    protected $paymentClient;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    protected $paymentPlugins;

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface[]
     */
    protected $checkoutPaymentStepEnterPreCheckPlugins;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface $paymentClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $paymentPlugins
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface[] $checkoutPaymentStepEnterPreCheckPlugins
     */
    public function __construct(
        CheckoutPageToPaymentClientInterface $paymentClient,
        StepHandlerPluginCollection $paymentPlugins,
        $stepRoute,
        $escapeRoute,
        FlashMessengerInterface $flashMessenger,
        CheckoutPageToCalculationClientInterface $calculationClient,
        array $checkoutPaymentStepEnterPreCheckPlugins
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->paymentClient = $paymentClient;
        $this->paymentPlugins = $paymentPlugins;
        $this->flashMessenger = $flashMessenger;
        $this->calculationClient = $calculationClient;
        $this->checkoutPaymentStepEnterPreCheckPlugins = $checkoutPaymentStepEnterPreCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        $totals = $quoteTransfer->getTotals();

        return $this->executeCheckoutPaymentStepEnterPreCheckPlugins($quoteTransfer) && (!$totals || $totals->getPriceToPay() > 0);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        if (!$this->executeCheckoutPaymentStepEnterPreCheckPlugins($quoteTransfer)) {
            return $quoteTransfer;
        }
        $paymentSelection = $this->getPaymentSelectionWithFallback($quoteTransfer);
        if ($paymentSelection === null) {
            return $quoteTransfer;
        }

        if ($this->paymentPlugins->has($paymentSelection)) {
            $paymentHandler = $this->paymentPlugins->get($paymentSelection);
            if ($paymentHandler instanceof StepHandlerPluginWithMessengerInterface) {
                $paymentHandler->setFlashMessenger($this->flashMessenger);
            }
            $paymentHandler->addToDataClass($request, $quoteTransfer);
            $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getPaymentSelectionWithFallback(QuoteTransfer $quoteTransfer): ?string
    {
        if ($quoteTransfer->getTotals() && $quoteTransfer->getTotals()->getPriceToPay() === 0) {
            return CheckoutPageConfig::PAYMENT_METHOD_NAME_NO_PAYMENT;
        }

        $paymentTransfer = $quoteTransfer->getPayment();

        if ($paymentTransfer) {
            return $paymentTransfer->getPaymentSelection();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null || $quoteTransfer->getPayment()->getPaymentProvider() === null) {
            return false;
        }

        $paymentCollection = $this->getPaymentCollection($quoteTransfer);
        if ($paymentCollection->count() === 0) {
            return false;
        }

        return $this->isValidPaymentSelection($paymentCollection, $quoteTransfer);
    }

    /**
     * @deprecated To be removed when the single payment property on the quote is removed
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]|\ArrayObject
     */
    protected function getPaymentCollection(QuoteTransfer $quoteTransfer): ArrayObject
    {
        $result = new ArrayObject();
        foreach ($quoteTransfer->getPayments() as $payment) {
            $result[] = $payment;
        }

        $singlePayment = $quoteTransfer->getPayment();

        if ($singlePayment) {
            $result[] = $singlePayment;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[]|\ArrayObject $paymentCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isValidPaymentSelection(ArrayObject $paymentCollection, QuoteTransfer $quoteTransfer): bool
    {
        $paymentMethods = $this->paymentClient->getAvailableMethods($quoteTransfer);

        foreach ($paymentCollection as $candidatePayment) {
            if (!$this->containsPayment($paymentMethods, $candidatePayment)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return bool
     */
    protected function containsPayment(PaymentMethodsTransfer $paymentMethodsTransfer, PaymentTransfer $paymentTransfer): bool
    {
        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if ($paymentMethodTransfer->getMethodName() === $paymentTransfer->getPaymentSelection()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.payment.title';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer)
    {
        return $this->postCondition($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer)
    {
        return !$this->requireInput($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    protected function executeCheckoutPaymentStepEnterPreCheckPlugins(AbstractTransfer $dataTransfer): bool
    {
        foreach ($this->checkoutPaymentStepEnterPreCheckPlugins as $checkoutPaymentStepEnterPreCheckPlugin) {
            if ($checkoutPaymentStepEnterPreCheckPlugin->check($dataTransfer)) {
                return false;
            }
        }

        return true;
    }
}
