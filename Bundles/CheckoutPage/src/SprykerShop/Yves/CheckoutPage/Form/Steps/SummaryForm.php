<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class SummaryForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL = 'OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL';

    protected const FIELD_ACCEPT_TERMS_AND_CONDITIONS = QuoteTransfer::ACCEPT_TERMS_AND_CONDITIONS;

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addAcceptTermsAndConditionsField($builder, $options);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'summaryForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAcceptTermsAndConditionsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ACCEPT_TERMS_AND_CONDITIONS, CheckboxType::class, [
            'label' => $options[static::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL],
            'required' => true,
        ]);

        return $this;
    }
}
