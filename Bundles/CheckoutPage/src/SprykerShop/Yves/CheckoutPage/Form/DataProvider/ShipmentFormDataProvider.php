<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Form\Steps\ShipmentCollectionForm;

class ShipmentFormDataProvider implements StepEngineFormDataProviderInterface
{
    public const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryClientInterface
     */
    protected $glossaryClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryClientInterface $glossaryClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToGlossaryClientInterface $glossaryClient,
        Store $store,
        MoneyPluginInterface $moneyPlugin
    ) {
        $this->shipmentClient = $shipmentClient;
        $this->glossaryClient = $glossaryClient;
        $this->store = $store;
        $this->moneyPlugin = $moneyPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShipment() === null) {
            $shipmentTransfer = new ShipmentTransfer();
            $quoteTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [
            ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP => $this->createAvailableMethodsByShipmentChoiceList($quoteTransfer),
        ];
    }

    /**
     * @deprecated Use createAvailableMethodsByShipmentChoiceList() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function createAvailableShipmentChoiceList(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethods = [];

        $shipmentMethodsTransfer = $this->getAvailableShipmentMethods($quoteTransfer);
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if (!isset($shipmentMethods[$shipmentMethodTransfer->getCarrierName()])) {
                $shipmentMethods[$shipmentMethodTransfer->getCarrierName()] = [];
            }
            $description = $this->getShipmentDescription(
                $shipmentMethodTransfer
            );
            $shipmentMethods[$shipmentMethodTransfer->getCarrierName()][$description] = $shipmentMethodTransfer->getIdShipmentMethod();
        }

        return $shipmentMethods;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function createAvailableMethodsByShipmentChoiceList(QuoteTransfer $quoteTransfer): array
    {
        $shipmentMethods = [];

        $shipmentGroupsTransfer = $this->getAvailableMethodsByShipment($quoteTransfer);

        foreach ($shipmentGroupsTransfer->getGroups() as $shipmentGroupTransfer) {
            foreach ($shipmentGroupTransfer->getAvailableShipmentMethods()->getMethods() as $shipmentMethodTransfer) {
                if (!isset($shipmentMethods[$shipmentGroupTransfer->getHash()][$shipmentMethodTransfer->getCarrierName()])) {
                    $shipmentMethods[$shipmentGroupTransfer->getHash()][$shipmentMethodTransfer->getCarrierName()] = [];
                }
                $description = $this->getShipmentDescription(
                    $shipmentMethodTransfer
                );
                $shipmentMethods[$shipmentGroupTransfer->getHash()][$shipmentMethodTransfer->getCarrierName()][$description] = $shipmentMethodTransfer->getIdShipmentMethod();
            }
        }

        return $shipmentMethods;
    }

    /**
     * @deprecated Use getAvailableMethodsByShipment() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableShipmentMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->shipmentClient->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    protected function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        return $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return string
     */
    protected function getShipmentDescription(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $shipmentDescription = $this->translate($shipmentMethodTransfer->getName());

        $shipmentDescription = $this->appendDeliveryTime($shipmentMethodTransfer, $shipmentDescription);
        $shipmentDescription = $this->appendShipmentPrice($shipmentMethodTransfer, $shipmentDescription);

        return $shipmentDescription;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $shipmentDescription
     *
     * @return string
     */
    protected function appendDeliveryTime(ShipmentMethodTransfer $shipmentMethodTransfer, $shipmentDescription)
    {
        $deliveryTime = $this->getDeliveryTime($shipmentMethodTransfer);

        if ($deliveryTime !== 0) {
            $shipmentDescription = sprintf(
                '%s (%s %d %s)',
                $shipmentDescription,
                $this->translate('page.checkout.shipping.delivery_time'),
                $deliveryTime,
                ($deliveryTime === 1) ? 'day' : 'days'
            );
        }

        return $shipmentDescription;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $shipmentDescription
     *
     * @return string
     */
    protected function appendShipmentPrice(ShipmentMethodTransfer $shipmentMethodTransfer, $shipmentDescription)
    {
        $shipmentPrice = $this->getFormattedShipmentPrice($shipmentMethodTransfer);
        $shipmentDescription .= ': ' . $shipmentPrice;

        return $shipmentDescription;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $method
     *
     * @return int
     */
    protected function getDeliveryTime(ShipmentMethodTransfer $method)
    {
        if (!$method->getDeliveryTime()) {
            return 0;
        }

        return (int)($method->getDeliveryTime() / 86400);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return string
     */
    protected function getFormattedShipmentPrice(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $moneyTransfer = $this->moneyPlugin
            ->fromInteger($shipmentMethodTransfer->getStoreCurrencyPrice());

        return $this->moneyPlugin->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param string $translationKey
     *
     * @return string
     */
    protected function translate($translationKey)
    {
        return $this->glossaryClient->translate($translationKey, $this->store->getCurrentLocale());
    }
}
