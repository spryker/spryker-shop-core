<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class SummaryStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     * @param array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepPreConditionPluginInterface> $preConditionPlugins
     * @param array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepPostConditionPluginInterface> $postConditionPlugins
     * @param string $stepRoute
     * @param string|null $escapeRoute
     */
    public function __construct(
        protected CheckoutPageToProductBundleClientInterface $productBundleClient,
        protected CheckoutPageToShipmentServiceInterface $shipmentService,
        protected CheckoutPageConfig $checkoutPageConfig,
        protected CheckoutPageToCheckoutClientInterface $checkoutClient,
        protected array $preConditionPlugins,
        protected array $postConditionPlugins,
        string $stepRoute,
        ?string $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer): bool
    {
        $quoteTransfer = $this->executePreConditionPlugins($quoteTransfer);

        if (!$quoteTransfer->getShippingAddress() || !$quoteTransfer->getBillingAddress()) {
            return false;
        }

        return parent::preCondition($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePreConditionPlugins(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->preConditionPlugins as $preConditionPlugin) {
            $quoteTransfer = $preConditionPlugin->preCondition($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $this->markCheckoutConfirmed($request, $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer): bool
    {
        $quoteTransfer = $this->executePostConditionPlugins($quoteTransfer);

        return $quoteTransfer->getBillingAddress() !== null
            && $this->haveItemsShipmentTransfers($quoteTransfer)
            && $quoteTransfer->getPayment() !== null
            && $quoteTransfer->getPayment()->getPaymentProvider() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePostConditionPlugins(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        foreach ($this->postConditionPlugins as $postConditionPlugin) {
            $quoteTransfer = $postConditionPlugin->postCondition($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer): array
    {
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $isPlaceableOrderResponseTransfer = $this->checkoutClient->isPlaceableOrder($quoteTransfer);
        $isPlaceableOrder = $quoteTransfer->getIsOrderPlacedSuccessfully() !== null || $isPlaceableOrderResponseTransfer->getIsSuccess();

        return [
            'quoteTransfer' => $quoteTransfer,
            'cartItems' => $this->productBundleClient->getGroupedBundleItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems(),
            ),
            'shipmentGroups' => $this->expandShipmentGroupsWithCartItems($shipmentGroups, $quoteTransfer),
            'totalCosts' => $this->getShipmentTotalCosts($shipmentGroups, $quoteTransfer),
            'isPlaceableOrder' => $isPlaceableOrder,
            'isPlaceableOrderErrors' => $isPlaceableOrderResponseTransfer->getErrors(),
            'shipmentExpenses' => $this->getShipmentExpenses($quoteTransfer),
            'acceptTermsFieldName' => QuoteTransfer::ACCEPT_TERMS_AND_CONDITIONS,
        ];
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer>
     */
    protected function expandShipmentGroupsWithCartItems(ArrayObject $shipmentGroupTransfers, QuoteTransfer $quoteTransfer): ArrayObject
    {
        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $cartItems = $this->productBundleClient->getGroupedBundleItems(
                $shipmentGroupTransfer->getItems(),
                $quoteTransfer->getBundleItems(),
            );

            $shipmentGroupTransfer->setCartItems($cartItems);
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle(): string
    {
        return 'checkout.step.summary.title';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $quoteTransfer): bool
    {
        return $this->postCondition($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $quoteTransfer): bool
    {
        return !$this->requireInput($quoteTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function markCheckoutConfirmed(Request $request, QuoteTransfer $quoteTransfer): void
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return;
        }

        $quoteTransfer->setCheckoutConfirmed(true);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroups
     *
     * @return int
     */
    protected function getTotalCosts(ArrayObject $shipmentGroups): int
    {
        $totalCosts = 0;

        foreach ($shipmentGroups as $shipmentGroup) {
            $totalCosts += $shipmentGroup->getShipment()->getMethod()->getStoreCurrencyPrice();
        }

        return $totalCosts;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroups
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getShipmentTotalCosts(ArrayObject $shipmentGroups, QuoteTransfer $quoteTransfer): int
    {
        $totalsTransfer = $quoteTransfer->getTotals();

        if ($totalsTransfer && $totalsTransfer->getShipmentTotal() !== null) {
            return $totalsTransfer->getShipmentTotal();
        }

        return $this->getTotalCosts($shipmentGroups);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function haveItemsShipmentTransfers(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function getShipmentExpenses(QuoteTransfer $quoteTransfer): array
    {
        $shipmentExpenses = [];

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentHashKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());
            $shipmentExpenses[$shipmentHashKey] = $expenseTransfer;
        }

        return $shipmentExpenses;
    }
}
