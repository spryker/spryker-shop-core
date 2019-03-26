<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig getConfig()
 */
class AgentQuoteRequestVersionQuoteSubForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteTransfer::class,
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
        $this->addItemsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addItemsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(QuoteTransfer::ITEMS, CollectionType::class, [
            'required' => false,
            'label' => false,
            'entry_type' => AgentQuoteRequestVersionItemsSubForm::class,
            'entry_options' => [
                AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS => $options[AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS],
            ],
        ]);

        $builder->get(QuoteTransfer::ITEMS)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createArrayObjectModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                return (array)$value;
            },
            function ($value) {
                return new ArrayObject($value);
            }
        );
    }
}
