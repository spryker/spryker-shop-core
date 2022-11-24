<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Form\Type;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShopUi\ShopUiFactory getFactory()
 * @method \SprykerShop\Yves\ShopUi\ShopUiConfig getConfig()
 */
class AbstractFormattedType extends AbstractType
{
    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const OPTION_GROUPING_SEPARATOR_SYMBOL = 'grouping_separator_symbol';

    /**
     * @var string
     */
    protected const OPTION_DECIMAL_SEPARATOR_SYMBOL = 'decimal_separator_symbol';

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $options = $this->provideMissingFormattingOptions($options);

        $view->vars[static::OPTION_GROUPING_SEPARATOR_SYMBOL] = $options[static::OPTION_GROUPING_SEPARATOR_SYMBOL];
        $view->vars[static::OPTION_DECIMAL_SEPARATOR_SYMBOL] = $options[static::OPTION_DECIMAL_SEPARATOR_SYMBOL];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_LOCALE);

        $resolver->setDefined([
            static::OPTION_GROUPING_SEPARATOR_SYMBOL,
            static::OPTION_DECIMAL_SEPARATOR_SYMBOL,
        ]);

        $resolver->setAllowedTypes(static::OPTION_GROUPING_SEPARATOR_SYMBOL, 'string');
        $resolver->setAllowedTypes(static::OPTION_DECIMAL_SEPARATOR_SYMBOL, 'string');
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function provideMissingFormattingOptions(array $options): array
    {
        if ($this->isAllFormattingOptionsSet($options)) {
            return $options;
        }

        $numberFormatConfigTransfer = $this->getFactory()
            ->getUtilNumberService()
            ->getNumberFormatConfig($options[static::OPTION_LOCALE]);

        $options[static::OPTION_GROUPING_SEPARATOR_SYMBOL] = $options[static::OPTION_GROUPING_SEPARATOR_SYMBOL] ?? $numberFormatConfigTransfer->getGroupingSeparatorSymbolOrFail();
        $options[static::OPTION_DECIMAL_SEPARATOR_SYMBOL] = $options[static::OPTION_DECIMAL_SEPARATOR_SYMBOL] ?? $numberFormatConfigTransfer->getDecimalSeparatorSymbolOrFail();

        return $options;
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function isAllFormattingOptionsSet(array $options): bool
    {
        return isset(
            $options[static::OPTION_DECIMAL_SEPARATOR_SYMBOL],
            $options[static::OPTION_GROUPING_SEPARATOR_SYMBOL],
        );
    }
}
