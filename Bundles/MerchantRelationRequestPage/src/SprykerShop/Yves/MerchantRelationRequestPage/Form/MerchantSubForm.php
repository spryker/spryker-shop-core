<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 */
class MerchantSubForm extends AbstractType
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
            'data_class' => MerchantTransfer::class,
            'label' => false,
        ]);
        $resolver->setRequired([
            MerchantRelationRequestForm::OPTION_SELECTED_MERCHANT_REFERENCE,
            MerchantRelationRequestForm::OPTION_MERCHANT_CHOICES,
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
        $this->addMerchantReferenceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantTransfer::MERCHANT_REFERENCE, ChoiceType::class, [
            'label' => 'merchant_relation_request_page.merchant_relation_request.merchant',
            'placeholder' => 'merchant_relation_request_page.merchant_relation_request.merchant.placeholder',
            'required' => true,
            'choices' => array_flip($options[MerchantRelationRequestForm::OPTION_MERCHANT_CHOICES]),
            'data' => $options[MerchantRelationRequestForm::OPTION_SELECTED_MERCHANT_REFERENCE],
            'constraints' => [
                new NotBlank(['message' => 'validation.not_blank']),
            ],
        ]);

        return $this;
    }
}
