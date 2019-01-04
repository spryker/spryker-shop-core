<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteApproveRequestForm extends AbstractType
{
    protected const FILED_APPROVER = 'idApprover';

    public const OPTION_APPROVERS_LIST = 'OPTION_APPROVERS_LIST';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addApproverField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_APPROVERS_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addApproverField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FILED_APPROVER, ChoiceType::class, [
            'choices' => $options[static::OPTION_APPROVERS_LIST],
            'choices_as_values' => true,
            'expanded' => false,
            'required' => true,
            'label' => false,
        ]);
    }
}
