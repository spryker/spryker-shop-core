<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSearchWidget\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\MerchantSearchWidget\MerchantSearchWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSearchWidget\MerchantSearchWidgetConfig getConfig()
 */
class MerchantsChoiceForm extends AbstractType
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANTS = 'merchant_search_widget.merchants';

    /**
     * @var string
     */
    protected const FIELD_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var string
     */
    protected const OPTION_MERCHANTS = 'merchants';

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
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_MERCHANT_REFERENCE,
            ChoiceType::class,
            [
                'label' => static::GLOSSARY_KEY_MERCHANTS,
                'choices' => $options[static::OPTION_MERCHANTS],
                'placeholder' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_MERCHANTS);
    }
}
