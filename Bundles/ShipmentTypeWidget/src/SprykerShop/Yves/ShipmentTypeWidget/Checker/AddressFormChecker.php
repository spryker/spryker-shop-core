<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig;
use Symfony\Component\Form\FormInterface;

class AddressFormChecker implements AddressFormCheckerInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS
     *
     * @var string
     */
    protected const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm::FIELD_SHIPPING_ADDRESS
     *
     * @var string
     */
    protected const FIELD_SHIPPING_ADDRESS = 'shippingAddress';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES
     *
     * @var string
     */
    protected const VALUE_DELIVER_TO_MULTIPLE_ADDRESSES = '-1';

    /**
     * @var \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig
     */
    protected ShipmentTypeWidgetConfig $shipmentTypeWidgetConfig;

    /**
     * @param \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig $shipmentTypeWidgetConfig
     */
    public function __construct(ShipmentTypeWidgetConfig $shipmentTypeWidgetConfig)
    {
        $this->shipmentTypeWidgetConfig = $shipmentTypeWidgetConfig;
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $data
     *
     * @return bool
     */
    public function isApplicableForShipmentTypeAddressStepFormHydration(?AbstractTransfer $data): bool
    {
        return $data instanceof QuoteTransfer
            || $data instanceof ItemTransfer && $this->checkNotApplicableShipmentTypeAddressStepFormItemPropertiesForHydration($data);
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
    protected function checkNotApplicableShipmentTypeAddressStepFormItemPropertiesForHydration(ItemTransfer $itemTransfer): bool
    {
        foreach ($this->shipmentTypeWidgetConfig->getNotApplicableShipmentTypeAddressStepFormItemPropertiesForHydration() as $notApplicableItemProperty) {
            if ($itemTransfer->offsetGet($notApplicableItemProperty) !== null) {
                return false;
            }
        }

        return true;
    }
}
