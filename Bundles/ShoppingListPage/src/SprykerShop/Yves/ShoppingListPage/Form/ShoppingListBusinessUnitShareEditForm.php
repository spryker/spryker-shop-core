<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListBusinessUnitShareEditForm extends AbstractType
{
    protected const FIELD_ID_SHOPPING_LIST_COMPANY_BUSINESS_UNIT = 'idShoppingListCompanyBusinessUnit';
    protected const FIELD_ID_COMPANY_BUSINESS_UNIT = 'idCompanyBusinessUnit';
    protected const FIELD_ID_SHOPPING_LIST = 'idShoppingList';
    protected const FIELD_ID_SHOPPING_LIST_PERMISSION_GROUP = 'idShoppingListPermissionGroup';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(ShareShoppingListForm::OPTION_PERMISSION_GROUPS);
        $resolver->setDefaults([
            'data_class' => ShoppingListCompanyBusinessUnitTransfer::class,
            'label' => false,
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
        $this
            ->addIdShoppingListCompanyBusinessUnitField($builder)
            ->addIdCompanyBusinessUnitField($builder)
            ->addIdShoppingListField($builder)
            ->addIdShoppingListPermissionGroupField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdShoppingListCompanyBusinessUnitField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_SHOPPING_LIST_COMPANY_BUSINESS_UNIT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyBusinessUnitField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_BUSINESS_UNIT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdShoppingListField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_SHOPPING_LIST, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdShoppingListPermissionGroupField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ID_SHOPPING_LIST_PERMISSION_GROUP, ChoiceType::class, [
            'choices' => $options[ShareShoppingListForm::OPTION_PERMISSION_GROUPS],
            'label' => false,
            'placeholder' => false,
            'required' => false,
        ]);

        return $this;
    }
}
