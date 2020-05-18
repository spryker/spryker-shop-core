<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ReturnProductBundleForm extends AbstractType
{
    public const FIELD_PRODUCT_BUNDLES = 'productBundles';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addReturnBundleItemsCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addReturnBundleItemsCollection(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            static::FIELD_PRODUCT_BUNDLES,
            CollectionType::class,
            [
                'entry_type' => ReturnProductBundleItemsForm::class,
                'entry_options' => [
                    ReturnProductBundleItemsForm::OPTION_RETURN_REASONS => $options[ReturnProductBundleItemsForm::OPTION_RETURN_REASONS],
                ],
                'label' => false,
            ]
        );
    }
}
