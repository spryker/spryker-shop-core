<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Widget;

use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointAddressStepForm;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig getConfig()
 */
class ClickCollectServicePointAddressFormWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @uses \SprykerShop\Yves\ServicePointWidget\Form\ClickCollectServiceTypeSubForm::FIELD_PICKUPABLE_SERVICE_TYPE
     *
     * @var string
     */
    protected const PARAMETER_PICKUPABLE_SERVICE_TYPE = 'pickupableServiceType';

    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_POINT_FORM = 'servicePointForm';

    /**
     * @var string
     */
    protected const PARAMETER_SELECTED_SERVICE_POINT = 'selectedServicePoint';

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
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     */
    public function __construct(FormView $checkoutAddressForm)
    {
        $this->addIsVisibleParameter($checkoutAddressForm);
        $this->addPickupableTypeParameter($checkoutAddressForm);
        $this->addServicePointFormParameter($checkoutAddressForm);
        $this->addSelectedServicePointParameter($checkoutAddressForm);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ClickCollectServicePointAddressFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ServicePointWidget/views/click-collect-service-point-address-form/click-collect-service-point-address-form.twig';
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addPickupableTypeParameter(FormView $checkoutAddressForm): void
    {
        $this->addParameter(
            static::PARAMETER_PICKUPABLE_SERVICE_TYPE,
            $this->getPickupableServiceType($checkoutAddressForm),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addServicePointFormParameter(FormView $checkoutAddressForm): void
    {
        $this->addParameter(static::PARAMETER_SERVICE_POINT_FORM, $this->getServicePointForm($checkoutAddressForm));
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
            $this->getPickupableServiceType($checkoutAddressForm) && $this->assertClickCollectShipmentTypes($checkoutAddressForm),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addSelectedServicePointParameter(FormView $checkoutAddressForm): void
    {
        $servicePointForm = $this->getServicePointForm($checkoutAddressForm);

        if (!$servicePointForm || !$this->isServicePointWithAddress($servicePointForm)) {
            return;
        }

        $this->addParameter(static::PARAMETER_SELECTED_SERVICE_POINT, $servicePointForm->vars['value']);
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return \Symfony\Component\Form\FormView|null
     */
    protected function getServicePointForm(FormView $checkoutAddressForm): ?FormView
    {
        return $checkoutAddressForm->children[ServicePointAddressStepForm::FIELD_SERVICE_POINT] ?? null;
    }

    /**
     * @param \Symfony\Component\Form\FormView $servicePointForm
     *
     * @return bool
     */
    protected function isServicePointWithAddress(FormView $servicePointForm): bool
    {
        return $servicePointForm->vars['value']
            && $servicePointForm->vars['value'] instanceof ServicePointTransfer
            && $this->hasServicePointAddress($servicePointForm->vars['value']);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return bool
     */
    protected function hasServicePointAddress(ServicePointTransfer $servicePointTransfer): bool
    {
        $servicePointAddressTransfer = $servicePointTransfer->getAddress();

        if (!$servicePointAddressTransfer) {
            return false;
        }

        return $servicePointAddressTransfer->getAddress1()
            && $servicePointAddressTransfer->getAddress2()
            && $servicePointAddressTransfer->getCity()
            && $servicePointAddressTransfer->getZipCode()
            && $servicePointAddressTransfer->getCountry();
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return string|null
     */
    protected function getPickupableServiceType(FormView $checkoutAddressForm): ?string
    {
        $servicePointForm = $this->getServicePointForm($checkoutAddressForm);
        if (!$servicePointForm) {
            return null;
        }

        $pickupableServiceType = $servicePointForm->children[static::PARAMETER_PICKUPABLE_SERVICE_TYPE] ?? null;
        if (!$pickupableServiceType) {
            return null;
        }

        return $pickupableServiceType->vars['value'] ?? null;
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return bool
     */
    protected function assertClickCollectShipmentTypes(FormView $checkoutAddressForm): bool
    {
        $shipmentTypeForm = $checkoutAddressForm->children[static::FIELD_SHIPMENT_TYPE] ?? null;
        if (!$shipmentTypeForm) {
            return false;
        }

        $shipmentTypeSubForm = $shipmentTypeForm->children[static::FIELD_SHIPMENT_TYPE_KEY] ?? null;
        if (!$shipmentTypeSubForm || !isset($shipmentTypeSubForm->vars['choices'])) {
            return false;
        }

        $shipmentTypeKeys = [];
        /** @var \Symfony\Component\Form\ChoiceList\View\ChoiceView $choiceView */
        foreach ($shipmentTypeSubForm->vars['choices'] as $choiceView) {
            $shipmentTypeKeys[] = $choiceView->value;
        }

        $clickAndCollectShipmentTypes = $this->getConfig()->getClickAndCollectShipmentTypes();

        return !array_diff($shipmentTypeKeys, $clickAndCollectShipmentTypes)
            && !array_diff($clickAndCollectShipmentTypes, $shipmentTypeKeys);
    }
}
