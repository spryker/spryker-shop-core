<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemEmbeddedForm extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchField', ChoiceType::class, [
                'choices' => $this->getSearchFieldChoices(),
            ])
            ->add('searchQuery', TextType::class, [
                'required' => false,
            ])
            ->add('sku', HiddenType::class, [
                'required' => false,
            ])
            ->add('qty', IntegerType::class, [
                'required' => false,
            ])
            //->add('unit', ChoiceType::class)
            ->add('price', HiddenType::class, [
                'required' => false,
            ])
            ->add('pricePanel', TextType::class, [
                'disabled' => true,
                'required' => false,
            ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderItemTransfer::class,
        ]);
    }

    protected function getSearchFieldChoices()
    {
        return [
            'SKU / Name' => 'name',
        ];
    }
}
