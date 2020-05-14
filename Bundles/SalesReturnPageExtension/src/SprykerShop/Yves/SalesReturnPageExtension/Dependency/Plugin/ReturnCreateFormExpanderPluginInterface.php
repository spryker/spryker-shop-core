<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Specification:
 * - Executed by {@link \SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider::getData()}.
 * - Executed by {@link \SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm::buildForm()}.
 * - Provides extension capabilities for {@link \SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm}.
 * - Implement this plugin interface to expand form data and to customize form building process.
 */
interface ReturnCreateFormExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands ReturnCreateForm metadata field with additional input fields.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface;

    /**
     * Specification:
     * - Expands ReturnCreateForm data.
     *
     * @api
     *
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array;
}
