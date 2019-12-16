<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\QuoteRequestPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class PurchaseOrderNumberMetadataFieldPlugin extends AbstractPlugin implements QuoteRequestFormMetadataFieldPluginInterface
{
    protected const FIELD_METADATA_PURCHASE_ORDER_NUMBER = 'purchase_order_number';
    protected const LABEL_METADATA_PURCHASE_ORDER_NUMBER = 'quote_request_page.quote_request.metadata.label.purchase_order_number';
    protected const MAX_LENGTH_NUMBER = 128;

    /**
     * {@inheritDoc}
     * - Adds purchase order number to metadata for QuoteRequest form.
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
            'constraints' => [
                new Length([
                    'max' => static::MAX_LENGTH_NUMBER,
                ]),
            ],
        ]);

        return $builder;
    }
}
