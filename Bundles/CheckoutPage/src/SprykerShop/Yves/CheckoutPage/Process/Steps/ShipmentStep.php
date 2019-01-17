<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use \ArrayObject;

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
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection            $shipmentPlugins
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface  $shipmentService
     * @param $stepRoute
     * @param $escapeRoute
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepHandlerPluginCollection $shipmentPlugins,
        CheckoutPageToShipmentServiceInterface $shipmentService,
        $stepRoute,
        $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->calculationClient = $calculationClient;
        $this->shipmentPlugins = $shipmentPlugins;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        /**
         * This is stub, remove when address step is ready
         */
        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            $shipment = new ShipmentTransfer();
            $address = new AddressTransfer();
            $address->setAddress1('address ' . $key);
            $address->setIso2Code('DE');
            $shipment->setShippingAddress($address);
            $itemTransfer->setShipment($shipment);
        }

        $quoteTransfer->setShipmentGroups($this->getShipmentGroupCollection($quoteTransfer));

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer  $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $shipmentHandler = $this->shipmentPlugins->get(CheckoutPageDependencyProvider::PLUGIN_SHIPMENT_STEP_HANDLER);
        $quoteTransfer = $shipmentHandler->addToDataClass($request, $quoteTransfer);

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        return $this->isShipmentSet($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSet(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getExpense() === null) {
                return false;
            }
        }

        return true;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function getShipmentGroupCollection(QuoteTransfer $quoteTransfer): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
    }
}
