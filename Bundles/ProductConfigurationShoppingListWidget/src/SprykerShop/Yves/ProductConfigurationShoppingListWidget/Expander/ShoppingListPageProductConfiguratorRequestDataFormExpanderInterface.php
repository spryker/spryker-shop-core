<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface ShoppingListPageProductConfiguratorRequestDataFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandProductConfiguratorRequestDataForm(FormBuilderInterface $builder, array $options): FormBuilderInterface;
}
