<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig getConfig()
 */
class TextOrderForm extends AbstractType
{
    public const FIELD_TEXT_ORDER = 'textOrder';
    public const SUBMIT_BUTTON_TEXT_ORDER = 'textOrder';
    protected const FIELD_TEXT_ORDER_PLACEHOLDER = 'quick-order.paste-order.input-placeholder.copy-paste-order';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addTextOrderField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTextOrderField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_TEXT_ORDER,
            TextareaType::class,
            [
                'label' => false,
                'attr' => ['placeholder' => static::FIELD_TEXT_ORDER_PLACEHOLDER],
                'constraints' => [
                    $this->getFactory()->createTextOrderCorrectConstraint(),
                ],
            ]
        );

        return $this;
    }
}
