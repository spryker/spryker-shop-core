<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Form;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MerchantProductOffersSelectForm extends AbstractType
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_NAME = 'merchant_product_offer_widget.merchant_name';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_CHOICES = 'product_offer_reference_choices';

    /**
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::PRODUCT_OFFER_REFERENCE_CHOICES);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductOfferReferenceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductOfferReferenceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRODUCT_OFFER_REFERENCE, ChoiceType::class, [
            'label' => static::GLOSSARY_KEY_MERCHANT_NAME,
            'choices' => $options[static::PRODUCT_OFFER_REFERENCE_CHOICES],
            'choice_label' => ProductOfferTransfer::MERCHANT_NAME,
            'choice_value' => ProductOfferTransfer::PRODUCT_OFFER_REFERENCE,
            'placeholder' => false,
            'required' => false,
        ]);

        return $this;
    }
}
