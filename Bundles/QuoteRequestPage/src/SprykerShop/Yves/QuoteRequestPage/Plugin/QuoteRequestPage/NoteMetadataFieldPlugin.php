<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\QuoteRequestPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class NoteMetadataFieldPlugin extends AbstractPlugin implements QuoteRequestFormMetadataFieldPluginInterface
{
    protected const FIELD_METADATA_NOTE = 'note';
    protected const LABEL_METADATA_NOTE = 'quote_request_page.quote_request.metadata.label.note';
    protected const MAX_LENGTH_NOTE = 1024;

    /**
     * {@inheritDoc}
     * - Adds note to metadata for QuoteRequest form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(static::FIELD_METADATA_NOTE, TextareaType::class, [
            'label' => static::LABEL_METADATA_NOTE,
            'required' => false,
            'constraints' => [
                new Length([
                    'max' => static::MAX_LENGTH_NOTE,
                ]),
            ],
        ]);

        return $builder;
    }
}
