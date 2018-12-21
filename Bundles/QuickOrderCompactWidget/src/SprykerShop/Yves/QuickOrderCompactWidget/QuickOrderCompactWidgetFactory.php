<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderCompactWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuickOrderCompactWidget\Form\QuickOrderCompactForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class QuickOrderCompactWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuickOrderCompactForm(): FormInterface
    {
        return $this->getFormFactory()->create(QuickOrderCompactForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
