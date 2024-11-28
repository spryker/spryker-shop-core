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
use Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Extractor\PaymentMethodKeyExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentStep extends AbstractBaseStep implements StepWithBreadcrumbInterface, StepWithCodeInterface
{
    /**
     * @var string
     */
    protected const STEP_CODE = 'payment';

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
     * @var array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface>
     */
    protected $checkoutPaymentStepEnterPreCheckPlugins;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Extractor\PaymentMethodKeyExtractorInterface
     */
    protected $paymentMethodKeyExtractor;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface $paymentClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $paymentPlugins
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface> $checkoutPaymentStepEnterPreCheckPlugins
     * @param \SprykerShop\Yves\CheckoutPage\Extractor\PaymentMethodKeyExtractorInterface $paymentMethodKeyExtractor
     */
    public function __construct(
        CheckoutPageToPaymentClientInterface $paymentClient,
        StepHandlerPluginCollection $paymentPlugins,
        $stepRoute,
        $escapeRoute,
        FlashMessengerInterface $flashMessenger,
        CheckoutPageToCalculationClientInterface $calculationClient,
        array $checkoutPaymentStepEnterPreCheckPlugins,
        PaymentMethodKeyExtractorInterface $paymentMethodKeyExtractor
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->paymentClient = $paymentClient;
        $this->paymentPlugins = $paymentPlugins;
        $this->flashMessenger = $flashMessenger;
        $this->calculationClient = $calculationClient;
        $this->checkoutPaymentStepEnterPreCheckPlugins = $checkoutPaymentStepEnterPreCheckPlugins;
        $this->paymentMethodKeyExtractor = $paymentMethodKeyExtractor;
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
        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection($paymentSelection);
        $paymentSelectionKey = $this->paymentMethodKeyExtractor->getPaymentSelectionKey($paymentTransfer);

        if ($paymentSelection === null || !$this->paymentPlugins->has($paymentSelectionKey)) {
            return $quoteTransfer;
        }

        $paymentHandler = $this->paymentPlugins->get($paymentSelectionKey);
        if ($paymentHandler instanceof StepHandlerPluginWithMessengerInterface) {
            $paymentHandler->setFlashMessenger($this->flashMessenger);
        }
        $paymentHandler->addToDataClass($request, $quoteTransfer);
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

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

        return true;
    }

    /**
     * @deprecated To be removed when the single payment property on the quote is removed
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PaymentTransfer>
     */
    protected function getPaymentCollection(QuoteTransfer $quoteTransfer): ArrayObject
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PaymentTransfer> $result */
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
     * @deprecated Will be removed with next major. Form is populated with available payments and validated against when submitting the form.
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PaymentTransfer> $paymentCollection
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $quoteTransfer)
    {
        return $this->postCondition($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $quoteTransfer)
    {
        return !$this->requireInput($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeCheckoutPaymentStepEnterPreCheckPlugins(AbstractTransfer $quoteTransfer): bool
    {
        foreach ($this->checkoutPaymentStepEnterPreCheckPlugins as $checkoutPaymentStepEnterPreCheckPlugin) {
            if (!$checkoutPaymentStepEnterPreCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::STEP_CODE;
    }
}
