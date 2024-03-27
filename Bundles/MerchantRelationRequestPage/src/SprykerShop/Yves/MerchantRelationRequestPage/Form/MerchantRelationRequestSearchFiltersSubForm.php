<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 */
class MerchantRelationRequestSearchFiltersSubForm extends AbstractType
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
     * @var string
     */
    public const FIELD_STATUS = 'status';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            MerchantRelationRequestSearchForm::OPTION_MERCHANT_CHOICES,
            MerchantRelationRequestSearchForm::OPTION_BUSINESS_UNIT_CHOICES,
            MerchantRelationRequestSearchForm::OPTION_STATUS_CHOICES,
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
            ->addOwnerCompanyBusinessUnitField($builder, $options)
            ->addStatusField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addMerchantFiled(FormBuilderInterface $builder, array $options)
    {
        if (!$this->getConfig()->isFilterByMerchantEnabledForMerchantRelationRequestTable()) {
            return $this;
        }

        $builder->add(
            static::FIELD_MERCHANT,
            ChoiceType::class,
            [
                'choices' => array_flip($options[MerchantRelationRequestSearchForm::OPTION_MERCHANT_CHOICES]),
                'required' => false,
                'placeholder' => 'merchant_relation_request_page.merchant_relation_request.merchant.placeholder',
                'label' => 'merchant_relation_request_page.merchant_relation_request.merchant',
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
                'choices' => array_flip($options[MerchantRelationRequestSearchForm::OPTION_BUSINESS_UNIT_CHOICES]),
                'required' => false,
                'placeholder' => 'merchant_relation_request_page.merchant_relation_request.business_unit_owner.placeholder',
                'label' => 'merchant_relation_request_page.merchant_relation_request.business_unit_owner',
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
    protected function addStatusField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STATUS,
            ChoiceType::class,
            [
                'choices' => array_flip($options[MerchantRelationRequestSearchForm::OPTION_STATUS_CHOICES]),
                'required' => false,
                'placeholder' => 'merchant_relation_request_page.merchant_relation_request.status.placeholder',
                'label' => 'merchant_relation_request_page.merchant_relation_request.status',
            ],
        );

        return $this;
    }
}
