<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class MultiShipmentForm extends AbstractType
{
    public const BLOCK_PREFIX = 'shipmentGroupForm';
    public const OPTION_SHIPMENT_METHODS = 'shipmentMethods';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
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
            ->addShipmentMethods($builder, $options)
            ->addRequestedDeliveryDate($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentMethods(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ShipmentTransfer::SHIPMENT_SELECTION, ChoiceType::class, [
            'choices' => $options[static::OPTION_SHIPMENT_METHODS],
            'expanded' => true,
            'multiple' => false,
            'required' => true,
            'placeholder' => false,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addRequestedDeliveryDate(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ShipmentTransfer::REQUESTED_DELIVERY_DATE, DateType::class, [
            'label' => 'page.checkout.shipment.requested_delivery_date.label',
            'widget' => 'single_text',
//            'property_path' => ShipmentTransfer::REQUESTED_DELIVERY_DATE,
            'placeholder' => 'checkout.shipment.requested_delivery_date.placeholder',
            'required' => false,
            'input' => 'string',
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

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'data_class' => ShipmentTransfer::class,
            ])
            ->setRequired(static::OPTION_SHIPMENT_METHODS);
    }
}
