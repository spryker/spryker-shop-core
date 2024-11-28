<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Resolver;

use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\ExpressCheckoutRedirectUrlsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientInterface;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface;
use SprykerShop\Yves\PaymentAppWidget\Generator\ExpressCheckoutRedirectGeneratorInterface;
use SprykerShop\Yves\PaymentAppWidget\Reader\ExpressCheckoutPaymentMethodWidgetReaderInterface;

class ExpressCheckoutPaymentMethodWidgetResolver implements ExpressCheckoutPaymentMethodWidgetResolverInterface
{
    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientInterface
     */
    protected PaymentAppWidgetToPaymentClientInterface $paymentClient;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface
     */
    protected PaymentAppWidgetToQuoteClientInterface $quoteClient;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Generator\ExpressCheckoutRedirectGeneratorInterface
     */
    protected ExpressCheckoutRedirectGeneratorInterface $expressCheckoutRedirectGenerator;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\Reader\ExpressCheckoutPaymentMethodWidgetReaderInterface
     */
    protected ExpressCheckoutPaymentMethodWidgetReaderInterface $expressCheckoutPaymentMethodTemplateReader;

    /**
     * @param \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientInterface $paymentClient
     * @param \SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\PaymentAppWidget\Generator\ExpressCheckoutRedirectGeneratorInterface $expressCheckoutRedirectGenerator
     * @param \SprykerShop\Yves\PaymentAppWidget\Reader\ExpressCheckoutPaymentMethodWidgetReaderInterface $expressCheckoutPaymentMethodTemplateReader
     */
    public function __construct(
        PaymentAppWidgetToPaymentClientInterface $paymentClient,
        PaymentAppWidgetToQuoteClientInterface $quoteClient,
        ExpressCheckoutRedirectGeneratorInterface $expressCheckoutRedirectGenerator,
        ExpressCheckoutPaymentMethodWidgetReaderInterface $expressCheckoutPaymentMethodTemplateReader
    ) {
        $this->paymentClient = $paymentClient;
        $this->quoteClient = $quoteClient;
        $this->expressCheckoutRedirectGenerator = $expressCheckoutRedirectGenerator;
        $this->expressCheckoutPaymentMethodTemplateReader = $expressCheckoutPaymentMethodTemplateReader;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>
     */
    public function getExpressCheckoutPaymentMethodWidgets(): array
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $paymentMethodsTransfer = $this->paymentClient->getAvailableMethods($quoteTransfer);
        if (!$paymentMethodsTransfer->getMethods()->count()) {
            return [];
        }

        $expressCheckoutRedirectUrlsTransfer = $this->expressCheckoutRedirectGenerator->generateRedirectUrls();
        $baseExpressCheckoutPaymentMethodWidgetTransfer = $this->createBaseExpressCheckoutPaymentMethodWidgetTransfer(
            $quoteTransfer,
            $expressCheckoutRedirectUrlsTransfer,
        );

        return $this->expressCheckoutPaymentMethodTemplateReader->getExpressCheckoutPaymentMethodWidgets(
            $paymentMethodsTransfer,
            $baseExpressCheckoutPaymentMethodWidgetTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutRedirectUrlsTransfer $expressCheckoutRedirectUrlsTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer
     */
    protected function createBaseExpressCheckoutPaymentMethodWidgetTransfer(
        QuoteTransfer $quoteTransfer,
        ExpressCheckoutRedirectUrlsTransfer $expressCheckoutRedirectUrlsTransfer
    ): ExpressCheckoutPaymentMethodWidgetTransfer {
        return (new ExpressCheckoutPaymentMethodWidgetTransfer())
            ->setQuote($quoteTransfer)
            ->setRedirectUrls($expressCheckoutRedirectUrlsTransfer);
    }
}
