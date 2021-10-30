<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductBundleWidget\Plugin\SalesReturnPage;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\SalesProductBundleWidget\SalesProductBundleWidgetFactory getFactory()
 */
class ProductBundleReturnCreateFormHandlerPlugin extends AbstractPlugin implements ReturnCreateFormHandlerPluginInterface
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
        return $this->getFactory()
            ->createReturnProductBundleForm()
            ->buildForm($builder, $options);
    }

    /**
     * {@inheritDoc}
     * - Expands ReturnCreateForm data with product bundles.
     *
     * @api
     *
     * @param array<string, mixed> $formData
     *
     * @return array<string, mixed>
     */
    public function expandFormData(array $formData): array
    {
        return $this->getFactory()
            ->createReturnCreateFormExpander()
            ->expandFormData($formData);
    }

    /**
     * {@inheritDoc}
     * - Adds submitted product bundle items to ReturnCreateRequestTransfer.
     *
     * @api
     *
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        return $this->getFactory()
            ->createReturnCreateFormHandler()
            ->handleFormData($returnItemsList, $returnCreateRequestTransfer);
    }
}
