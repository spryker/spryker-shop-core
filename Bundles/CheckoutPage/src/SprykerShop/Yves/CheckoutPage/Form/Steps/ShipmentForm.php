<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShipmentForm extends AbstractType
{
    const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';
    const OPTION_SHIPMENT_METHODS = 'shipmentMethods';

    const SHIPMENT_PROPERTY_PATH = 'shipment';
    const SHIPMENT_SELECTION = 'shipmentSelection';
    const SHIPMENT_SELECTION_PROPERTY_PATH = self::SHIPMENT_PROPERTY_PATH . '.' . self::SHIPMENT_SELECTION;

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'shipmentForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_SHIPMENT_METHODS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addShipmentMethods($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentMethods(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ID_SHIPMENT_METHOD, ChoiceType::class, [
            'choices' => $options[self::OPTION_SHIPMENT_METHODS],
            'expanded' => true,
            'multiple' => false,
            'required' => true,
            'property_path' => static::SHIPMENT_SELECTION_PROPERTY_PATH,
            'placeholder' => false,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }
}
