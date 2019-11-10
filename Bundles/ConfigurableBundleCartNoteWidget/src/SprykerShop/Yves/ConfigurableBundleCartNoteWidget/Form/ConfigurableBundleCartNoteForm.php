<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class ConfigurableBundleCartNoteForm extends AbstractType
{
    public const FORM_NAME = 'configurableBundleCartNote';
    public const FIELD_CONFIGURABLE_BUNDLE_CART_NOTE = 'cartNote';
    public const FIELD_CONFIGURABLE_BUNDLE_GROUP_KEY = 'configurableBundleGroupKey';
    public const FIELD_CONFIGURABLE_BUNDLE_TEMPLATE_NAME = 'templateName';

    protected const FIELD_CONFIGURABLE_BUNDLE_CART_NOTE_MAX_LENGTH = 255;

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCartNoteField($builder)
            ->addGroupKeyField($builder)
            ->addTemplateNameField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCartNoteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONFIGURABLE_BUNDLE_CART_NOTE, TextareaType::class, [
            'label' => 'configurable_bundle_cart_note.enter_note',
            'required' => false,
            'constraints' => [
                new Length(['max' => static::FIELD_CONFIGURABLE_BUNDLE_CART_NOTE_MAX_LENGTH]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGroupKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONFIGURABLE_BUNDLE_GROUP_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTemplateNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONFIGURABLE_BUNDLE_TEMPLATE_NAME, HiddenType::class);

        return $this;
    }
}
