<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 */
class OwnerCompanyBusinessUnitSubForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CompanyBusinessUnitTransfer::class,
            'label' => false,
        ]);
        $resolver->setRequired([
            MerchantRelationRequestForm::OPTION_BUSINESS_UNIT_CHOICES,
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
        $this->addIdCompanyBusinessUnitField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIdCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'label' => 'merchant_relation_request_page.merchant_relation_request.business_unit_owner',
            'placeholder' => 'merchant_relation_request_page.merchant_relation_request.business_unit_owner.placeholder',
            'required' => true,
            'choices' => array_flip($options[MerchantRelationRequestForm::OPTION_BUSINESS_UNIT_CHOICES]),
            'constraints' => [
                new NotBlank(['message' => 'validation.not_blank']),
            ],
        ]);

        return $this;
    }
}
