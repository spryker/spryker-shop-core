<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CartNoteWidget\Plugin\Provider\CartNoteWidgetControllerProvider;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class QuoteItemCartNoteForm extends AbstractType
{
    const FORM_NAME = 'quoteItemCartNote';
    const FIELD_CART_NOTE = 'cartNote';
    const FIELD_SKU = 'sku';
    const FIELD_GROUP_KEY = 'groupKey';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCartNoteField($builder);
        $this->addItemSkuField($builder);
        $this->addGroupKeyField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCartNoteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CART_NOTE, TextareaType::class, [
            'label' => false,
            'empty_data' => 'cart_note.item_form.placeholder',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGroupKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GROUP_KEY, HiddenType::class);

        return $this;
    }
}
