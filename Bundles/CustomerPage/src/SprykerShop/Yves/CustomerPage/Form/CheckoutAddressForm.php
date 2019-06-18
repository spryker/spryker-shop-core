<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Closure;
use Generated\Shared\Transfer\AddressTransfer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CheckoutAddressForm extends AddressForm
{
    public const FIELD_IS_ADDRESS_SAVING_SKIPPED = 'isAddressSavingSkipped';

    public const OPTION_VALIDATION_GROUP = 'validation_group';
    public const OPTION_ADDRESS_CHOICES = 'addresses_choices';

    public const VALUE_DELIVER_TO_MULTIPLE_ADDRESSES = -1;

    protected const GLOSSARY_KEY_SAVE_NEW_ADDRESS = 'customer.address.save_new_address';

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
            'data_class' => AddressTransfer::class,
            'allow_extra_fields' => true,
        ]);

        $resolver->setRequired(static::OPTION_VALIDATION_GROUP);
        $resolver->setDefined(static::OPTION_ADDRESS_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addAddressSelectField($builder, $options)
            ->addSalutationField($builder, $options)
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addCompanyField($builder)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addPhoneField($builder)
            ->addIdCompanyUnitAddressTextField($builder)
            ->addIsAddressSavingSkippedField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAddressSelectField(FormBuilderInterface $builder, array $options)
    {
        if (count($options[static::OPTION_ADDRESS_CHOICES]) === 0) {
            return $this;
        }

        $choices = $options[static::OPTION_ADDRESS_CHOICES];

        $builder->add(static::FIELD_ID_CUSTOMER_ADDRESS, ChoiceType::class, [
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'required' => false,
            'placeholder' => 'customer.account.add_new_address',
            'label' => 'page.checkout.address.address_select',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyUnitAddressTextField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_UNIT_ADDRESS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsAddressSavingSkippedField(FormBuilderInterface $builder)
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
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(array $options): NotBlank
    {
        return new NotBlank([
            'groups' => $options[static::OPTION_VALIDATION_GROUP],
            'message' => static::VALIDATION_NOT_BLANK_MESSAGE,
        ]);
    }

    /**
     * @return \Closure
     */
    protected function getInvertedBooleanValueCallbackTransformer(): Closure
    {
        return function (?bool $value): bool {
            return !$value;
        };
    }
}
