<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSearchWidget\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\MerchantSearchWidget\MerchantSearchWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSearchWidget\MerchantSearchWidgetConfig getConfig()
 */
class MerchantsSearchForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMerchantReferenceField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_MERCHANT_REFERENCE,
            HiddenType::class,
            [
                'required' => false,
                'label' => false,
            ],
        );

        return $this;
    }
}
