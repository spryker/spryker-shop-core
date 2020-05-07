<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\SalesReturnPage;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductBundleWidget\Form\ProductBundleItemsForm;
use SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\SalesReturnPageFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleSalesReturnPageFormExpanderPlugin extends AbstractPlugin implements SalesReturnPageFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds bundle products with items for ReturnCreateForm
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
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array
    {
        return $this->getFactory()
            ->getSalesReturnPageFormExpander()
            ->expandFormData($formData);
    }

    /**
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        return $this->getFactory()
            ->getSalesReturnPageFormHandler()
            ->handleFormData($returnItemsList, $returnCreateRequestTransfer);
    }
}
