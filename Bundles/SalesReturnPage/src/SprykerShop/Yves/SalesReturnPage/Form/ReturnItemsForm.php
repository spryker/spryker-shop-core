<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageConfig getConfig()
 */
class ReturnItemsForm extends AbstractType
{
    public const FIELD_UUID = 'uuid';
    public const FIELD_REASON = 'reason';
    public const FIELD_CUSTOM_REASON = 'customReason';
    public const FIELD_ORDER_ITEM = 'orderItem';

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
            ->addReason($builder, $options)
            ->addCustomReason($builder);
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
                    'label' => false,
                    'required' => false,
                ]
            );

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            return $this->getFactory()
                ->createReturnItemsFormEventsListener()
                ->mappReturnItemTransfersUuid($event);
        });

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
            'placeholder' => 'return.return_reasons.select_reason.placeholder',
            'choices' => $options[static::OPTION_REUTN_REASONS],
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomReason(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CUSTOM_REASON, TextareaType::class, [
            'label' => 'Custom Reason',
            'required' => false,
        ]);

        return $this;
    }
}
