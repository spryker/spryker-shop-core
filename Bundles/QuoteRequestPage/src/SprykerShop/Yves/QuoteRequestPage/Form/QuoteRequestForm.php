<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestForm extends AbstractType
{
    public const SUBMIT_BUTTON_SAVE = 'save';
    public const SUBMIT_BUTTON_SEND_TO_USER = 'sendToUser';

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
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLatestVersionForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLatestVersionForm(FormBuilderInterface $builder)
    {
        $builder->add(QuoteRequestTransfer::LATEST_VERSION, QuoteRequestVersionSubForm::class);

        return $this;
    }
}
