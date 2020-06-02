<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Specification:
 * - Executed by {@link \SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider::getData()}.
 * - Executed by {@link \SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm::buildForm()}.
 * - Executed by {@link \SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandler::createReturn()}.
 * - Provides extension capabilities for {@link \SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm}.
 * - Implement this plugin interface to expand form data, to customize form building process and to customize form handling process.
 */
interface ReturnCreateFormHandlerPluginInterface
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

    /**
     * Specification:
     * - Handles ReturnCreateForm data.
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
