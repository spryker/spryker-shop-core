<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Form\Steps\ShipmentCollectionForm;
use SprykerShop\Yves\CheckoutPage\Form\Steps\ShipmentForm;

class ShipmentFormDataProvider implements StepEngineFormDataProviderInterface
{
    use PermissionAwareTrait;

    protected const ONE_DAY = 1;
    protected const SECONDS_IN_ONE_DAY = 86400;

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
     * @var \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected $checkoutPageConfig;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepPreGroupItemsByShipmentPluginInterface[]
     */
    protected $preGroupItemsByShipmentPlugins;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepPreGroupItemsByShipmentPluginInterface[] $preGroupItemsByShipmentPlugins
     */
    public function __construct(
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient,
        Store $store,
        MoneyPluginInterface $moneyPlugin,
        CheckoutPageToShipmentServiceInterface $shipmentService,
        CheckoutPageConfig $checkoutPageConfig,
        CheckoutPageToProductBundleClientInterface $productBundleClient,
        array $preGroupItemsByShipmentPlugins
    ) {
        $this->shipmentClient = $shipmentClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->store = $store;
        $this->moneyPlugin = $moneyPlugin;
        $this->shipmentService = $shipmentService;
        $this->checkoutPageConfig = $checkoutPageConfig;
        $this->productBundleClient = $productBundleClient;
        $this->preGroupItemsByShipmentPlugins = $preGroupItemsByShipmentPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        $defaultShipmentTransfer = new ShipmentTransfer();
        $defaultShipmentTransfer->setShippingAddress(new AddressTransfer());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                $itemTransfer->setShipment($defaultShipmentTransfer);
            }
        }

        $quoteTransfer = $this->setQuoteShipment($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        foreach ($this->preGroupItemsByShipmentPlugins as $preGroupItemsByShipmentPlugin) {
            $quoteTransfer = $preGroupItemsByShipmentPlugin->preGroupItemsByShipment($quoteTransfer);
        }

        $shipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $shipmentGroupCollection = $this->expandShipmentGroupsWithCartItems($shipmentGroupCollection, $quoteTransfer);
        $shipmentGroupCollection = $this->filterGiftCardForShipmentGroupCollection($shipmentGroupCollection);

        $options = [
            ShipmentCollectionForm::OPTION_SHIPMENT_GROUPS => $shipmentGroupCollection,
            ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST => $this->getShippingAddressLabelList($shipmentGroupCollection),
            ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP => $this->createAvailableMethodsByShipmentChoiceList($quoteTransfer, $shipmentGroupCollection),
        ];

        /**
         * @deprecated Exists for Backward Compatibility reasons only.
         */
        $options[ShipmentForm::OPTION_SHIPMENT_METHODS] = $this->createAvailableShipmentChoiceList($quoteTransfer);

        return $options;
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
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return string[]
     */
    protected function getShippingAddressLabelList(iterable $shipmentGroupCollection): array
    {
        $shippingAddressLabelList = [];

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            if ($shipmentGroupTransfer->getShipment() === null) {
                continue;
            }

            $shippingAddressTransfer = $shipmentGroupTransfer->getShipment()->getShippingAddress();
            if ($shippingAddressTransfer === null) {
                continue;
            }

            $shippingAddressLabelList[$shipmentGroupTransfer->getHash()] = $this->getShippingAddressLabel($shippingAddressTransfer);
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
     * @deprecated Use getAvailableMethodsByShipment() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableShipmentMethods(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ShipmentMethodsTransfer|null $shipmentMethodsTransfer */
        $shipmentMethodsTransfer = $this->shipmentClient
            ->getAvailableMethodsByShipment($quoteTransfer)
            ->getShipmentMethods()
            ->getIterator()
            ->current();

        if (!$shipmentMethodsTransfer) {
            return new ShipmentMethodsTransfer();
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return array
     */
    protected function createAvailableMethodsByShipmentChoiceList(
        QuoteTransfer $quoteTransfer,
        iterable $shipmentGroupCollection
    ): array {
        $shipmentMethods = [];
        $shipmentMethodsTransferCollection = $this->getAvailableShipmentMethodsByShipment($quoteTransfer);

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentMethodsTransfer = $this->findAvailableShipmentMethodsByShipmentGroup(
                $shipmentMethodsTransferCollection,
                $shipmentGroupTransfer
            );
            if ($shipmentMethodsTransfer === null) {
                continue;
            }

            $shipmentHashKey = $shipmentGroupTransfer->getHash();
            foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
                $shipmentMethodCarrierName = $shipmentMethodTransfer->getCarrierName();

                if (!isset($shipmentMethods[$shipmentHashKey][$shipmentMethodCarrierName])) {
                    $shipmentMethods[$shipmentHashKey][$shipmentMethodCarrierName] = [];
                }

                $description = $this->getShipmentDescription($shipmentMethodTransfer);
                $shipmentMethods[$shipmentHashKey][$shipmentMethodCarrierName][$description] = $shipmentMethodTransfer->getIdShipmentMethod();
            }
        }

        return $shipmentMethods;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|null
     */
    protected function findAvailableShipmentMethodsByShipmentGroup(
        ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ?ShipmentMethodsTransfer {
        $shipmentHashKey = $shipmentGroupTransfer->getHash();
        foreach ($shipmentMethodsCollectionTransfer->getShipmentMethods() as $shipmentMethodsTransfer) {
            if ($shipmentHashKey === $shipmentMethodsTransfer->getShipmentHash()) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    protected function getAvailableShipmentMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
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
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setShipment(new ShipmentTransfer());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function filterGiftCardForShipmentGroupCollection(ArrayObject $shipmentGroupCollection): ArrayObject
    {
        $updatedShipmentGroups = [];

        foreach ($shipmentGroupCollection as $shipmentGroupIndex => $shipmentGroupTransfer) {
            $shipmentGroupTransfer->setItems($this->removeGiftCardItem($shipmentGroupTransfer->getItems()));

            if ($shipmentGroupTransfer->getItems()->count() === 0) {
                continue;
            }

            $updatedShipmentGroups[] = $shipmentGroupTransfer;
        }

        return new ArrayObject($updatedShipmentGroups);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function removeGiftCardItem(ArrayObject $itemTransfers): ArrayObject
    {
        $updatedItemTransfers = [];

        foreach ($itemTransfers as $itemIndex => $itemTransfer) {
            $giftCardMetadataTransfer = $itemTransfer->getGiftCardMetadata();

            if ($giftCardMetadataTransfer && $giftCardMetadataTransfer->getIsGiftCard()) {
                continue;
            }

            $updatedItemTransfers[] = $itemTransfer;
        }

        return new ArrayObject($updatedItemTransfers);
    }
}
