<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListClearForm extends AbstractType
{
    /**
     * This method is empty, because this form needs to implement csrf protection and all options and form content
     * will be defined in twig templates.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    }
}
