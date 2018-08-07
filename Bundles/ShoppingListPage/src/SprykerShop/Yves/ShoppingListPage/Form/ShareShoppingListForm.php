<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShareShoppingListForm extends AbstractType
{
    public const OPTION_PERMISSION_GROUPS = 'permissionGroups';

    public const FIELD_COMPANY_BUSINESS_UNITS = 'companyBusinessUnits';
    public const FIELD_COMPANY_USERS = 'companyUsers';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::OPTION_PERMISSION_GROUPS);
        $resolver->setDefaults([
            'data_class' => ShoppingListTransfer::class,
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
        $this->addCompanyBusinessUnits($builder, $options);
        $this->addCompanyUsers($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnits(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_COMPANY_BUSINESS_UNITS, CollectionType::class, [
            'entry_type' => ShoppingListBusinessUnitShareEditForm::class,
            'entry_options' => [
                static::OPTION_PERMISSION_GROUPS => $options[static::OPTION_PERMISSION_GROUPS],
            ],
            'label' => 'customer.account.shopping_list.share.select_company_business_unit',
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
    protected function addCompanyUsers(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_COMPANY_USERS, CollectionType::class, [
            'entry_type' => ShoppingListCompanyUserShareEditForm::class,
            'entry_options' => [
                static::OPTION_PERMISSION_GROUPS => $options[static::OPTION_PERMISSION_GROUPS],
            ],
            'label' => 'customer.account.shopping_list.share.select_company_user',
            'required' => false,
        ]);

        return $this;
    }
}
