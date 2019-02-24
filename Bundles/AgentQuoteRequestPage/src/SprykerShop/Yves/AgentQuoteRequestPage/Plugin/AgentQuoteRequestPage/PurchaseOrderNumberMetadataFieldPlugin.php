<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Plugin\AgentQuoteRequestPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentQuoteRequestPageExtension\Dependency\Plugin\AgentQuoteRequestFormMetadataFieldPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class PurchaseOrderNumberMetadataFieldPlugin extends AbstractPlugin implements AgentQuoteRequestFormMetadataFieldPluginInterface
{
    protected const FIELD_METADATA_PURCHASE_ORDER_NUMBER = 'purchase_order_number';
    protected const LABEL_METADATA_PURCHASE_ORDER_NUMBER = 'quote_request_page.quote_request.metadata.label.purchase_order_number';

    /**
     * {@inheritdoc}
     * - Adds purchase order number to metadata for AgentQuoteRequest form.
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
        $builder->add(static::FIELD_METADATA_PURCHASE_ORDER_NUMBER, TextType::class, [
            'label' => static::LABEL_METADATA_PURCHASE_ORDER_NUMBER,
            'required' => false,
        ]);

        return $builder;
    }
}
