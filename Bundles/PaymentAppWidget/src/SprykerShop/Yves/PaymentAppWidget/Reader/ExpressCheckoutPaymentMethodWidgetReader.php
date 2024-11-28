<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Reader;

use Generated\Shared\Transfer\ExpressCheckoutCsrfTokenTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ExpressCheckoutPaymentMethodWidgetReader implements ExpressCheckoutPaymentMethodWidgetReaderInterface
{
    /**
     * @var list<\SprykerShop\Yves\PaymentAppWidgetExtension\Dependency\Plugin\ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface>
     */
    protected array $expressCheckoutPaymentWidgetRenderStrategyPlugins;

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    protected CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @param list<\SprykerShop\Yves\PaymentAppWidgetExtension\Dependency\Plugin\ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface> $expressCheckoutPaymentWidgetRenderStrategyPlugins
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager
     */
    public function __construct(
        array $expressCheckoutPaymentWidgetRenderStrategyPlugins,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->expressCheckoutPaymentWidgetRenderStrategyPlugins = $expressCheckoutPaymentWidgetRenderStrategyPlugins;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
     *
     * @return list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>
     */
    public function getExpressCheckoutPaymentMethodWidgets(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
    ): array {
        $expressCheckoutPaymentMethodWidgetTransfers = [];
        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            $expressCheckoutPaymentMethodWidgetTransfers[] = $this->executeExpressCheckoutPaymentWidgetRenderStrategyPlugins(
                $paymentMethodTransfer,
                $baseExpressCheckoutPaymentMethodWidgetTransfer,
            );
        }

        return array_merge(...$expressCheckoutPaymentMethodWidgetTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
     *
     * @return list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>
     */
    protected function executeExpressCheckoutPaymentWidgetRenderStrategyPlugins(
        PaymentMethodTransfer $paymentMethodTransfer,
        ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
    ): array {
        $expressCheckoutPaymentMethodWidgetTransfers = [];
        foreach ($this->expressCheckoutPaymentWidgetRenderStrategyPlugins as $expressCheckoutPaymentWidgetRenderStrategyPlugin) {
            if (!$expressCheckoutPaymentWidgetRenderStrategyPlugin->isApplicable($paymentMethodTransfer)) {
                continue;
            }

            $expressCheckoutPaymentMethodWidgetTransfer = $this->cloneExpressCheckoutPaymentMethodWidgetTransfer($baseExpressCheckoutPaymentMethodWidgetTransfer, $paymentMethodTransfer);
            $expressCheckoutPaymentMethodWidgetTransfers[] = $expressCheckoutPaymentWidgetRenderStrategyPlugin->getExpressCheckoutPaymentMethodWidget(
                $expressCheckoutPaymentMethodWidgetTransfer,
            );
        }

        return $expressCheckoutPaymentMethodWidgetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer
     */
    public function cloneExpressCheckoutPaymentMethodWidgetTransfer(
        ExpressCheckoutPaymentMethodWidgetTransfer $baseExpressCheckoutPaymentMethodWidgetTransfer,
        PaymentMethodTransfer $paymentMethodTransfer
    ): ExpressCheckoutPaymentMethodWidgetTransfer {
        return (new ExpressCheckoutPaymentMethodWidgetTransfer())
            ->fromArray($baseExpressCheckoutPaymentMethodWidgetTransfer->toArray(), true)
            ->setPaymentMethod($paymentMethodTransfer)
            ->setCsrfToken(
                (new ExpressCheckoutCsrfTokenTransfer())
                    ->setValue($this->csrfTokenManager->getToken($paymentMethodTransfer->getPaymentMethodKeyOrFail())->getValue())
                    ->setName($paymentMethodTransfer->getPaymentMethodKeyOrFail()),
            );
    }
}
