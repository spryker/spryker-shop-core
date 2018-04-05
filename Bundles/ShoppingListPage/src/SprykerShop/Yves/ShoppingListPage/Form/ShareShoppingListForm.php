<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShareShoppingListForm extends AbstractType
{
    public const OPTION_BUSINESS_UNITS = 'businessUnits';
    public const OPTION_COMPANY_USERS = 'companyUsers';

    public const FIELD_ID_BUSINESS_UNIT = 'idCompanyBusinessUnit';
    public const FIELD_ID_COMPANY_USER = 'idCompanyUser';
    public const FIELD_ID_SHOPPING_LIST_PERMISSION_GROUP = 'idShoppingListPermissionGroup';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::OPTION_BUSINESS_UNITS);
        $resolver->setDefined(static::OPTION_COMPANY_USERS);
        $resolver->setDefaults([
            'data_class' => ShoppingListShareRequestTransfer::class,
            'constraints' => [
                $this->getFactory()->createShareShoppingListRequiredIdConstraint(),
            ],
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
        $this->addIdCompanyUserField($builder, $options);
        $this->addIdBusinessUnitField($builder, $options);
        $this->addIdShoppingListPermissionGroupField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdCompanyUserField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_ID_COMPANY_USER, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_COMPANY_USERS]),
            'choices_as_values' => true,
            'expanded' => false,
            'placeholder' => 'customer.account.shopping_list.share.select_company_user',
            'label' => 'customer.account.shopping_list.share.select_company_user',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdBusinessUnitField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_ID_BUSINESS_UNIT, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_BUSINESS_UNITS]),
            'choices_as_values' => true,
            'expanded' => false,
            'placeholder' => 'customer.account.shopping_list.share.select_company_business_unit',
            'label' => 'customer.account.shopping_list.share.select_company_business_unit',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdShoppingListPermissionGroupField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_SHOPPING_LIST_PERMISSION_GROUP, HiddenType::class);

        return $this;
    }
}
