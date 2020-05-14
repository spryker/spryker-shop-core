<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\SalesReturnPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductBundleWidget\Form\ProductBundleItemsForm;
use SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\ReturnCreateFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleReturnCreateFormExpanderPlugin extends AbstractPlugin implements ReturnCreateFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ReturnCreateForm with product bundles field.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(
            ProductBundleItemsForm::FIELD_PRODUCT_BUNDLES,
            CollectionType::class,
            [
                'entry_type' => ProductBundleItemsForm::class,
                'entry_options' => [
                    ProductBundleItemsForm::OPTION_RETURN_REASONS => $options[ProductBundleItemsForm::OPTION_RETURN_REASONS],
                ],
                'label' => false,
            ]
        );

        return $builder;
    }

    /**
     * {@inheritDoc}
     * - Expands ReturnCreateForm data with product bundles.
     *
     * @api
     *
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array
    {
        return $this->getFactory()
            ->createReturnCreateFormExpander()
            ->expandFormData($formData);
    }
}
