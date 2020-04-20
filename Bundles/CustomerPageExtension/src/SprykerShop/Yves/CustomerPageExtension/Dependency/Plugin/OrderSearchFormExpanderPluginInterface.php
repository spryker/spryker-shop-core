<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Provides form expansion capabilities.
 *
 * Use this plugin for expanding OrderSearchForm with new form fields or subforms.
 */
interface OrderSearchFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands OrderSearchForm with new form fields or subforms.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface;
}
