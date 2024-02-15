<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointAddressStepForm;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointSubForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class ServicePointFormValidator implements ServicePointFormValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SERVICE_POINT_NOT_SELECTED = 'service_point_widget.validation.error.service_point_not_selected';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_BILLING_ADDRESS_NOT_PROVIDED = 'service_point_widget.validation.error.billing_address_not_provided';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm::FIELD_BILLING_SAME_AS_SHIPPING
     *
     * @var string
     */
    protected const FIELD_BILLING_SAME_AS_SHIPPING = 'billingSameAsShipping';

    /**
     * @var \SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface
     */
    protected AddressFormCheckerInterface $addressFormChecker;

    /**
     * @param \SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface $addressFormChecker
     */
    public function __construct(AddressFormCheckerInterface $addressFormChecker)
    {
        $this->addressFormChecker = $addressFormChecker;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    public function validateSubmittedData(AbstractTransfer $data, FormInterface $form): void
    {
        $this->assertServicePointSelection($data, $form);
        $this->assertBillingAddress($data, $form);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function assertServicePointSelection(AbstractTransfer $data, FormInterface $form): void
    {
        if (!$this->addressFormChecker->hasShipmentTypes($form)) {
            return;
        }

        if (!$this->checkCorrespondenceOfSubmittedData($data, $form)) {
            return;
        }

        $servicePointUuid = $form->get(ServicePointAddressStepForm::FIELD_SERVICE_POINT)
            ->get(ServicePointSubForm::FIELD_SERVICE_POINT_UUID)
            ->getData();

        if ($this->addressFormChecker->isShipmentTypeDelivery($form) || $servicePointUuid) {
            return;
        }

        $form->get(ServicePointAddressStepForm::FIELD_SERVICE_POINT)
            ->get(ServicePointSubForm::FIELD_SERVICE_POINT_UUID)
            ->addError(new FormError(static::GLOSSARY_KEY_SERVICE_POINT_NOT_SELECTED));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function assertBillingAddress(AbstractTransfer $data, FormInterface $form): void
    {
        if ($data instanceof ItemTransfer || !$this->checkCorrespondenceOfSubmittedData($data, $form)) {
            return;
        }

        if (
            !$this->addressFormChecker->isBillingAddressTheSameAsShipping($form)
            || $this->addressFormChecker->isShipmentTypeDelivery($form)
            || !$this->addressFormChecker->hasShipmentTypes($form)
        ) {
            return;
        }

        $form->getRoot()
            ->get(static::FIELD_BILLING_SAME_AS_SHIPPING)
            ->addError(new FormError(static::GLOSSARY_KEY_BILLING_ADDRESS_NOT_PROVIDED));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function checkCorrespondenceOfSubmittedData(AbstractTransfer $data, FormInterface $form): bool
    {
        return $data instanceof QuoteTransfer && !$this->addressFormChecker->isDeliverToMultipleAddresses($form)
            || $data instanceof ItemTransfer && $this->addressFormChecker->isDeliverToMultipleAddresses($form);
    }
}
