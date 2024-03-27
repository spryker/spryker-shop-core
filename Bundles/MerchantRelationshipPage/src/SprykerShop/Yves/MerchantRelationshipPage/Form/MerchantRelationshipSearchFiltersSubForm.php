<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig getConfig()
 */
class MerchantRelationshipSearchFiltersSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_MERCHANT = 'merchant';

    /**
     * @var string
     */
    public const FIELD_OWNER_BUSINESS_UNIT = 'owner_business_unit';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            MerchantRelationshipSearchForm::OPTION_MERCHANT_CHOICES,
            MerchantRelationshipSearchForm::OPTION_BUSINESS_UNIT_CHOICES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addMerchantFiled($builder, $options)
            ->addOwnerCompanyBusinessUnitField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addMerchantFiled(FormBuilderInterface $builder, array $options)
    {
        if (!$this->getConfig()->isFilterByMerchantEnabledForMerchantRelationshipTable()) {
            return $this;
        }

        $builder->add(
            static::FIELD_MERCHANT,
            ChoiceType::class,
            [
                'choices' => array_flip($options[MerchantRelationshipSearchForm::OPTION_MERCHANT_CHOICES]),
                'required' => false,
                'placeholder' => 'merchant_relationship_page.merchant_relationship.merchant.placeholder',
                'label' => 'merchant_relationship_page.merchant_relationship.merchant',
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addOwnerCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_OWNER_BUSINESS_UNIT,
            ChoiceType::class,
            [
                'choices' => array_flip($options[MerchantRelationshipSearchForm::OPTION_BUSINESS_UNIT_CHOICES]),
                'required' => false,
                'placeholder' => 'merchant_relationship_page.merchant_relationship.business_unit_owner.placeholder',
                'label' => 'merchant_relationship_page.merchant_relationship.business_unit_owner',
            ],
        );

        return $this;
    }
}
