<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Plugin\AgentQuoteRequestPage;

use DateTime;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentQuoteRequestPageExtension\Dependency\Plugin\AgentQuoteRequestFormMetadataFieldPluginInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class DeliveryDateMetadataFieldPlugin extends AbstractPlugin implements AgentQuoteRequestFormMetadataFieldPluginInterface
{
    protected const FIELD_METADATA_DELIVERY_DATE = 'delivery_date';
    protected const LABEL_METADATA_DELIVERY_DATE = 'quote_request_page.quote_request.metadata.label.delivery_date';

    /**
     * {@inheritdoc}
     * - Adds delivery date to metadata for AgentQuoteRequest form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(static::FIELD_METADATA_DELIVERY_DATE, DateType::class, [
            'label' => static::LABEL_METADATA_DELIVERY_DATE,
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
        ]);

        $builder->get(static::FIELD_METADATA_DELIVERY_DATE)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $builder;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    $value = new DateTime($value);
                }
                return $value;
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d');
                }
                return $value;
            }
        );
    }
}
