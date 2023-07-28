<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Provides form expansion capabilities.
 *
 * Use this plugin interface for expanding `CheckoutMultiShippingAddressesForm` with new form fields.
 */
interface CheckoutMultiShippingAddressesFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Configures form options.
     *
     * @api
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * Specification:
     *  - Expands form with new form fields or subforms.
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
