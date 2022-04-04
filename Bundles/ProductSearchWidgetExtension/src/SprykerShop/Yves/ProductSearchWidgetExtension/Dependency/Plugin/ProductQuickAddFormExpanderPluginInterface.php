<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidgetExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Provides capabilities to expand ProductQuickAddForm.
 */
interface ProductQuickAddFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands ProductQuickAddForm with new form fields or sub-forms.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface;
}
