<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CurrentStoreMultiCurrencyType extends AbstractType
{
    protected const MIN_MONEY_INT = 0;

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
    protected function addAmountPerCurrencyFields(FormBuilderInterface $builder)
    {
        $currencyIsoCodes = $this->getCurrentStore()
            ->getAvailableCurrencyIsoCodes();

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
    protected function addAmountPerCurrencyField(FormBuilderInterface $builder, string $currencyIsoCode)
    {
        $currentStoreName = $this->getCurrentStore()
            ->getName();

        $options = [
            'attr' => ['placeholder' => 'company_page.multi_currency_type.name.cent_amount.' . $currencyIsoCode],
            'label' => strtoupper($currencyIsoCode),
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => static::MIN_MONEY_INT,
                ]),
            ],
            'property_path' => sprintf(
                '[%s][%s]',
                $currentStoreName,
                $currencyIsoCode
            ),
        ];

        $builder->add($currencyIsoCode, NumberType::class, $options);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore(): StoreTransfer
    {
        return $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore();
    }
}
