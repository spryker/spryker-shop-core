<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\CompanyBusinessUnitWidget\CompanyBusinessUnitWidgetFactory getFactory()
 */
class CompanyBusinessUnitOrderSearchFormExpanderPlugin extends AbstractPlugin implements OrderSearchFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands OrderSearchForm with company business unit dropdown.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $companyBusinessUnitForm = $this->getFactory()->createCompanyBusinessUnitForm();
        $companyBusinessUnitFormDataProvider = $this->getFactory()->createCompanyBusinessUnitFormDataProvider();

        $companyBusinessUnitForm->buildForm(
            $builder,
            $companyBusinessUnitFormDataProvider->getOptions()
        );
    }
}
