<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm;

class CheckoutAddressFormDataProvider extends AbstractAddressFormDataProvider implements StepEngineFormDataProviderInterface
{
    protected const ADDRESS_LABEL_PATTERN = '%s %s %s, %s %s, %s %s';
    protected const SANITIZED_CUSTOMER_ADDRESS_LABEL_PATTERN = '%s - %s';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    protected const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface $customerClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface $customerService
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface $shipmentClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        CustomerPageToCustomerClientInterface $customerClient,
        Store $store,
        CustomerPageToCustomerServiceInterface $customerService,
        CheckoutPageToShipmentClientInterface $shipmentClient,
        CheckoutPageToProductBundleClientInterface $productBundleClient,
        CheckoutPageToShipmentServiceInterface $shipmentService
    ) {
        parent::__construct($customerClient, $store);

        $this->customerService = $customerService;
        $this->customerTransfer = $this->getCustomer();
        $this->shipmentClient = $shipmentClient;
        $this->productBundleClient = $productBundleClient;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        /**
         * @deprecated Exists for Backward Compatibility reasons only.
         */
        $quoteTransfer->setShippingAddress($this->getShippingAddress($quoteTransfer));
        $quoteTransfer->setBillingAddress($this->getBillingAddress($quoteTransfer));

        $quoteTransfer = $this->setItemLevelShippingAddresses($quoteTransfer);

        $quoteTransfer = $this->setBillingSameAsShipping($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = $this->setBundleItemLevelShippingAddresses($quoteTransfer);
        $canDeliverToMultipleShippingAddresses = $this->canDeliverToMultipleShippingAddresses($quoteTransfer);
        $defaultAddressChoices = $this->getAddressChoices();

        return [
            CheckoutAddressCollectionForm::OPTION_SINGLE_SHIPPING_ADDRESS_CHOICES => $this->getSingleShippingAddressChoices($defaultAddressChoices, $canDeliverToMultipleShippingAddresses),
            CheckoutAddressCollectionForm::OPTION_MULTIPLE_SHIPPING_ADDRESS_CHOICES => $defaultAddressChoices,
            CheckoutAddressCollectionForm::OPTION_BILLING_ADDRESS_CHOICES => $defaultAddressChoices,
            CheckoutAddressCollectionForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
            CheckoutAddressCollectionForm::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES => $canDeliverToMultipleShippingAddresses,
            CheckoutAddressCollectionForm::OPTION_IS_CUSTOMER_LOGGED_IN => $this->customerClient->isLoggedIn(),
            CheckoutAddressCollectionForm::OPTION_BUNDLE_ITEMS => $this->getBundleItemsFromQuote($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getBundleItemsFromQuote(QuoteTransfer $quoteTransfer): array
    {
        $groupedBundleItems = $this->productBundleClient->getGroupedBundleItems(
            $quoteTransfer->getItems(),
            $quoteTransfer->getBundleItems()
        );

        $bundleItems = [];

        foreach ($groupedBundleItems as $groupedBundleItem) {
            if (is_array($groupedBundleItem)) {
                $bundleItems[] = $groupedBundleItem[static::BUNDLE_PRODUCT];
            }
        }

        return $bundleItems;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer()
    {
        $this->customerClient->markCustomerAsDirty();

        return $this->customerClient->getCustomer();
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShippingAddress(QuoteTransfer $quoteTransfer): AddressTransfer
    {
        if ($this->isShippingAddressInQuote($quoteTransfer)) {
            return $quoteTransfer->getShippingAddress();
        }

        $addressTransfer = new AddressTransfer();
        if ($this->customerTransfer !== null) {
            $addressTransfer->setIdCustomerAddress($this->customerTransfer->getDefaultShippingAddress());
        }

        return $addressTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShippingAddressInQuote(QuoteTransfer $quoteTransfer): bool
    {
        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();

        if ($shippingAddressTransfer === null) {
            return false;
        }

        return $shippingAddressTransfer->getIdCustomerAddress() !== null
            || $shippingAddressTransfer->getIdCompanyUnitAddress() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getBillingAddress(QuoteTransfer $quoteTransfer): AddressTransfer
    {
        if ($this->isBillingAddressInQuote($quoteTransfer)) {
            return $quoteTransfer->getBillingAddress();
        }

        $addressTransfer = new AddressTransfer();
        if ($this->customerTransfer !== null) {
            $addressTransfer->setIdCustomerAddress($this->customerTransfer->getDefaultBillingAddress());
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isBillingAddressInQuote(QuoteTransfer $quoteTransfer): bool
    {
        $billingAddressTransfer = $quoteTransfer->getBillingAddress();

        if ($billingAddressTransfer === null) {
            return false;
        }

        return $billingAddressTransfer->getIdCustomerAddress() !== null
            || $billingAddressTransfer->getIdCompanyUnitAddress() !== null
            || !(empty(trim($billingAddressTransfer->getFirstName())) && empty($billingAddressTransfer->getLastName()));
    }

    /**
     * @return array
     */
    protected function getAddressChoices()
    {
        $choices = $this->getDefaultAddressChoices();
        if ($this->customerTransfer === null) {
            return $choices;
        }

        $customerAddressesTransfer = $this->customerTransfer->getAddresses();

        if ($customerAddressesTransfer === null || count($customerAddressesTransfer->getAddresses()) === 0) {
            return $choices;
        }

        $choices = $this->addCustomerAddressChoices($customerAddressesTransfer->getAddresses(), $choices);

        return $this->sanitizeDuplicatedCustomerAddressChoices($choices);
    }

    /**
     * @return array
     */
    protected function getDefaultAddressChoices(): array
    {
        return [
            CheckoutAddressForm::GLOSSARY_KEY_SAVE_NEW_ADDRESS => CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressLabel(AddressTransfer $addressTransfer): string
    {
        return sprintf(
            static::ADDRESS_LABEL_PATTERN,
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
     * @param iterable|\ArrayObject|\Generated\Shared\Transfer\AddressTransfer[] $customerAddressesCollection
     * @param array $choices
     *
     * @return string[]
     */
    protected function addCustomerAddressChoices(iterable $customerAddressesCollection, array $choices = []): array
    {
        foreach ($customerAddressesCollection as $addressTransfer) {
            $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
            if ($idCustomerAddress === null) {
                continue;
            }

            $choices[$idCustomerAddress] = $this->getAddressLabel($addressTransfer);
        }

        return $choices;
    }

    /**
     * @param iterable|string[] $choices
     *
     * @return string[]
     */
    protected function sanitizeDuplicatedCustomerAddressChoices(iterable $choices): array
    {
        $sanitizedChoices = [];
        $choicesCounts = [];

        foreach ($choices as $idAddress => $addressLabel) {
            if (isset($sanitizedChoices[$addressLabel])) {
                $originAddressLabel = $addressLabel;
                if (!isset($choicesCounts[$originAddressLabel])) {
                    $choicesCounts[$originAddressLabel] = 1;
                }

                $addressLabel = $this->getSanitizedCustomerAddressChoices($addressLabel, $choicesCounts[$originAddressLabel]);
                $choicesCounts[$originAddressLabel]++;
            }

            $sanitizedChoices[$addressLabel] = $idAddress;
        }

        ksort($sanitizedChoices, SORT_NATURAL);

        return $sanitizedChoices;
    }

    /**
     * @param string $addressLabel
     * @param int $itemNumber
     *
     * @return string
     */
    protected function getSanitizedCustomerAddressChoices(string $addressLabel, int $itemNumber): string
    {
        return sprintf(static::SANITIZED_CUSTOMER_ADDRESS_LABEL_PATTERN, $addressLabel, $itemNumber);
    }

    /**
     * @param array $customerAddressChoices
     * @param bool $canDeliverToMultipleShippingAddresses
     *
     * @return string[]
     */
    protected function getSingleShippingAddressChoices(array $customerAddressChoices, bool $canDeliverToMultipleShippingAddresses): array
    {
        if ($canDeliverToMultipleShippingAddresses === false) {
            return $customerAddressChoices;
        }

        $customerAddressChoices[CheckoutAddressForm::GLOSSARY_KEY_DELIVER_TO_MULTIPLE_ADDRESSES] = CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;

        return $customerAddressChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer|null $previouslySelectedShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getItemShipment(ItemTransfer $itemTransfer, ?ShipmentTransfer $previouslySelectedShipmentMethod): ShipmentTransfer
    {
        $shipmentTransfer = $itemTransfer->getShipment();
        if ($shipmentTransfer === null) {
            $shipmentTransfer = new ShipmentTransfer();
        }

        $shipmentShippingAddress = $this->getShipmentShippingAddress($shipmentTransfer);
        if ($previouslySelectedShipmentMethod !== null && !$this->isShippingAddressDefined($shipmentShippingAddress)) {
            return $previouslySelectedShipmentMethod;
        }

        $shipmentTransfer->setShippingAddress($shipmentShippingAddress);

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShipmentShippingAddress(ShipmentTransfer $shipmentTransfer): AddressTransfer
    {
        $addressTransfer = new AddressTransfer();
        if ($shipmentTransfer->getShippingAddress() !== null) {
            $addressTransfer = $shipmentTransfer->getShippingAddress();
        }

        if ($this->customerTransfer !== null && $shipmentTransfer->getShippingAddress() === null) {
            $addressTransfer->setIdCustomerAddress($this->customerTransfer->getDefaultShippingAddress());
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setItemLevelShippingAddresses(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $previouslySelectedShipmentMethod = $this->resolveShipmentForSingleAddressDelivery($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                $itemTransfer->getShipment() !== null
                && $itemTransfer->getShipment()->getShippingAddress() !== null
            ) {
                continue;
            }

            $itemTransfer->setShipment($this->getItemShipment($itemTransfer, $previouslySelectedShipmentMethod));
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setBundleItemLevelShippingAddresses(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() && $itemTransfer->getShipment()->getShippingAddress()) {
                continue;
            }

            $shipmentTransfer = $itemTransfer->getShipment() ?? new ShipmentTransfer();
            $shipmentTransfer->setShippingAddress(new AddressTransfer());

            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setBillingSameAsShipping(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()
            ->getIterator()
            ->current();

        $itemTransfer->requireShipment();

        $shippingAddressTransfer = $itemTransfer->getShipment()->getShippingAddress();

        $shippingAddressHashKey = $this->customerService->getUniqueAddressKey($shippingAddressTransfer);
        $billingAddressHashKey = $this->customerService->getUniqueAddressKey($quoteTransfer->getBillingAddress());

        if ($billingAddressHashKey === $shippingAddressHashKey) {
            $quoteTransfer->setBillingSameAsShipping(true);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canDeliverToMultipleShippingAddresses(QuoteTransfer $quoteTransfer): bool
    {
        $items = $this->productBundleClient->getGroupedBundleItems(
            $quoteTransfer->getItems(),
            $quoteTransfer->getBundleItems()
        );

        return count($items) > 1
            && $this->shipmentClient->isMultiShipmentSelectionEnabled()
            && !$this->hasQuoteGiftCardItems($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasQuoteGiftCardItems(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $giftCardMetadataTransfer = $itemTransfer->getGiftCardMetadata();
            if ($giftCardMetadataTransfer === null) {
                continue;
            }

            if ($giftCardMetadataTransfer->getIsGiftCard()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function resolveShipmentForSingleAddressDelivery(QuoteTransfer $quoteTransfer): ?ShipmentTransfer
    {
        if ($quoteTransfer->getItems()->count() === 0) {
            return null;
        }

        $shipmentGroups = $this->shipmentService->groupItemsByShipment(
            $this->filterQuoteItemsWithShipment($quoteTransfer->getItems()->getArrayCopy())
        );
        if ($shipmentGroups->count() !== 1) {
            return null;
        }

        return $shipmentGroups->offsetGet(0)->getShipment();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterQuoteItemsWithShipment(array $itemTransfers): array
    {
        return array_filter($itemTransfers, function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getShipment() !== null;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $shipmentShippingAddress
     *
     * @return bool
     */
    protected function isShippingAddressDefined(AddressTransfer $shipmentShippingAddress): bool
    {
        return $shipmentShippingAddress->getIdCustomerAddress() || $shipmentShippingAddress->getIdCompanyUnitAddress();
    }
}
