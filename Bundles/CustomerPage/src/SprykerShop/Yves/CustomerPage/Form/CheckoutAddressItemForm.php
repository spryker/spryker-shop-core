<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Closure;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutAddressItemForm extends AbstractType
{
    public const FIELD_IS_ADDRESS_SAVING_SKIPPED = 'isAddressSavingSkipped';
    public const FIELD_SHIPMENT_SHIPPING_ADDRESS = 'shippingAddress';

    public const OPTION_ADDRESS_CHOICES = 'address_choices';
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    protected const GLOSSARY_KEY_SAVE_NEW_ADDRESS = 'customer.address.save_new_address';

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
            'data_class' => ItemTransfer::class,
        ]);

        $resolver->setDefined(static::OPTION_ADDRESS_CHOICES);
        $resolver->setDefined(static::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addShipmentField($builder, $options)
            ->addIsAddressSavingSkippedField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressItemForm
     */
    protected function addShipmentField(FormBuilderInterface $builder, array $options): CheckoutAddressItemForm
    {
        $builder->add(static::FIELD_SHIPMENT_SHIPPING_ADDRESS, CheckoutAddressForm::class, [
            'property_path' => 'shipment.shippingAddress',
            'data_class' => AddressTransfer::class,
            'required' => true,
            'validation_groups' => function (FormInterface $form) {
                $quoteIdCustomerAddress = $form->getParent()
                    ->getParent()
                    ->getParent()
                    ->get(CheckoutAddressCollectionForm::FIELD_SHIPPING_ADDRESS)
                    ->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
                    ->getData();

                if ($quoteIdCustomerAddress !== CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES) {
                    return false;
                }

                if ($form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS) === true
                    && $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() === null
                ) {
                    return [CheckoutAddressCollectionForm::GROUP_SHIPPING_ADDRESS];
                }

                return false;
            },
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => CheckoutAddressCollectionForm::GROUP_SHIPPING_ADDRESS,
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $options[static::OPTION_ADDRESS_CHOICES],
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressItemForm
     */
    protected function addIsAddressSavingSkippedField(FormBuilderInterface $builder): CheckoutAddressItemForm
    {
        $isLoggedIn = $this->getFactory()
            ->getCustomerClient()
            ->isLoggedIn();

        if (!$isLoggedIn) {
            return $this;
        }

        $builder->add(static::FIELD_IS_ADDRESS_SAVING_SKIPPED, CheckboxType::class, [
            'label' => static::GLOSSARY_KEY_SAVE_NEW_ADDRESS,
            'required' => false,
        ]);

        $callbackTransformer = new CallbackTransformer(
            $this->getInvertedBooleanValueCallbackTransformer(),
            $this->getInvertedBooleanValueCallbackTransformer()
        );

        $builder->get(static::FIELD_IS_ADDRESS_SAVING_SKIPPED)
            ->addModelTransformer($callbackTransformer);

        return $this;
    }

    /**
     * @return Closure
     */
    protected function getInvertedBooleanValueCallbackTransformer(): Closure
    {
        return function (?bool $value): bool {
            return !$value;
        };
    }
}
