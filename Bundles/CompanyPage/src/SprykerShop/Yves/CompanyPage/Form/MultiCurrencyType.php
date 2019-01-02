<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class MultiCurrencyType extends AbstractType
{
    protected const FIELD_MULTI_CURRENCY = 'multi_currency';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'MultiCurrencyType';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAmountPerCurrencyFields($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAmountPerCurrencyFields(FormBuilderInterface $builder): self
    {
        $currencyIsoCodes = $this->getFactory()->getStore()->getCurrencyIsoCodes();

        foreach ($currencyIsoCodes as $currencyIsoCode) {
            $this->addAmountPerCurrencyField($builder, $currencyIsoCode);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $currencyIsoCode
     *
     * @return $this
     */
    protected function addAmountPerCurrencyField(FormBuilderInterface $builder, string $currencyIsoCode): self
    {
        $builder->add($currencyIsoCode, NumberType::class, [
            'attr' => ['placeholder' => 'company_page.multi_currency_type.name.cent_amount.' . $currencyIsoCode],
            'label' => strtoupper($currencyIsoCode),
        ]);

        return $this;
    }
}
