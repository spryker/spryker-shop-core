<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Form;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Form\FormFactory as SymfonyFormFactory;
use Symfony\Component\Form\FormInterface;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): SymfonyFormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerReorderWidgetForm(): FormInterface
    {
        return $this->getFormFactory()->create(CustomerReorderWidgetForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerReorderItemsWidgetForm(): FormInterface
    {
        return $this->getFormFactory()->create(CustomerReorderItemsWidgetForm::class);
    }
}
