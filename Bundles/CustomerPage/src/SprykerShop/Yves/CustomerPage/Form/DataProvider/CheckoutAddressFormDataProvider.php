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
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm;

class CheckoutAddressFormDataProvider extends AbstractAddressFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var bool
     */
    protected $isMultipleShipmentEnabled;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface $customerClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface $customerService
     * @param bool $isMultipleShipmentEnabled
     */
    public function __construct(
        CustomerPageToCustomerClientInterface $customerClient,
        Store $store,
        CustomerPageToCustomerServiceInterface $customerService,
        bool $isMultipleShipmentEnabled
    ) {
        parent::__construct($customerClient, $store);

        $this->customerService = $customerService;
        $this->customerTransfer = $this->getCustomer();
        $this->isMultipleShipmentEnabled = $isMultipleShipmentEnabled;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
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
        $quoteTransfer->setIsMultipleShipmentEnabled($this->isMultipleShipmentEnabled);

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
            CheckoutAddressCollectionForm::OPTION_ADDRESS_CHOICES => $this->getAddressChoices(),
            CheckoutAddressCollectionForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
            CheckoutAddressCollectionForm::OPTION_IS_MULTI_SHIPMENT_USED => $this->isMultiShipmentUsed($quoteTransfer),
        ];
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
        $addressTransfer = new AddressTransfer();
        if ($this->isShippingAddressInQuote($quoteTransfer)) {
            $addressTransfer = $quoteTransfer->getShippingAddress();
        }

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
        $addressTransfer = new AddressTransfer();
        if ($this->isBillingAddressInQuote($quoteTransfer)) {
            $addressTransfer = $quoteTransfer->getBillingAddress();
        }

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
            || $billingAddressTransfer->getIdCompanyUnitAddress() !== null;
    }

    /**
     * @return array
     */
    protected function getAddressChoices()
    {
        if ($this->customerTransfer === null) {
            return [];
        }

        $customerAddressesTransfer = $this->customerTransfer->getAddresses();

        if ($customerAddressesTransfer === null) {
            return [];
        }

        $choices = [];
        foreach ($customerAddressesTransfer->getAddresses() as $address) {
            $choices[$address->getIdCustomerAddress()] = sprintf(
                '%s %s %s, %s %s, %s %s',
                $address->getSalutation(),
                $address->getFirstName(),
                $address->getLastName(),
                $address->getAddress1(),
                $address->getAddress2(),
                $address->getZipCode(),
                $address->getCity()
            );
        }

        return $choices;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getItemShipment(ItemTransfer $itemTransfer): ShipmentTransfer
    {
        $shipmentTransfer = $itemTransfer->getShipment();
        if ($shipmentTransfer === null) {
            $shipmentTransfer = new ShipmentTransfer();
        }

        $shipmentTransfer->setShippingAddress($this->getShipmentShippingAddress($shipmentTransfer));

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
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getFirstItemLevelShippingAddress(QuoteTransfer $quoteTransfer): AddressTransfer
    {
        $firstItemTransfer = $quoteTransfer->getItems()[0];
        $firstItemTransfer->requireShipment();

        return $firstItemTransfer->getShipment()->getShippingAddress();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setItemLevelShippingAddresses(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null
                && $itemTransfer->getShipment()->getShippingAddress() !== null
            ) {
                continue;
            }

            $itemTransfer->setShipment($this->getItemShipment($itemTransfer));
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
        $shippingAddressTransfer = $this->getFirstItemLevelShippingAddress($quoteTransfer);
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
    protected function isMultiShipmentUsed(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getItems()->count() > 1 && $quoteTransfer->getIsMultipleShipmentEnabled();
    }
}
