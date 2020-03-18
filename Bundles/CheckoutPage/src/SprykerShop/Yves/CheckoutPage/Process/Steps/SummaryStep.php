<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected $checkoutPageConfig;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface $checkoutClient
     */
    public function __construct(
        CheckoutPageToProductBundleClientInterface $productBundleClient,
        CheckoutPageToShipmentServiceInterface $shipmentService,
        CheckoutPageConfig $checkoutPageConfig,
        $stepRoute,
        $escapeRoute,
        CheckoutPageToCheckoutClientInterface $checkoutClient
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->productBundleClient = $productBundleClient;
        $this->shipmentService = $shipmentService;
        $this->checkoutPageConfig = $checkoutPageConfig;
        $this->checkoutClient = $checkoutClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $this->markCheckoutConfirmed($request, $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress() !== null
            && $this->haveItemsShipmentTransfers($quoteTransfer)
            && $quoteTransfer->getPayment() !== null
            && $quoteTransfer->getPayment()->getPaymentProvider() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer)
    {
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $isPlaceableOrderResponseTransfer = $this->checkoutClient->isPlaceableOrder($quoteTransfer);

        return [
            'quoteTransfer' => $quoteTransfer,
            'cartItems' => $this->productBundleClient->getGroupedBundleItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems()
            ),
            'shipmentGroups' => $this->expandShipmentGroupsWithCartItems($shipmentGroups, $quoteTransfer),
            'totalCosts' => $this->getShipmentTotalCosts($shipmentGroups, $quoteTransfer),
            'isPlaceableOrder' => $isPlaceableOrderResponseTransfer->getIsSuccess(),
            'isPlaceableOrderErrors' => $isPlaceableOrderResponseTransfer->getErrors(),
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function expandShipmentGroupsWithCartItems(ArrayObject $shipmentGroupTransfers, QuoteTransfer $quoteTransfer): ArrayObject
    {
        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $cartItems = $this->productBundleClient->getGroupedBundleItems(
                $shipmentGroupTransfer->getItems(),
                $quoteTransfer->getBundleItems()
            );

            $shipmentGroupTransfer->setCartItems($cartItems);
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.summary.title';
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function markCheckoutConfirmed(Request $request, QuoteTransfer $quoteTransfer)
    {
        if ($request->isMethod('POST')) {
            $quoteTransfer->setCheckoutConfirmed(true);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroups
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroups
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
}
