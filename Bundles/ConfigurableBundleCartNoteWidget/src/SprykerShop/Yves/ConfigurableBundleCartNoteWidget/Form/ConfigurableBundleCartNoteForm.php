<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ConfigurableBundleCartNoteForm extends AbstractType
{
    protected const FIELD_CONFIGURABLE_BUNDLE_CART_NOTE_MAX_LENGTH = 255;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConfiguredBundleTransfer::class,
        ]);
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
            ->addGroupKeyField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCartNoteField(FormBuilderInterface $builder)
    {
        $builder->add(ConfiguredBundleTransfer::CART_NOTE, TextareaType::class, [
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
        $builder->add(ConfiguredBundleTransfer::GROUP_KEY, HiddenType::class);

        return $this;
    }
}
