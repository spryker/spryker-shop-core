<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageFactory getFactory()
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig getConfig()
 */
class MerchantRelationshipSearchForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_BUSINESS_UNIT_CHOICES = 'option_business_unit_choices';

    /**
     * @var string
     */
    public const OPTION_MERCHANT_CHOICES = 'option_merchant_choices';

    /**
     * @var string
     */
    public const FIELD_RESET = 'reset';

    /**
     * @var string
     */
    public const FIELD_FILTERS = 'filters';

    /**
     * @var string
     */
    public const FORM_NAME = 'merchantRelationshipSearchForm';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_MERCHANT_CHOICES,
            static::OPTION_BUSINESS_UNIT_CHOICES,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET);

        $this->addResetField($builder)
            ->addFiltersField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addResetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_RESET, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFiltersField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_FILTERS,
            MerchantRelationshipSearchFiltersSubForm::class,
            [
                static::OPTION_MERCHANT_CHOICES => $options[static::OPTION_MERCHANT_CHOICES],
                static::OPTION_BUSINESS_UNIT_CHOICES => $options[static::OPTION_BUSINESS_UNIT_CHOICES],
            ],
        );

        return $this;
    }
}
