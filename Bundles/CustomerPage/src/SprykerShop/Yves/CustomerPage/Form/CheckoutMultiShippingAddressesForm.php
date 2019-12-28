<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutMultiShippingAddressesForm extends AbstractType
{
    public const FIELD_SHIPPING_ADDRESS = 'shippingAddress';

    public const OPTION_ADDRESS_CHOICES = 'address_choices';
    public const OPTION_COUNTRY_CHOICES = 'country_choices';
    public const OPTION_IS_CUSTOMER_LOGGED_IN = 'is_customer_logged_in';
    public const OPTION_VALIDATION_GROUP = 'validation_group';

    protected const PROPERTY_PATH_SHIPPING_ADDRESS = 'shipment.shippingAddress';

    /**
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return 'checkout_address_item';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_ADDRESS_CHOICES => [],
        ]);

        $resolver->setDefined(static::OPTION_ADDRESS_CHOICES)
            ->setRequired(static::OPTION_COUNTRY_CHOICES)
            ->setRequired(static::OPTION_IS_CUSTOMER_LOGGED_IN)
            ->setRequired(static::OPTION_VALIDATION_GROUP);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addShippingAddressField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShippingAddressField(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $this->addShippingAddressFieldForRegularItem($event, $options);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array $options
     *
     * @return void
     */
    protected function addShippingAddressFieldForRegularItem(FormEvent $event, array $options): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $event->getData();
        $form = $event->getForm();

        if ($itemTransfer->getRelatedBundleItemIdentifier()) {
            return;
        }

        $form->add(static::FIELD_SHIPPING_ADDRESS, CheckoutAddressForm::class, [
            'property_path' => static::PROPERTY_PATH_SHIPPING_ADDRESS,
            'data_class' => AddressTransfer::class,
            'required' => true,
            'validation_groups' => function (FormInterface $form) {
                $customerAddressForm = $form->getParent()
                    ->getParent()
                    ->getParent()
                    ->get(CheckoutAddressCollectionForm::FIELD_SHIPPING_ADDRESS);

                if (!$this->isDeliverToMultipleAddressesEnabled($customerAddressForm)) {
                    return false;
                }

                if ($this->isNewAddressFormShouldNotBeValidated($customerAddressForm)) {
                    return false;
                }

                if ($this->isNewAddressFormShouldNotBeValidated($form)) {
                    return false;
                }

                return [CheckoutAddressCollectionForm::GROUP_SHIPPING_ADDRESS];
            },
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => $options[static::OPTION_VALIDATION_GROUP],
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $options[static::OPTION_ADDRESS_CHOICES],
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
            CheckoutAddressForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isDeliverToMultipleAddressesEnabled(FormInterface $form): bool
    {
        if ($form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS) !== true) {
            return false;
        }

        $idCustomerAddress = $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData();

        return $idCustomerAddress == CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $customerAddressForm
     *
     * @return bool
     */
    protected function isIdCustomerOrCompanyUnitAddressesExist(FormInterface $customerAddressForm): bool
    {
        return $customerAddressForm->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            || $customerAddressForm->has(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isNewCustomerAddress(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() === null;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCustomerAddressEmpty(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() === CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCompanyUnitAddressEmpty(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)->getData() === null;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isNewAddressFormShouldNotBeValidated(FormInterface $form): bool
    {
        if ($this->isNewCustomerAddress($form)
            || $this->isIdCustomerAddressEmpty($form)
            || $this->isIdCompanyUnitAddressEmpty($form)
        ) {
            return true;
        }

        return false;
    }
}
