<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuickAddWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ProductQuickAddWidget\ProductQuickAddWidgetConfig getConfig()
 */
class ProductQuickAddForm extends AbstractType
{
    protected const FIELD_SKU = 'sku';
    protected const FIELD_QUANTITY = 'quantity';
    public const FIELD_REDIRECT_ROUTE_NAME = 'redirect-route-name';
    public const FIELD_ADDITIONAL_REDIRECT_PARAMETERS = 'additional-redirect-parameters';

    protected const FORM_NAME = 'productQuickAddForm';
    protected const MAX_QUANTITY_VALUE = 2147483647; // 32 bit integer

    protected const ERROR_MESSAGE_QUANTITY_REQUIRED = 'product_quick_add_widget.form.error.quantity.required';
    protected const ERROR_MESSAGE_QUANTITY_MAX_VALUE_CONSTRAINT = 'product_quick_add_widget.form.error.quantity.max_value_constraint';
    protected const ERROR_MESSAGE_REDIRECT_ROUTE_EMPTY = 'product_quick_add_widget.form.error.redirect_route_empty';
    protected const ERROR_MESSAGE_REDIRECT_SKU_EMPTY = 'product_quick_add_widget.form.error.sku.empty';

    /**
     * @return string|null
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addQuantity($builder)
            ->addSku($builder)
            ->addRedirectRouteName($builder)
            ->addAdditionalRedirectParameters($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSku(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'required' => true,
            'label' => false,
            'constraints' => [
                $this->createNotBlankConstraint(static::ERROR_MESSAGE_REDIRECT_SKU_EMPTY),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRedirectRouteName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_REDIRECT_ROUTE_NAME, HiddenType::class, [
            'required' => true,
            'label' => false,
            'constraints' => [
                $this->createNotBlankConstraint(static::ERROR_MESSAGE_REDIRECT_ROUTE_EMPTY),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantity(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_QUANTITY, IntegerType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['min' => 1],
                'constraints' => [
                    $this->createNotBlankConstraint(static::ERROR_MESSAGE_QUANTITY_REQUIRED),
                    $this->createMinLengthConstraint(static::ERROR_MESSAGE_QUANTITY_REQUIRED),
                    $this->createLessThanOrEqualConstraint(
                        static::MAX_QUANTITY_VALUE,
                        static::ERROR_MESSAGE_QUANTITY_MAX_VALUE_CONSTRAINT
                    )
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAdditionalRedirectParameters(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDITIONAL_REDIRECT_PARAMETERS, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createMinLengthConstraint(string $message): Length
    {
        return new Length([
            'min' => 1,
            'minMessage' => $message
        ]);
    }

    /**
     * @param string $message
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(string $message): NotBlank
    {
        return new NotBlank([
            'message' => $message
        ]);
    }

    /**
     * @param int $maxValue
     * @param string $message
     *
     * @return \Symfony\Component\Validator\Constraints\LessThanOrEqual
     */
    protected function createLessThanOrEqualConstraint(int $maxValue, string $message): LessThanOrEqual
    {
        return new LessThanOrEqual([
            'value' => $maxValue,
            'message' => $message
        ]);
    }
}
