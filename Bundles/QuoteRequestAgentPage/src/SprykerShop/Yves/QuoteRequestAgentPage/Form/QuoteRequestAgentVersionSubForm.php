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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
        $builder->add(QuoteRequestVersionTransfer::SHIPMENT_GROUPS, CollectionType::class, [
            'required' => false,
            'label' => false,
            'entry_type' => QuoteRequestAgentVersionShipmentGroupsSubForm::class,
            'disabled' => !$options[QuoteRequestAgentForm::OPTION_IS_QUOTE_VALID],
            'entry_options' => [
                QuoteRequestAgentForm::OPTION_PRICE_MODE => $options[QuoteRequestAgentForm::OPTION_PRICE_MODE],
            ],
        ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            return $this->getFactory()
                ->createQuoteRequestAgentFormEventsListener()
                ->copySubmittedItemShipmentMethodPricesToQuoteShipmentMethod($event);
        });

        return $this;
    }
}
