<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestForm extends AbstractType
{
    public const FIELD_METADATA = 'metadata';
    public const FIELD_QUOTE_REQUEST_VERSION_REFERENCE = 'quote-request-version-reference';

    public const OPTION_VERSION_REFERENCE_CHOICES = 'version_reference_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteRequestTransfer::class,
        ]);

        $resolver->setRequired([static::OPTION_VERSION_REFERENCE_CHOICES]);
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
            ->addVersionsForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetadataForm(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_METADATA, QuoteRequestMetadataSubForm::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addVersionsForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            QuoteRequestTransfer::LATEST_VERSION,
            QuoteRequestVersionsSubForm::class,
            [
                'label' => false,
                'data_class' => QuoteRequestVersionTransfer::class,
                static::OPTION_VERSION_REFERENCE_CHOICES => $options[static::OPTION_VERSION_REFERENCE_CHOICES],
            ]
        );

        return $this;
    }
}
