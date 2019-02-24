<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form;

use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestVersionsSubForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([QuoteRequestForm::OPTION_VERSION_REFERENCE_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addQuoteRequestVersionReferenceChoice($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuoteRequestVersionReferenceChoice(FormBuilderInterface $builder, array $options)
    {
        $builder->add(QuoteRequestVersionTransfer::VERSION_REFERENCE, ChoiceType::class, [
            'label' => false,
            'choices' => $options[QuoteRequestForm::OPTION_VERSION_REFERENCE_CHOICES],
        ]);

        return $this;
    }
}
