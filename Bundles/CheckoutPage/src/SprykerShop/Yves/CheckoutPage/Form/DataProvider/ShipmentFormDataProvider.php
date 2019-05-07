<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Form\Steps\ShipmentCollectionForm;

class ShipmentFormDataProvider implements StepEngineFormDataProviderInterface
{
    use PermissionAwareTrait;

    protected const ONE_DAY = 1;
    protected const SECONDS_IN_ONE_DAY = 86400;

    public const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient,
        Store $store,
        MoneyPluginInterface $moneyPlugin,
        CheckoutPageToShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentClient = $shipmentClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->store = $store;
        $this->moneyPlugin = $moneyPlugin;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = $this->setQuoteShipmentGroups($quoteTransfer);

        return [
            ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP => $this->createAvailableMethodsByShipmentChoiceList($quoteTransfer),
            ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST => $this->getShippingAddressLabelList($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getShippingAddressLabelList(QuoteTransfer $quoteTransfer): array
    {
        $shippingAddressLabelList = [];

        $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            if ($shipmentGroupTransfer->getShipment() === null
                || $shipmentGroupTransfer->getShipment()->getShippingAddress() === null
            ) {
                continue;
            }

            $shippingAddressLabelList[$shipmentGroupTransfer->getHash()] = $this->getShippingAddressLabel($shipmentGroupTransfer->getShipment()->getShippingAddress());
        }

        return $shippingAddressLabelList;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getShippingAddressLabel(AddressTransfer $addressTransfer): string
    {
        return sprintf(
            '%s %s %s, %s %s, %s %s',
            $addressTransfer->getSalutation(),
            $addressTransfer->getFirstName(),
            $addressTransfer->getLastName(),
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity()
        );
    }

    /**
     * @deprecated Use createAvailableMethodsByShipmentChoiceList() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[][]
     */
    protected function createAvailableShipmentChoiceList(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethods = [];

        $shipmentMethodsTransfer = $this->getAvailableShipmentMethods($quoteTransfer);
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            $carrierName = $shipmentMethodTransfer->getCarrierName();

            if ($carrierName === null) {
                continue;
            }

            $shipmentMethods[$carrierName] = $shipmentMethods[$carrierName] ?? [];

            $description = $this->getShipmentDescription($shipmentMethodTransfer);
            $shipmentMethods[$carrierName][$description] = $shipmentMethodTransfer->getIdShipmentMethod();
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

        $shipmentGroupCollectionTransfer = $this->getAvailableMethodsByShipment($quoteTransfer);

        foreach ($shipmentGroupCollectionTransfer->getGroups() as $shipmentGroupTransfer) {
            $shipmentMethodsTransfer = $shipmentGroupTransfer->getAvailableShipmentMethods();
            if ($shipmentMethodsTransfer === null) {
                continue;
            }

            foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
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
        if ($this->can('SeePricePermissionPlugin')) {
            $shipmentDescription = $this->appendShipmentPrice($shipmentMethodTransfer, $shipmentDescription);
        }

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
                $this->getTranslatedDayName($deliveryTime)
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

        return (int)($method->getDeliveryTime() / static::SECONDS_IN_ONE_DAY);
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
        return $this->glossaryStorageClient->translate($translationKey, $this->store->getCurrentLocale());
    }

    /**
     * @param int $deliveryTime
     *
     * @return string
     */
    protected function getTranslatedDayName(int $deliveryTime): string
    {
        if ($deliveryTime === static::ONE_DAY) {
            return $this->translate('page.checkout.shipping.day');
        }

        return $this->translate('page.checkout.shipping.days');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $quoteTransfer->setShipmentGroups($shipmentGroupTransfers);

        return $quoteTransfer;
    }
}
