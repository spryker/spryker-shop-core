<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ReturnCreateFormExpanderPluginInterface
{
    /**
     * Specification:
     *  - Extends return create form metadata field with additional input fields.
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
     * - Expands Return create form data.
     *
     * @api
     *
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array;
}
