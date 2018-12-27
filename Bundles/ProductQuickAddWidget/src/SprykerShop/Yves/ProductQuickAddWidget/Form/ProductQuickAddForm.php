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
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ProductQuickAddWidget\ProductQuickAddWidgetConfig getConfig()
 */
class ProductQuickAddForm extends AbstractType
{
    private const FORM_NAME = 'productQuickAddForm';
    public const FIELD_SKU = 'sku';
    public const FIELD_QUANTITY = 'quantity';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
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
            ->addSku($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerShop\Yves\ProductQuickAddWidget\Form\ProductQuickAddForm
     */
    protected function addSku(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_SKU,
            HiddenType::class,
            [
                'required' => true,
                'label' => false,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerShop\Yves\ProductQuickAddWidget\Form\ProductQuickAddForm
     */
    protected function addQuantity(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_QUANTITY,
            IntegerType::class,
            [
                'required' => true,
                'label' => false,
                'attr' => ['min' => 1],
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMinLengthConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createMinLengthConstraint(): Length
    {
        return new Length(
            [
                'min' => 1,
            ]
        );
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank();
    }
}
