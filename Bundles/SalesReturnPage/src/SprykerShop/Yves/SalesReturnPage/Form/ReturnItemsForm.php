<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form;

use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageConfig getConfig()
 */
class ReturnItemsForm extends AbstractType
{
    public const FIELD_UUID = 'uuid';
    public const FIELD_REASON = 'reason';

    public const OPTION_REUTN_REASONS = 'return_reasons';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addUuid($builder)
            ->addReason($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_REUTN_REASONS,
        ]);

        $resolver->setDefaults([
            'data_class' => ReturnItemTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function add(FormBuilderInterface $builder)
    {
        $builder
            ->add(
                static::FIELD_UUID,
                CheckboxType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'label' => false,
                ]
            );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUuid(FormBuilderInterface $builder)
    {
        $builder
            ->add(
                static::FIELD_UUID,
                CheckboxType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'label' => false,
                ]
            );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addReason(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_REASON, ChoiceType::class, [
            'label' => 'Reason',
            'placeholder' => 'Select one',
            'choices' => $options[static::OPTION_REUTN_REASONS],
            'required' => false,
        ]);

        return $this;
    }
}
