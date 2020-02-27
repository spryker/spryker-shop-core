<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form;

use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageConfig getConfig()
 */
class QuoteRequestAgentVersionSubForm extends AbstractType
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
        $resolver->setRequired([
            QuoteRequestAgentForm::OPTION_PRICE_MODE,
            QuoteRequestAgentForm::OPTION_IS_QUOTE_VALID,
            QuoteRequestAgentForm::OPTION_SHIPMENT_GROUPS,
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
        $this->addMetadataForm($builder)
            ->addQuoteForm($builder, $options)
            ->addShipmentGroupsForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetadataForm(FormBuilderInterface $builder)
    {
        $builder->add(QuoteRequestVersionTransfer::METADATA, QuoteRequestAgentMetadataSubForm::class);

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
            QuoteRequestAgentVersionQuoteSubForm::class,
            [
                QuoteRequestAgentForm::OPTION_PRICE_MODE => $options[QuoteRequestAgentForm::OPTION_PRICE_MODE],
                QuoteRequestAgentForm::OPTION_IS_QUOTE_VALID => $options[QuoteRequestAgentForm::OPTION_IS_QUOTE_VALID],
                QuoteRequestAgentForm::OPTION_SHIPMENT_GROUPS => $options[QuoteRequestAgentForm::OPTION_SHIPMENT_GROUPS],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentGroupsForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(QuoteRequestAgentForm::FIELD_SHIPMENT_GROUPS, CollectionType::class, [
            'required' => false,
            'label' => false,
            'entry_type' => QuoteRequestAgentVersionShipmentGroupsSubForm::class,
            'disabled' => !$options[QuoteRequestAgentForm::OPTION_IS_QUOTE_VALID],
            'mapped' => false,
            'data' => $options[QuoteRequestAgentForm::OPTION_SHIPMENT_GROUPS],
            'entry_options' => [
                QuoteRequestAgentForm::OPTION_PRICE_MODE => $options[QuoteRequestAgentForm::OPTION_PRICE_MODE],
            ],
        ]);

        return $this;
    }
}
