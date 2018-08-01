<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerAutocompleteForm extends AbstractType
{
    public const FIELD_LIMIT = 'limit';
    public const FIELD_QUERY = 'query';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_protection', false); // because api
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addLimitField($builder)
            ->addQueryField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLimitField(FormBuilderInterface $builder): self
    {
        $builder->add(self::FIELD_LIMIT, ChoiceType::class, [
            'required' => false,
            'data' => 5,
            'choices' => [5, 10, 15],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQueryField(FormBuilderInterface $builder): self
    {
        $builder->add(self::FIELD_QUERY, TextType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 3]),
            ],
        ]);

        return $this;
    }
}
