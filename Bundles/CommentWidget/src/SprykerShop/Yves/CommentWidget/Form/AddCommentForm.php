<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Form;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommentForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @retun void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentTransfer::class,
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
        $this->addMessageField($builder)
            ->addOwnerIdField($builder)
            ->addOwnerTypeField($builder)
            ->addReturnUrlField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMessageField(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::MESSAGE, TextareaType::class, [
            'label' => false,
            'required' => true,
            'attr' => [
                'placeholder' => 'comment_widget.form.placeholder.add_comment',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOwnerIdField(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::OWNER_ID, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOwnerTypeField(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::OWNER_TYPE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addReturnUrlField(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::RETURN_URL, HiddenType::class);

        return $this;
    }
}
