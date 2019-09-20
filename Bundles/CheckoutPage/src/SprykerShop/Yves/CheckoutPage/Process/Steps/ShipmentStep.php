<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ShipmentStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    protected $shipmentPlugins;

    /**
     * @var \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepPreCheckPluginInterface[]
     */
    protected $shipmentStepPreCheckPlugins;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $shipmentPlugins
     * @param string $stepRoute
     * @param string $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepPreCheckPluginInterface[] $shipmentStepPreCheckPlugins
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepHandlerPluginCollection $shipmentPlugins,
        $stepRoute,
        $escapeRoute,
        array $shipmentStepPreCheckPlugins
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->calculationClient = $calculationClient;
        $this->shipmentPlugins = $shipmentPlugins;
        $this->shipmentStepPreCheckPlugins = $shipmentStepPreCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return $this->executeShipmentStepPreCheckPlugins($quoteTransfer) === false
            && $quoteTransfer->getItems()->count()
            && $this->hasOnlyGiftCardItems($quoteTransfer) === false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        if (!$this->requireInput($quoteTransfer) && $this->executeShipmentStepPreCheckPlugins($quoteTransfer) === true) {
            $quoteTransfer = $this->setDefaultNoShipmentMethod($quoteTransfer);
        }

        $shipmentHandler = $this->shipmentPlugins->get(CheckoutPageDependencyProvider::PLUGIN_SHIPMENT_STEP_HANDLER);
        $shipmentHandler->addToDataClass($request, $quoteTransfer);

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($this->hasOnlyGiftCardItems($quoteTransfer)) {
            return true;
        }

        if (!$this->isShipmentSet($quoteTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSet(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getShipment()) {
            return false;
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                return $quoteTransfer->getShipment()->getShipmentSelection() !== null;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasOnlyGiftCardItems(QuoteTransfer $quoteTransfer): bool
    {
        $onlyGiftCardItems = true;
        foreach ($quoteTransfer->getItems() as $item) {
            $isGiftCard = $item->getGiftCardMetadata() ? $item->getGiftCardMetadata()->getIsGiftCard() : false;
            $onlyGiftCardItems &= $isGiftCard;
        }

        return (bool)$onlyGiftCardItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setDefaultNoShipmentMethod(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentTransfer = (new ShipmentTransfer())
            ->setShipmentSelection(CheckoutPageConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT);

        return $quoteTransfer->setShipment($shipmentTransfer);
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.shipment.title';
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
    protected function executeShipmentStepPreCheckPlugins(AbstractTransfer $dataTransfer): bool
    {
        foreach ($this->shipmentStepPreCheckPlugins as $shipmentStepPreCheckPlugin) {
            if (!$shipmentStepPreCheckPlugin->check($dataTransfer)) {
                return false;
            }
        }

        return true;
    }
}
