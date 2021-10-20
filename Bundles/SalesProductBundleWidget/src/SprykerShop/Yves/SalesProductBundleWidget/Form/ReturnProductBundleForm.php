<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductBundleWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\SalesProductBundleWidget\SalesProductBundleWidgetConfig getConfig()
 */
class ReturnProductBundleForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_PRODUCT_BUNDLES = 'productBundles';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addReturnBundleItemsCollection($builder, $options);

        return $builder;
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
            ],
        );
    }
}
