<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Form;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetConfig getConfig()
 */
class CommentForm extends AbstractType
{
    public const COMMENT_FORM = 'commentForm';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentTransfer::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return self::COMMENT_FORM;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addUuidFiled($builder);
        $this->addMessageFiled($builder);
        $this->addCommentTags($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUuidFiled(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::UUID, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMessageFiled(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::MESSAGE, TextareaType::class, [
            'label' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'max' => 5000,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCommentTags(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::TAGS, CollectionType::class, [
            'required' => false,
            'label' => false,
            'entry_type' => CommentTagSubForm::class,
        ]);

        return $this;
    }
}
