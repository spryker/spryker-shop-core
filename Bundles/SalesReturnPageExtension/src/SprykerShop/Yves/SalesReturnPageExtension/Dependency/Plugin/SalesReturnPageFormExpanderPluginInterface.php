<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Symfony\Component\Form\FormBuilderInterface;

interface SalesReturnPageFormExpanderPluginInterface
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
     * - Expands Return create form data with product bundles.
     *
     * @api
     *
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array;

    /**
     * Specification:
     * - Handles Return create form.
     * - Adds submitted product bundle items to ReturnCreateRequestTransfer.
     *
     * @api
     *
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer;
}
