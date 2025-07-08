<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig;
use Symfony\Component\Form\FormInterface;

class AddressFormChecker implements AddressFormCheckerInterface
{
    /**
     * @uses \SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPE = 'shipmentType';

    /**
     * @uses \SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeSubForm::FIELD_SHIPMENT_TYPE_KEY
     *
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPE_KEY = 'key';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm::FIELD_SHIPPING_ADDRESS
     *
     * @var string
     */
    protected const FIELD_SHIPPING_ADDRESS = 'shippingAddress';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS
     *
     * @var string
     */
    protected const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm::FIELD_BILLING_SAME_AS_SHIPPING
     *
     * @var string
     */
    protected const FIELD_BILLING_SAME_AS_SHIPPING = 'billingSameAsShipping';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES
     *
     * @var string
     */
    protected const VALUE_DELIVER_TO_MULTIPLE_ADDRESSES = '-1';

    /**
     * @param \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig $servicePointWidgetConfig
     */
    public function __construct(protected ServicePointWidgetConfig $servicePointWidgetConfig)
    {
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function isDeliverToMultipleAddresses(FormInterface $form): bool
    {
        $shippingAddressForm = $this->findShippingAddressForm($form);

        if (!$shippingAddressForm) {
            return false;
        }

        if (!$shippingAddressForm->has(static::FIELD_ID_CUSTOMER_ADDRESS)) {
            return false;
        }

        return $shippingAddressForm->get(static::FIELD_ID_CUSTOMER_ADDRESS)->getData() == static::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function isShipmentTypeDelivery(FormInterface $form): bool
    {
        $shipmentTypeForm = $this->findShipmentTypeForm($form);

        if (!$shipmentTypeForm || !$shipmentTypeForm->has(static::FIELD_SHIPMENT_TYPE_KEY)) {
            return true;
        }

        $shipmentTypeKey = $shipmentTypeForm->get(static::FIELD_SHIPMENT_TYPE_KEY)->getData();

        return in_array($shipmentTypeKey, $this->servicePointWidgetConfig->getDeliveryShipmentTypeKeys(), true);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function hasShipmentTypes(FormInterface $form): bool
    {
        $shipmentTypeForm = $this->findShipmentTypeForm($form);

        if ($shipmentTypeForm !== null && $shipmentTypeForm->get(static::FIELD_SHIPMENT_TYPE_KEY)->all() === []) {
            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function isBillingAddressTheSameAsShipping(FormInterface $form): bool
    {
        $parentForm = $form->getRoot();

        if (
            !$parentForm->has(static::FIELD_BILLING_SAME_AS_SHIPPING)
            || !$parentForm->get(static::FIELD_BILLING_SAME_AS_SHIPPING)->getData()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $data
     *
     * @return bool
     */
    public function isApplicableForServicePointAddressStepFormHydration(?AbstractTransfer $data): bool
    {
        return $data instanceof QuoteTransfer
            || $data instanceof ItemTransfer && $this->checkNotApplicableServicePointAddressStepFormItemPropertiesForHydration($data);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\Form\FormInterface|null
     */
    protected function findShipmentTypeForm(FormInterface $form): ?FormInterface
    {
        return $form->has(static::FIELD_SHIPMENT_TYPE) ? $form->get(static::FIELD_SHIPMENT_TYPE) : null;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\Form\FormInterface|null
     */
    protected function findShippingAddressForm(FormInterface $form): ?FormInterface
    {
        $parentForm = $form->getRoot();

        if ($parentForm->has(static::FIELD_SHIPPING_ADDRESS)) {
            return $parentForm->get(static::FIELD_SHIPPING_ADDRESS);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function checkNotApplicableServicePointAddressStepFormItemPropertiesForHydration(ItemTransfer $itemTransfer): bool
    {
        foreach ($this->servicePointWidgetConfig->getNotApplicableServicePointAddressStepFormItemPropertiesForHydration() as $notApplicableItemProperty) {
            if ($itemTransfer->offsetGet($notApplicableItemProperty) !== null) {
                return false;
            }
        }

        return true;
    }
}
