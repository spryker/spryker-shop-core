<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\ShopUi\Form\Type\FormattedMoneyType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageConfig getConfig()
 */
class QuoteRequestAgentVersionItemsSubForm extends AbstractType
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRICE = 'quote_request_agent_page.form.invalid_price';

    /**
     * @var string
     */
    protected const PATTERN_MONEY = '/^\d*\.?\d{0,2}$/';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemTransfer::class,
            'label' => false,
        ]);
        $resolver->setRequired([
            QuoteRequestAgentForm::OPTION_PRICE_MODE,
            QuoteRequestAgentForm::OPTION_LOCALE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options[QuoteRequestAgentForm::OPTION_PRICE_MODE] === static::PRICE_MODE_GROSS) {
            $this->addManualGrossPriceField($builder, $options);

            return;
        }

        $this->addManualNetPriceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addManualGrossPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ItemTransfer::SOURCE_UNIT_GROSS_PRICE, FormattedMoneyType::class, [
            'label' => false,
            'locale' => $options[QuoteRequestAgentForm::OPTION_LOCALE],
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(ItemTransfer::SOURCE_UNIT_GROSS_PRICE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addManualNetPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ItemTransfer::SOURCE_UNIT_NET_PRICE, FormattedMoneyType::class, [
            'label' => false,
            'locale' => $options[QuoteRequestAgentForm::OPTION_LOCALE],
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(ItemTransfer::SOURCE_UNIT_NET_PRICE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createMoneyModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                return $value !== null ? $value / 100 : null;
            },
            function ($value) {
                return $value !== null ? $value * 100 : null;
            },
        );
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createMoneyConstraint(array $options): Regex
    {
        return new Regex([
            'pattern' => static::PATTERN_MONEY,
            'message' => static::ERROR_MESSAGE_PRICE,
            'groups' => $this->getValidationGroup($options),
        ]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return string
     */
    protected function getValidationGroup(array $options): string
    {
        $validationGroup = Constraint::DEFAULT_GROUP;

        if (!empty($options['validation_group'])) {
            $validationGroup = $options['validation_group'];
        }

        return $validationGroup;
    }
}
