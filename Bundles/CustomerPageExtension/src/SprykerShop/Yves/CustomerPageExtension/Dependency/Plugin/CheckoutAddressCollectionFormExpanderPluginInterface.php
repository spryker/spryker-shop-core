<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Provides form expansion capabilities.
 *
 * Use this plugin interface for expanding `CheckoutAddressCollectionForm` with new form fields and options.
 */
interface CheckoutAddressCollectionFormExpanderPluginInterface
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
     * - Expands form options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function expandOptions(QuoteTransfer $quoteTransfer, array $options): array;

    /**
     * Specification:
     * - Expands form with new form fields or subforms.
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
