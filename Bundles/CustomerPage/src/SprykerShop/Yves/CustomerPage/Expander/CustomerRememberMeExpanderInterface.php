<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;

interface CustomerRememberMeExpanderInterface
{
    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function expand(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface;
}
