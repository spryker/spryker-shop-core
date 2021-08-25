<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Provides capabilities to expand `ProductConfiguratorRequestDataForm.`
 *
 * Use this plugin in case you need to extend a form with a newly introduced source type.
 */
interface ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable to execute.
     *
     * @api
     *
     * @param array $options
     *
     * @return bool
     */
    public function isApplicable(array $options): bool;

    /**
     * Specification:
     * - Expands `ProductConfiguratorRequestDataForm` with additional form fields or sub-forms.
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
