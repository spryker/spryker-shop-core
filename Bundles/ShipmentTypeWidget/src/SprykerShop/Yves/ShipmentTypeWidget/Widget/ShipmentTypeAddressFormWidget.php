<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeAddressStepForm;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig getConfig()
 */
class ShipmentTypeAddressFormWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_TYPE_FORM = 'shipmentTypeForm';

    /**
     * @var string
     */
    protected const PARAMETER_CHECKOUT_ADDRESS_FORM = 'checkoutAddressForm';

    /**
     * @var string
     */
    protected const PARAMETER_DEFAULT_SHIPMENT_TYPE = 'defaultShipmentType';

    /**
     * @var string
     */
    protected const PARAMETER_DELIVERY_SHIPMENT_TYPES = 'deliveryShipmentTypes';

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     */
    public function __construct(FormView $checkoutAddressForm)
    {
        $this->addIsVisibleParameter($checkoutAddressForm);
        $this->addShipmentTypeFormParameter($checkoutAddressForm);
        $this->addCheckoutAddressFormParameter($checkoutAddressForm);
        $this->addDefaultShipmentTypeParameter();
        $this->addDeliveryShipmentTypesParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShipmentTypeAddressFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShipmentTypeWidget/views/shipment-type-address-form/shipment-type-address-form.twig';
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addIsVisibleParameter(FormView $checkoutAddressForm): void
    {
        $this->addParameter(
            static::PARAMETER_IS_VISIBLE,
            isset($checkoutAddressForm->children[ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE]),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addShipmentTypeFormParameter(FormView $checkoutAddressForm): void
    {
        $this->addParameter(
            static::PARAMETER_SHIPMENT_TYPE_FORM,
            $checkoutAddressForm->children[ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE] ?? null,
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addCheckoutAddressFormParameter(FormView $checkoutAddressForm): void
    {
        $this->addParameter(static::PARAMETER_CHECKOUT_ADDRESS_FORM, $checkoutAddressForm);
    }

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ShipmentTypeWidget\Widget\ShipmentTypeAddressFormWidget::addDeliveryShipmentTypesParameter()} instead.
     *
     * @return void
     */
    protected function addDefaultShipmentTypeParameter(): void
    {
        $this->addParameter(static::PARAMETER_DEFAULT_SHIPMENT_TYPE, $this->getConfig()->getDefaultShipmentType());
    }

    /**
     * @return void
     */
    protected function addDeliveryShipmentTypesParameter(): void
    {
        $this->addParameter(static::PARAMETER_DELIVERY_SHIPMENT_TYPES, $this->getConfig()->getDeliveryShipmentTypes());
    }
}
