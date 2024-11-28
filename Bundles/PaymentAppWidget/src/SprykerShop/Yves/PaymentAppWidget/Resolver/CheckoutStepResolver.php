<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Resolver;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;
use SprykerShop\Yves\PaymentAppWidget\Reader\RequestRouteReaderInterface;

class CheckoutStepResolver implements CheckoutStepResolverInterface
{
 /**
  * @var \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig
  */
    protected PaymentAppWidgetConfig $paymentAppWidgetConfig;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Reader\RequestRouteReaderInterface
     */
    protected RequestRouteReaderInterface $requestRouteReader;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface
     */
    protected PaymentAppWidgetToQuoteClientInterface $quoteClient;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig $paymentAppWidgetConfig
     * @param \SprykerShop\Yves\PaymentAppWidget\Reader\RequestRouteReaderInterface $requestRouteReader
     */
    public function __construct(
        PaymentAppWidgetConfig $paymentAppWidgetConfig,
        RequestRouteReaderInterface $requestRouteReader
    ) {
        $this->paymentAppWidgetConfig = $paymentAppWidgetConfig;
        $this->requestRouteReader = $requestRouteReader;
    }

    /**
     * @param list<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface> $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface>
     */
    public function applyExpressCheckoutWorkflow(array $steps, QuoteTransfer $quoteTransfer): array
    {
        if ($this->requestRouteReader->getCurrentRequestRouteName() === $this->paymentAppWidgetConfig->getExpressCheckoutStartPageRouteName()) {
            $this->cleanQuoteFieldsInExpressCheckoutWorkflow($quoteTransfer);

            return $steps;
        }

        foreach ($steps as $stepKey => $step) {
            if (
                $step instanceof StepWithCodeInterface
                && in_array(
                    $step->getCode(),
                    $this->paymentAppWidgetConfig->getCheckoutStepsToSkipInExpressCheckoutWorkflow(),
                    true,
                )
            ) {
                unset($steps[$stepKey]);
            }
        }

        return array_values($steps);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function cleanQuoteFieldsInExpressCheckoutWorkflow(QuoteTransfer $quoteTransfer): void
    {
        $quoteFieldsToCleanInExpressCheckoutWorkflow = $this->paymentAppWidgetConfig->getQuoteFieldsToCleanInExpressCheckoutWorkflow();
        foreach ($quoteFieldsToCleanInExpressCheckoutWorkflow as $property) {
            if (!$quoteTransfer->offsetExists($property)) {
                continue;
            }

            if (is_array($quoteTransfer->offsetGet($property))) {
                $quoteTransfer->offsetSet($property, []);

                continue;
            }

            if ($quoteTransfer->offsetGet($property) instanceof ArrayObject) {
                $quoteTransfer->offsetSet($property, new ArrayObject());

                continue;
            }

            $quoteTransfer->offsetSet($property, null);
        }
    }
}
