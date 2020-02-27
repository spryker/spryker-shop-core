<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageConfig getConfig()
 */
class QuoteRequestAgentForm extends AbstractType
{
    public const SUBMIT_BUTTON_SAVE = 'save';
    public const SUBMIT_BUTTON_SEND_TO_CUSTOMER = 'sendToCustomer';

    public const OPTION_PRICE_MODE = 'option_price_mode';
    public const OPTION_IS_QUOTE_VALID = 'option_is_quote_valid';
    public const OPTION_SHIPMENT_GROUPS = 'option_shipment_groups';

    public const FIELD_SHIPMENT_GROUPS = 'shipmentGroups';

    protected const FORMAT_VALID_UNTIL_DATE = 'Y-m-d H:i:s';
    protected const LABEL_QUOTE_REQUEST_IS_LATEST_VERSION_VISIBLE = 'quote_request_page.quote_request.labels.show_latest_version';
    protected const GLOSSARY_KEY_DATE_VIOLATION = 'quote_request_page.quote_request.violations.invalid_date';

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
        $resolver->setRequired([
            static::OPTION_PRICE_MODE,
            static::OPTION_IS_QUOTE_VALID,
            static::OPTION_SHIPMENT_GROUPS,
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
        $this->addLatestVersionForm($builder, $options)
            ->addValidUntilField($builder)
            ->addIsVisibleLatestVersionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLatestVersionForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            QuoteRequestTransfer::LATEST_VERSION,
            QuoteRequestAgentVersionSubForm::class,
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
     *
     * @return $this
     */
    protected function addValidUntilField(FormBuilderInterface $builder)
    {
        $currentStoreTimezone = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore()
            ->getTimezone();

        $builder->add(QuoteRequestTransfer::VALID_UNTIL, DateTimeType::class, [
            'label' => false,
            'widget' => 'single_text',
            'required' => false,
            'view_timezone' => $currentStoreTimezone,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
        ]);

        $builder->get(QuoteRequestTransfer::VALID_UNTIL)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsVisibleLatestVersionField(FormBuilderInterface $builder)
    {
        $builder->add(QuoteRequestTransfer::IS_LATEST_VERSION_VISIBLE, CheckboxType::class, [
            'label' => static::LABEL_QUOTE_REQUEST_IS_LATEST_VERSION_VISIBLE,
            'required' => false,
        ]);

        return $this;
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
                    $value = $value->format(static::FORMAT_VALID_UNTIL_DATE);
                }

                return $value;
            }
        );
    }
}
