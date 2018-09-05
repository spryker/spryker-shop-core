<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin;

 use Symfony\Component\Form\FormBuilderInterface;

interface ShoppingListItemFormExpanderPluginInterface
{
    /**
     * Specification:
     *  - Extends shopping list item form with additional input fields.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void;
}
