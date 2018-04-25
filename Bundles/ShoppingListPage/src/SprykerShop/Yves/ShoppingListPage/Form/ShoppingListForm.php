<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShoppingListForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_ID = 'idShoppingList';
    public const SHOW_NAME_LABEL = 'showNameLabel';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::SHOW_NAME_LABEL);
        $resolver->setDefaults([
            'data_class' => ShoppingListTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder, $options);
        $this->addIdField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addNameField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => $options[static::SHOW_NAME_LABEL]? 'customer.account.shopping_list.overview.name': false,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIdField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_ID, HiddenType::class);
    }
}