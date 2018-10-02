<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListNoteWidget\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ShoppingListItemNoteForm extends ShoppingListItemNoteFormType
{
    protected const FIELD_SHOPPING_LIST_ITEM_NOTE = 'note';
    protected const TEMPLATE_PATH = '@ShoppingListNoteWidget/views/shopping-list-item-note-update/shopping-list-item-note-update.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNoteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addNoteField(FormBuilderInterface $builder): void
    {
        $builder->add(
            static::FIELD_SHOPPING_LIST_ITEM_NOTE,
            TextareaType::class
        );
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }
}
