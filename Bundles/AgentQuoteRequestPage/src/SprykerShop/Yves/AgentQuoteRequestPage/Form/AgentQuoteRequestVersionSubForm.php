<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form;

use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig getConfig()
 */
class AgentQuoteRequestVersionSubForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteRequestVersionTransfer::class,
            'label' => false,
        ]);
        $resolver->setRequired([AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMetadataForm($builder)
            ->addQuoteForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetadataForm(FormBuilderInterface $builder)
    {
        $builder->add(QuoteRequestVersionTransfer::METADATA, AgentQuoteRequestMetadataSubForm::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuoteForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            QuoteRequestVersionTransfer::QUOTE,
            AgentQuoteRequestVersionQuoteSubForm::class,
            [
                AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS => $options[AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS],
            ]
        );

        return $this;
    }
}
