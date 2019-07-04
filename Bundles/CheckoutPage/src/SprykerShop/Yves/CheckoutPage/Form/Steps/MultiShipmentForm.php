<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CheckoutPage\Form\Validator\Constraints\GreaterThanOrEqualDate;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class MultiShipmentForm extends AbstractType
{
    public const BLOCK_PREFIX = 'shipmentGroupForm';
    public const OPTION_SHIPMENT_METHODS = 'shipmentMethods';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_INVALID_DATE_TIME_MESSAGE = 'validation.invalid_date';
    protected const VALIDATION_VALID_DATE_TIME_FORMAT = 'Y-m-d'; // Format accepted by date().
    protected const VALIDATION_DATE_TODAY = 'today';

    protected const FIELD_REQUESTED_DELIVERY_DATE_FORMAT = 'yyyy-MM-dd'; // Format accepted by IntlDateFormatter.

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
            ->addShipmentMethodsField($builder, $options)
            ->addRequestedDeliveryDateField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentMethodsField(FormBuilderInterface $builder, array $options)
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
     *
     * @return $this
     */
    protected function addRequestedDeliveryDateField(FormBuilderInterface $builder)
    {
        $builder->add(ShipmentTransfer::REQUESTED_DELIVERY_DATE, DateType::class, [
            'label' => 'page.checkout.shipment.requested_delivery_date.label',
            'widget' => 'single_text',
            'placeholder' => 'checkout.shipment.requested_delivery_date.placeholder',
            'required' => false,
            'input' => 'string',
            'format' => static::FIELD_REQUESTED_DELIVERY_DATE_FORMAT,
            'constraints' => [
                $this->createDateTimeConstraint(),
                $this->createDateTimeGreaterThanOrEqualConstraint(static::VALIDATION_DATE_TODAY),
            ],
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
     * @return \Symfony\Component\Validator\Constraints\DateTime
     */
    protected function createDateTimeConstraint(): ConstraintDateTime
    {
        return new ConstraintDateTime([
            'format' => static::VALIDATION_VALID_DATE_TIME_FORMAT,
            'message' => sprintf(static::VALIDATION_INVALID_DATE_TIME_MESSAGE, static::VALIDATION_VALID_DATE_TIME_FORMAT),
        ]);
    }

    /**
     * @param string $minDate
     *
     * @return \SprykerShop\Yves\CheckoutPage\Form\Validator\Constraints\GreaterThanOrEqualDate
     */
    protected function createDateTimeGreaterThanOrEqualConstraint(string $minDate): GreaterThanOrEqualDate
    {
        return new GreaterThanOrEqualDate($minDate);
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
