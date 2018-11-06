<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class RegisterFormTypePlugin extends AbstractPlugin
{
    /**
     * @param string $name
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function getRegisterFormNamedBuilder(string $name, array $options = [])
    {
        return $this->getFactory()
            ->createCustomerFormFactory()
            ->getRegisterFormNamedBuilder($name, $options);
    }
}
